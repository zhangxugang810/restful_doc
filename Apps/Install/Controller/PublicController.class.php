<?php

class PublicController extends Controller {

    public function __construct($data) {
        parent::__construct($data);
        if(file_exists('./Data/install.lock')){
            header('Location:'.U('Tester/Index/index'));
        }
    }
    
}

