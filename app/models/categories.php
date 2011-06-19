<?php

require_once 'lib/simplepie/SimplePie.php';

class Categories extends AppModel {
    protected $beforeSave = array('getOrder', 'getItemType');
    protected $afterSave = array('updateFeed');
    protected $beforeDelete = array('deleteChildren');
    protected $defaultScope = array(
        'order' => '`order` ASC'
    );
    protected $validates = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Você precisa definir um título'
            ),
            array(
                'rule' => array('maxLength', 50),
                'message' => 'O título de uma categoria não pode conter mais do que 50 caracteres'
            )
        )
    );

    public function __construct($data = array()) {
        parent::__construct($data);

        if(is_null($this->id)) {
            $this->data['visibility'] = true;
        }
    }

    public function createRoot($site) {
        $root = Model::load('Segments')->firstById($site->segment)->root;
        $this->id = null;
        $this->save(array(
            'title' => __($root),
            'site_id' => $site->id,
            'parent_id' => 0
        ));
    }

    public function getRoot($site_id) {
        return $this->firstBySiteIdAndParentId($site_id, 0);
    }

    public function children($id = null) {
        if(is_null($id)) {
            $id = $this->id;
        }

        $result = array(
            'categories' => Model::load('Categories')->allByParentId($id)
        );

        $types = Model::load('BusinessItems')->typesForParent($id);
        foreach($types as $type) {
            $result[$type->type] = Model::load('BusinessItems')->allByParentIdAndType($id, $type->type);
        }

        return $result;
    }

    public function childrenItems($limit = null) {
        $type = Inflector::underscore($this->type);
        $model = Model::load($type);
        return $model->allByParentId($this->id, array(
            'limit' => $limit
        ));
    }

    public function hasFeed() {
        $populate = $this->populate;
        return $populate == 'auto';
    }

    public function childrenCount() {
        return Model::load('BusinessItems')->count(array(
            'conditions' => array(
                'parent_id' => $this->id
            )
        ));
    }

    public function breadcrumbs() {
        $parent_id = $this->parent_id;
        $breadcrumbs = array($this);

        while($parent_id > 0) {
            $category = $this->firstById($parent_id);
            $breadcrumbs []= $category;
            $parent_id = $category->parent_id;
        }

        return array_reverse($breadcrumbs);
    }

    public function parent() {
        if($this->parent_id) {
            return $this->firstById($this->parent_id);
        }
    }

    public function recursiveById($id, $depth) {
        $results = array($this->firstById($id));

        if($depth > 0) {
            $children = $this->recursiveByParentId($id, $depth - 1);
            $results = array_merge($results, $children);
        }

        return $results;
    }

    public function recursiveByParentId($parent_id, $depth) {
        $results = $this->allByParentIdAndVisibility($parent_id, 1);

        if($depth > 0) {
            foreach($results as $result) {
                $children = $this->recursiveByParentId($result->id, $depth - 1);
                $results = array_merge($results, $children);
            }
        }

        return $results;
    }

    public function toJSON() {
        $data = $this->data;
        $data['items_count'] = $this->childrenCount();
        return $data;
    }

    public function forceDelete($id) {
        $this->deleteChildren($id, true);
        $this->deleteAll(array(
            'conditions' => array(
                'id' => $id
            )
        ));
    }

    public function updateArticles() {
        $log = KLogger::instance(Filesystem::path('log'));

        $articles = Model::load('Articles');
        $feed = $this->getFeed();
        $items = $feed->get_items();

        $log->logInfo('Importing feed "%s"', $this->feed_url);
        $log->logInfo('%d articles found', count($items));

        foreach($items as $item) {
            if(!$articles->articleExists($this->id, $item->get_id())) {
                $articles->addToFeed($this, $item);
            }
            else {
                $log->logInfo('Article "%s" already exists. Skipping', $item->get_id());
            }
        }

        $this->cleanup();

        $this->updateAttributes(array(
            'updated' => date('Y-m-d H:i:s')
        ));
        $this->save();
    }

    public function cleanup() {
        $conditions = array(
            'site_id' => $this->site_id,
            'parent_id' => $this->parent_id
        );
        $count = Model::load('Articles')->count(array(
            'conditions' => $conditions
        ));

        if($count > 50) {
            $articles = Model::load('Articles')->allOrdered(array(
                'conditions' => $conditions,
                'limit' => $count - 50,
                'order' => 'pubdate ASC'
            ));
            foreach($articles as $article) {
                Model::load('Articles')->delete($article->id);
            }
        }
    }

    protected function getFeed() {
        $feed = new SimplePie();
        $feed->enable_cache(false);
        $feed->set_feed_url($this->feed_url);
        $feed->init();

        return $feed;
    }

    protected function getOrder($data) {
        if(is_null($this->id) && $data['parent_id'] != 0) {
            $siblings = $this->toList(array(
                'fields' => array('id', '`order`'),
                'conditions' => array(
                    'site_id' => $data['site_id'],
                    'parent_id' => $data['parent_id']
                ),
                'order' => '`order` DESC',
                'displayField' => 'order',
                'limit' => 1
            ));

            if(!empty($siblings)) {
                $data['order'] = current($siblings) + 1;
            }
            else {
                $data['order'] = 0;
            }
        }

        return $data;
    }

    protected function getItemType($data) {
        if(is_null($this->id)) {
            $site = Model::load('Sites')->firstById($this->site_id);
            $items = (array) $site->itemTypes();

            if(!array_key_exists('type', $data) || !in_array($data['type'], $items)) {
                $data['type'] = $items[0];
            }
        }

        return $data;
    }

    protected function updateFeed($created) {
        if(isset($this->data['populate']) && $this->data['populate'] == 'auto') {
            if(!isset($this->data['feed_url'])) {
                $this->data['feed_url'] = '';
            }
            $is_set = isset($this->data['feed']);
            $is_empty = $is_set && empty($this->data['feed']);

            if($is_empty or $is_set && $this->data['feed'] != $this->data['feed_url']) {
                $children = $this->childrenItems();
                $this->deleteSet(Model::load('BusinessItems'), $children);
                $this->update(array(
                    'conditions' => array('id' => $this->id)
                ), array(
                    'feed_url' => ''
                ));
            }

            if($is_set && !$is_empty && $this->data['feed'] != $this->data['feed_url']) {
                $this->feed_url = $this->data['feed'];
                $this->update(array(
                    'conditions' => array('id' => $this->id)
                ), array(
                    'feed_url' => $this->feed_url
                ));

                $this->updateArticles();
            }
        }
    }

    protected function deleteChildren($id, $force = false) {
        $self = $this->firstById($id);
        if($self->parent_id == 0 && !$force) {
            return false;
        }

        $types = $self->children();
        foreach($types as $type => $children) {
            $model = Model::load(Inflector::camelize($type));
            $this->deleteSet($model, $children);
        }

        return $id;
    }
}
