<?php

require_once 'app/models/sites.php';

class ImagesController extends AppController {
    public function delete($id = null) {
        $this->Images->delete($id);
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

	public function add(){
		$this->layout = false;
		$this->set('timestamp', $this->data['timestamp']);
	}
}
