<?php
namespace RuiDa_v5000;
use think\facade\Db as TPDb;

class Service
{
    public $error = [];
    public function __construct($config) {
        $res = Config::setConfig($config);
        if(!$res['succ']){
            $this->error[] = $res['msg'];
        }
    }

    /*
     * 根据公司名称获取对应车场区域
     * */
    public function getParkAreaByCompany($name){
        $info = TPDb::table('park_parking_area')->where('name',$name)->find();
        return $info?:[];
    }

    /*
     * 根据停车区域id获取对应车场进出口区域列表
     * */
    public function getEntranceListByAreaId($area_id){
        $list = TPDb::table('park_entrance_area')->where('parking_area_id',$area_id)->select();
        return $list->isEmpty()?[]:$list->toArray();
    }

    /*
     * 根据停车进出口id获取对应岗亭列表
     * */
    public function getPavilioListByEntranceId($entrance_id){
        $list = TPDb::table('park_pavilio')->where('entrance_area_id',$entrance_id)->select();
        return $list->isEmpty()?[]:$list->toArray();
    }

    /*
     * 根据停车岗亭id获取对应通道列表
     * */
    public function getChannelListByPavilioId($pavilio_id){
        $list = TPDb::table('park_channel')->where('pavilio_id',$pavilio_id)->select();
        return $list->isEmpty()?[]:$list->toArray();
    }

    /*
     * 获取进出记录列表
     * */
    public function getRecordList($data,$page,$limit){
        $list_in = $this->getRecordinList($data,$page,$limit);
        $list_out = $this->getRecordoutList($data,$page,$limit);
        $list = array_merge($list_in,$list_out);
        //时间排序
        $list_date_arr = array_column($list, 'create_time');
        array_multisort($list_date_arr, $list);
        return (array)$list;
    }
    /*
     * 获取进出记录总记录数
     * */
    public function getRecordCount($data){
        $count_in = $this->getRecordinCount($data);
        $count_out = $this->getRecordoutCount($data);
        $count = max($count_in,$count_out);
        return $count;
    }

    /*
     * 获取进场记录
     * */
    private function getRecordinList($data,$page,$limit){
        $where = array(
           array('check_in_time','>=',$data['begin_time']),
           array('check_in_time','<',$data['end_time']),
        );
        $list = TPDb::table('park_recordin_side')->where($where)->order('check_in_time','desc')->limit(($page-1)*$limit, $limit)->select();
        $list = $list->isEmpty()?[]:$list->toArray();
        foreach ($list as &$info){
            $info['carOut'] = 1;
            $info['channel_id'] = $info['channel_in_id'];
            $info['create_time'] = $info['check_in_time'];
        }
        return $list;
    }

    /*
     * 获取进场总记录数
     * */
    private function getRecordinCount($data){
        $where = array(
            array('check_in_time','>=',$data['begin_time']),
            array('check_in_time','<',$data['end_time']),
        );
        $count = TPDb::table('park_recordin_side')->where($where)->count();
        return (int)$count;
    }

    /*
     * 获取出场记录
     * */
    private function getRecordoutList($data,$page,$limit){
        $where = array(
            array('check_out_time','>=',$data['begin_time']),
            array('check_out_time','<',$data['end_time']),
        );
        $list = TPDb::table('park_recordout')->where($where)->order('check_out_time','desc')->limit(($page-1)*$limit, $limit)->select();
        $list = $list->isEmpty()?[]:$list->toArray();
        foreach ($list as &$info){
            $info['carOut'] = 0;
            $info['channel_id'] = $info['channel_out_id'];
            $info['create_time'] = $info['check_out_time'];
        }
        return $list;
    }

    /*
     * 获取出场记录总记录数
     * */
    private function getRecordoutCount($data){
        $where = array(
            array('check_out_time','>=',$data['begin_time']),
            array('check_out_time','<',$data['end_time']),
        );
        $count = TPDb::table('park_recordout')->where($where)->count();
        return (int)$count;
    }



}