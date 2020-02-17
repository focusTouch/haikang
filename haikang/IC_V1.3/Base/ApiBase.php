<?php
namespace HaiKang\Base;

class ApiBase
{
    static $uri_array = array();
    public $use_uri = '';
    public $request_data = array();
    public $error = array();

    public function getUri($action){
        if(!$action || !isset(static::$uri_array[$action])){
            return false;
        }
        $this->use_uri = static::$uri_array[$action];
        return true;
    }

    public function runMethod($action='',$data=array()){//var_dump($this);
        if(!is_callable(array($this,$action))){
            return false;
        }
        $this->$action($data);
        return true;
    }

    public function selfInit(){
        $this->use_uri = '';
        $this->request_data = array();
    }

}