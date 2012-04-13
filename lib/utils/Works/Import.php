<?php
namespace utils;
require_once 'lib/utils/Work.php';

class Import extends Work
{
    const INCLUSIVE = 0;
    const EXCLUSIVE = 1;
    
    protected $category;
    protected $job;
    protected $fileDir = '/public/uploads/imports/';
    protected $file;
    protected $fields;
    protected $isJob = true;
    protected $method;
    protected $imported = array();
    
    public function init()
    {
        $this->log->logInfo('import work: init the work');
        if ($this->isJob) {
            $this->job = \app\models\Jobs::first(array(
                'conditions' => array('type' => 'import'), 
                'order' => 'modified',
            ));
            $this->method = $this->job->params->method;
        }
    }

    public function canRun()
    {
        if ($this->isJob && !$this->job) {
            return false;
        }
        
        if ($this->file() && $this->category()) {
            return true;
        }
    }

    public function run()
    {
        if ($this->canRun()) {
            $classname = '\app\models\items\\' .
            \Inflector::camelize($this->category()->type);

            while ($item = $this->next()) {
                if (isset($item['id'])) {
                    $record = $classname::find('first', array(
                        'conditions' => array(
                            '_id' => $item['id']
                        ),
                    ));
                }
                
                if (! $record) {
                    $record = $classname::create();
                }

                $item['parent_id'] = $this->category()->id;
                $item['site_id'] = $this->category()->site_id;
                $item['type'] = $this->category()->type;
                $record->set($item);
                $record->save();
                if (self::EXCLUSIVE == $this->method) {
                    $this->imported[] = (string)$record->_id;
                }
            }
        }
        return $this->stop();
    }
    
    public function category(\Categories $category = null) 
    {
        if (!($this->category instanceof \Categories)) {
            $this->category = $category ? $category : 
            \Model::load('categories')->firstById($this->job->params->category_id);
        }
        return $this->category;
    }
    
    public function setMethod($method = null)
    {
        $this->method = (int)$method;
    }
    
    public function notIsJob()
    {
        $this->isJob = false;
    }

    public function file($file = false)
    {
        if (!$this->file) {
            $file = $file ? $file : APP_ROOT . $this->fileDir . $this->job->params->file;
            if (is_readable($file)) {
                $this->file = fopen($file, 'r');
            } else {
                $this->log->logError('Import work: file doesn\'t exist');
            }
        }
        return $this->file;
    }
    
    protected function next()
    {
        $fields = $this->fields();
        if (!$row = fgetcsv($this->file(), 3000)) {
            return false;
        }
        foreach ($fields as $key => $field) {
            if (isset($row[$key])) {
                $data[$field] = $row[$key];
            }
        }
        return $data;
    }

    protected function fields()
    {
        if (!$this->fields) {
            rewind($this->file());
            $this->fields = fgetcsv($this->file(), 3000);
        }
        return $this->fields;
    }

    protected function stop() {
        if (self::EXCLUSIVE == $this->method && count($this->imported)) {
            $classname = '\app\models\items\\' .
                         \Inflector::camelize($this->category()->type);
            $classname::remove(
    array(
            'parent_id' => $this->category()->id,
            '_id' => array(
                '$nin' => $this->imported,
            ),
        )
            );
        }
        return $this->deleteJob();
    }

    protected function deleteJob() 
    {
        if(!$this->isJob || !$this->job) {
            return true;
        }
        $this->log->logInfo("import work: all items processed in job {$this->job->_id}");
        if ($this->file()) {
            fclose($this->file());
            unlink(APP_ROOT . $this->fileDir . $this->job->params->file);
        }
        return $this->job->delete();
    }
}
