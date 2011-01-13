<?php

class HomeController extends AppController {
    public $uses = array('Feeds');
    
    public function index() {
        $this->Feeds->first()->updateArticles();
        // @$detailsImage = exif_read_data($mapped_object['imageUrl']);
        // if($detailsImage !=null && is_array($detailsImage)){
        //   $mapped_object['imageLength'] = $detailsImage["FileSize"];
        //   $mapped_object['imageLengthOctal'] = decoct($detailsImage["FileSize"]);
        //   $mapped_object['imageType'] = $detailsImage["MimeType"];
        // }
    }
}