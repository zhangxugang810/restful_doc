<?php
class IndexController extends Controller{
    public function __construct($data) {
        parent::__construct($data);
    }

    public function index(){
        $this->display();
    }
}