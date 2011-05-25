<?php

class Categories extends AppModel {
    protected $beforeSave = array('getOrder', 'getItemType');
    protected $afterSave = array('getFeedUrl');
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

    public function listAvailableParents($site_id) {
        $root = $this->getRoot($site_id);
        $list = array(
            $root->id => $root->title
        );

        $list += $this->toList(array(
            'conditions' => array(
                'site_id' => $site_id,
                'parent_id' => $root->id
            )
        ));

        return $list;
    }

    public function toListBySiteId($site_id) {
        return $this->toList(array(
            'conditions' => array(
                'site_id' => $site_id
            )
        ));
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

    public function hasChildren() {
        $conditions = array(
            'conditions' => array(
                'parent_id' => $this->id
            )
        );

        $total = 0;
        $total += Model::load('Categories')->count($conditions);
        $total += Model::load('BusinessItems')->count($conditions);

        return (bool) $total;
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
        $results = $this->allByParentId($parent_id);

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

    protected function getFeedUrl($created) {
        if($created && array_key_exists('feed', $this->data) && !empty($this->data['feed'])) {
            Model::load('Feeds');
            $feed = new Feeds(array(
                'site_id' => $this->site_id,
                'category_id' => $this->id,
                'link' => $this->data['feed']
            ));
            $feed->save();
            $feed->updateArticles();
        }
    }

    protected function deleteChildren($id, $force = false) {
        $self = $this->firstById($id);
        if($self->parent_id == 0 && !$force) {
            return false; // don't allow root's deletion
        }

        $types = $self->children();
        foreach($types as $type => $children) {
            foreach($children as $child) {
                $child->delete($child->id);
            }
        }

        return $id;
    }
}
