<?php
namespace HaiKang;

class Config
{
    static function getConfig($config){
        //获取该公司的平台服务配置
//        $company_config = (new \base_company_config())->getConfig($cid);
        $company_config = $config;
        if(!$company_config){
            return array();
        }
        return array(
            'host'=>$company_config['server'],
            'api_root'=>'/artemis',
            'appKey'=>$company_config['appkey'],
            'appSecret'=>$company_config['secret'],
            'log_path'=>rootpath,
        );
//        return array(
//            'host'=>'http://192.168.1.181:9016',
//            'api_root'=>'/artemis',
//            'appKey'=>'23292722',
//            'appSecret'=>'Fys96HnoQsNTGuvP0BBD',
//            'log_path'=>rootpath,
//        );
    }
}