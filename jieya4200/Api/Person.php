<?php
namespace JieYa4200\Api;

class Person extends Base implements ApiTemplate
{
    static $map=array(
        'edit'=>'people.php?do=add',
        'add'=>'people.php?do=add',
    );

    public function __construct() {
    }

    public function add($info){
        $res = $this->curl_post(self::$map['add'],$info);
        return $res;
    }

    public function edit($info){
        $res = $this->curl_post(self::$map['edit'],$info);
        return $res;
    }



}