<?php

class ImagesController extends AppController {
    public function delete($id = null) {
        $this->Images->delete($id);
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
