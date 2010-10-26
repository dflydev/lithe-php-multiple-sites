<?php
require_once('lithe_Controller.php');
class DemoController extends lithe_Controller {

    public function index() {
        $this->view = 'demo/index';
    }
    
    public function submit() {
        $this->model->set('name', $this->httpRequest->postParam('name'));
        $this->view = 'demo/submission';
    }

}