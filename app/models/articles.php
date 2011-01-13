<?php

class Articles extends AppModel {
    protected static $blacklist = array(
        'gravatar.com'
    );
    protected $defaultScope = array(
        'recursion' => 0,
        'orm' => false,
        'order' => 'pubdate DESC'
    );
    
    public function allBySiteSlug($slug) {
        return $this->all();
    }
    
    public function articleExists($guid) {
        return $this->exists(compact('guid'));
    }
    
    public function addToFeed($feed, $item) {
        $this->id = null;
        
        $article = array(
            'feed_id' => $feed->id,
            'guid' => $item->get_id(),
            'link' => $item->get_link(),
            'title' => $item->get_title(),
            'description' => $item->get_content(),
            'author' => $item->get_author()->get_name(),
            'pubdate' => $item->get_date('Y-m-d')
        );
        
        // $enclosure = $this->getEnclosure($item);
        // if(!empty($enclosure)) {
        //     
        // }
        
        return $this->save($article);
    }
    
    protected function getEnclosure($item) {
        $enclosures = $item->get_enclosures();
        
        foreach($enclosures as $enclosure) {
            if($enclosure->get_medium() != 'image') continue;
            
            $link = $enclosure->get_link();
            if(!$this->isBlackListed($link)) {
                return $this->saveEnclosure($enclosure);
            }
        }
        
        return array();
    }
    
    protected function saveEnclosure($enclosure) {
        $params = array(
            'filename' => 'image',
            'ext' => 'jpg'
        );
        $path = String::insert('public/images/articles/:filename.:ext', $params);
        $content = file_get_contents($enclosure->get_link());
        Filesystem::write($path, $content);


        // @$detailsImage = exif_read_data($mapped_object['imageUrl']);
        // if($detailsImage !=null && is_array($detailsImage)){
        //   $mapped_object['imageLength'] = $detailsImage["FileSize"];
        //   $mapped_object['imageLengthOctal'] = decoct($detailsImage["FileSize"]);
        //   $mapped_object['imageType'] = $detailsImage["MimeType"];
        // }


    }
    
    protected function isBlackListed($link) {
        foreach(self::$blacklist as $i) {
            $pattern = preg_quote($i);
            if(preg_match('%' . $pattern . '%', $link)) {
                return true;
            }
        }
        
        return false;
    }
}