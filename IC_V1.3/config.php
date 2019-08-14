<?php
namespace HaiKang;

class Config
{
    static function getConfig($cid){
        //获取该公司的平台服务配置
        $company_config = (new \base_company_config())->getConfig($cid);
        if(!$company_config || $company_config['platform'] != 3){
            return array();
        }
        return array(
            'host'=>$company_config['server'],
            'api_root'=>'/artemis',
            'appKey'=>$company_config['appkey'],
            'appSecret'=>$company_config['secret'],
            'log_path'=>rootpath,
        );
        return array(
            'host'=>'http://192.168.98.118:9016',
            'api_root'=>'/artemis',
            'appKey'=>'23292722',
            'appSecret'=>'Fys96HnoQsNTGuvP0BBD',
            'log_path'=>rootpath,
        );
    }
}