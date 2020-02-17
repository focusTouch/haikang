<?php
namespace RuiDa_v5000;
use think\facade\Db as TPDb;

class Config
{
    static function setConfig($config){
        //获取该公司的平台服务配置
        $company_config = $config;
        if(!$company_config){
            return array('succ'=>false,'msg'=>'配置出错');
        }
        //配置数据库，采用thinkphp的orm操作包
        TPDb::setConfig([
            // 默认数据连接标识
            'default'     => 'sqlsrv',
            // 数据库连接信息
            'connections' => [
                'sqlsrv' => [
                    // 数据库类型
                    'type'     => 'sqlsrv',
                    // 主机地址
                    'hostname' => $company_config['host'],
                    // 数据库名
                    'database' => $company_config['database'],
                    // 数据库连接端口
                    'hostport'    => $company_config['port'],
                    // 数据库用户名
                    'username' => $company_config['user'],
                    // 数据库密码
                    'password'    => $company_config['password'],
                    // 数据库编码默认采用utf8
                    'charset'  => 'utf8'
                ],
            ],
        ]);
        try{
            TPDb::query('SELECT @@VERSION');
        }catch(\Exception $e){
            return array('succ'=>false,'msg'=>$e->getMessage());
        }
        return array('succ'=>true,'msg'=>'配置成功');
    }
}