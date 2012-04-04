<?php
require_once dirname(__DIR__) . '/Work.php';
class Import extends Work
{
    protected $category;
    protected $job;
    protected $fileDir = '/public/uploads/imports/';
    protected $file;
    protected $fields;

    public function init()
    {
        $this->job = \app\models\Jobs::first(array(
            'conditions' => array('type' => 'import'), 
            'order' => 'modified',
        ));
        parent::init();
    }

    public function canRun()
    {
        if (!$this->job) {
            return false;
        }
        $this->category = Model::load('categories')->firstById($this->job->params->category_id);
        if ($this->file() && $this->category) {
            return true;
        }
    }

    public function run()
    {
        if ($this->canRun()) {
            $classname = '\app\models\items\\' .
            Inflector::camelize($this->category->type);

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

                $item['parent_id'] = $this->category->id;
                $item['site_id'] = $this->category->site_id;
                $item['type'] = $this->category->type;
                $record->set($item);
                $record->save();
            }
            echo "all items processed in job {$this->job->_id} \n";
        }
        return $this->deleteJob();
    }

    public function next()
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

    protected function file()
    {
        if ($this->job && !$this->file) {
            $file = APP_ROOT . $this->fileDir . $this->job->params->file;
            if (is_readable($file)) {
                $this->file = fopen($file, 'r');
            } else {
                echo 'file don\'t exists';
            }
        }
        return $this->file;
    }

    protected function deleteJob() 
    {
        if(!$this->job) {
            return true;
        }
        if ($this->file()) {
            fclose($this->file());
            unlink(APP_ROOT . $this->fileDir . $this->job->params->file);
        }
        return $this->job->delete();
    }
}
