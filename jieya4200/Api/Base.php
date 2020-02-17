<?php
namespace JieYa4200\Api;

class Base
{
    //东南
    static $base_url = 'https://fzbj.fzjieya.cn:553/api/open/';
    static $partner = '29';
    static $secret = '630988f6c99d1a4f9181ad1137a5ff17';

    // static $base_url = 'https://bj.cn:443/api/open/';
    // static $partner = '29';
    // static $secret = '630988f6c99d1a4f9181ad1137a5ff17';
    
    
    //海盈
    // static $base_url = 'https://fzbj.fzjieya.cn:553/api/open/';
    // static $partner = '27';
    // static $secret = '2b50af1452e61372878cd425de77ff89';

    // static $base_url = 'https://bj.cn:443/api/open/';
    // static $partner = '27';
    // static $secret = '2b50af1452e61372878cd425de77ff89';
    
    
    //造船
    // static $base_url = 'https://fzbj.fzjieya.cn:553/api/open/';
    // static $partner = '28';
    // static $secret = 'd0bc6733e3c67d2c9c71f90f77baf2e9';

    // static $base_url = 'https://bj.cn:443/api/open/';
    // static $partner = '28';
    // static $secret = 'd0bc6733e3c67d2c9c71f90f77baf2e9';
    
    //发送数据
    public function add($list=[]){
        return array('succ'=>true,'msg'=>'未定义这个方法，默认成功');
    }

    public function del($list=[]){
        return array('succ'=>true,'msg'=>'未定义这个方法，默认成功');
    }

    protected function curl_post($uri,$array=[]){
        $time = time();
        $partner = static::$partner;
        $secret = static::$secret;

        $array['timestamp'] = $time;
        $array['partner'] = $partner;
        //允许不对数据进行检查
        $array['data_check'] = false;

        $tool = new apiTool($secret);
        $mysign = $tool->getSign($array);
        $array['sign'] = $mysign;

        $this->log('curlpost',$array);
        $url = static::$base_url.$uri;
        $res = curloutput($url,$array,$opt='content',$header=array());
        $res_arr = json_decode($res,true);
        if(!$res_arr){
            $this->log('curlpost',$res);
            echo $res;
            die();
        }
        if(!isset($res_arr['succ'])){
            echo '返回格式错误';
            die();
        }
        return $res_arr;
    }

    //写日志
    protected function log($key,$log){
        if(!is_dir(rootpath.'cache/jieya/')){
            mkpath(rootpath.'cache/jieya/');
        }
        if(is_array($log)){
            $log = json_encode($log,256);
        }
        file_put_contents(rootpath . 'cache/jieya/'.$key.date('Ymd').'log.txt',$log.PHP_EOL,FILE_APPEND);
    }

    //停止
    protected function stop($str){
        echo $str;
        die();
    }

}