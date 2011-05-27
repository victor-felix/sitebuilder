<?php

class ImagesController extends AppController {
    public function delete($id = null) {
        // TODO implement some security here
        $this->Images->delete($id);
    }
}
