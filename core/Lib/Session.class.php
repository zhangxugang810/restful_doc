<?php
class Session{
   public $name;
   /**
    * 
    * @param type $name
    */
   public function  __construct($name) {
       $this->name = $name;
   }
   
   /**
    * 
    * @param type $value
    */
   public function setSession($value){
       $_SESSION[$this->name] = $value;
   }
   
   /**
    * 
    * @return boolean
    */
   public function getSession(){
       if(isset($_SESSION[$this->name]))
           return $_SESSION[$this->name];
       else
           return false;
   }
   
   /**
    * 
    * @return type
    */
   public function delSession(){
       return $_SESSION[$this->name] = NULL;
   }
   
   /**
    * 
    * @return type
    */
   public function destroy(){
       return session_destroy();
   }
 }