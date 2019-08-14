<?php
namespace HaiKang;

class Service
{
    public function __construct($cid) {
        $this->config = (Config::getConfig($cid));
    }

    /*
     * 调用海康api接口，统一使用这个方法获取和设置海康接口
     *  $class 可以是类名(绝对命名空间) 或者 对象
     *  $action 调用的方法名
     *  $data 数据数组,请在对应类方法里做好数据过滤
     * */
    public function api($class='',$action='',$data=array()){
        if(!is_object($class)){
            if(!class_exists($class)){
                return array('succ'=>false,'msg'=>'未定义类');
            }
            $class = new $class();
        }
        if(!$class->runMethod($action,$data)){
            return array('succ'=>false,'msg'=>'方法未定义');
        }
        if($class->error){
            return array('succ'=>false,'msg'=>implode('|',$class->error));
        }
        if(!$class->getUri($action)){
            return array('succ'=>false,'msg'=>'地址标识错误');
        }
        $res = (new Tools($this->config))->http_post($class);
        //对象信息初始化
        $class->selfInit();
        return $res;
    }

}