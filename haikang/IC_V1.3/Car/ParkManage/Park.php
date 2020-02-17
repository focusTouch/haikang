<?php
namespace HaiKang\Car\ParkManage;

use HaiKang\Base\ApiBase;

class Park extends ApiBase
{
    static $uri_array = array(
        'reservationParking'=>'/api/pms/v2/parkingSpace/reservations/addition',//车位预约v2
        'queryReserveRecord'=>'/api/pms/v2/reserveRecord/page',//查询预约记录v2
        'deleteReservation'=>'/api/pms/v1/parkingSpace/reservations/deletion',//取消车位预约
        'crossRecords'=>'/api/pms/v1/crossRecords/page',//查询过车记录
        'queryImage'=>'/api/pms/v1/image',//查询车辆抓拍图片
        'deviceControl'=>'/api/pms/v1/deviceControl',//根据车道编码反控道闸
        'deviceControlBatch'=>'/api/pms/v1/deviceControlBatch',//根据停车场编码反控道闸
        'carCharge'=>'/api/pms/v1/car/charge',//车辆充值
        'deleteCarCharge'=>'/api/pms/v1/car/charge/deletion',//取消车辆包期
        'queryCarChargeList'=>'/api/pms/v1/car/charge/page',//查询车辆包期信息
    );

    /*
     * 车位预约v2
     * 简述：车辆进入停车场前预约指定停车场车位（目前提供给访客组件使用）。
     * 支持按时间段进行车辆预约，根据需要配置是否收费、进出次数及联系人信息；在预约时间段内有效，可进行一次或多次进出场，以及出场时收费还是免费放行。
     * 车位预约有诸多限制，允许临时车预约，固定车、一户多车车辆、黑名单车辆、场内车不允许预约，同一车牌号只能预约一次。
     * 支持：支持通过停车库唯一标识、车牌号等信息进行车位预约。
     * */
    public function reservationParking($in_data){
        $array = array();
        if(!isset($in_data['parkSyscode'])){
            $this->error[] = '停车库唯一标识必须';
        }else{
            $array['parkSyscode'] = $in_data['parkSyscode'];
        }
        if(!isset($in_data['plateNo'])){
            $this->error[] = '车牌号码必须';
        }else{
            $array['plateNo'] = $in_data['plateNo'];
        }
        isset($in_data['phoneNo']) ? $array['phoneNo'] = $in_data['phoneNo']:'';
        isset($in_data['owner']) ? $array['owner'] = $in_data['owner']:'';
        isset($in_data['allowTimes']) ? $array['allowTimes'] = $in_data['allowTimes']:'';
        isset($in_data['isCharge']) ? $array['isCharge'] = $in_data['isCharge']:'';
        isset($in_data['resvWay']) ? $array['resvWay'] = $in_data['resvWay']:'';
        isset($in_data['startTime']) ? $array['startTime'] = $in_data['startTime']:'';
        isset($in_data['endTime']) ? $array['endTime'] = $in_data['endTime']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询预约记录v2
     * 简述：提供第三方通过接口查询平台预约记录；
     * 支持：支持通过车牌号、预约状态、预约方式、预约类型、停车库及时间段查询预约信息。
     * */
    public function queryReserveRecord($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '获取数量必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        isset($in_data['parkSyscode']) ? $array['parkSyscode'] = $in_data['parkSyscode']:'';
        isset($in_data['plateNo']) ? $array['plateNo'] = $in_data['plateNo']:'';
        isset($in_data['resvState']) ? $array['resvState'] = $in_data['resvState']:'';
        isset($in_data['resvWay']) ? $array['resvWay'] = $in_data['resvWay']:'';
        isset($in_data['allowTimes']) ? $array['allowTimes'] = $in_data['allowTimes']:'';
        isset($in_data['isCharge']) ? $array['isCharge'] = $in_data['isCharge']:'';
        isset($in_data['startTime']) ? $array['startTime'] = $in_data['startTime']:'';
        isset($in_data['endTime']) ? $array['endTime'] = $in_data['endTime']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 取消车位预约
     * 车辆进入停车场前取消预约（目前提供给访客组件使用）
     * */
    public function deleteReservation($in_data){
        $array = array();
        if(!isset($in_data['reserveOrderNo'])){
            $this->error[] = '预约单号必须';
        }else{
            $array['reserveOrderNo'] = $in_data['reserveOrderNo'];
        }
        isset($in_data['way']) ? $array['way'] = $in_data['way']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询过车记录
     * 简述：当车辆进、出停车场时，会产生过车记录，过车记录信息包括车辆的车牌号、车辆所在的车道标识、出入口标识、停车场标识、出场标识、过车时间、车辆图片信息等，调用方可以使用该接口同步过车信息，了解车辆进出场情况；
     * 支持：调用方可以通过该接口分页获取过车记录信息，每页记录总数不超过1000，其它查询条件之间是与的关系，可以根据这些字段进行过滤筛选查询到的数据。
     * 注：车牌条件为模糊查询条件
     * */
    public function crossRecords($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '获取数量必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        isset($in_data['parkSyscode']) ? $array['parkSyscode'] = $in_data['parkSyscode']:'';
        isset($in_data['entranceSyscode']) ? $array['entranceSyscode'] = $in_data['entranceSyscode']:'';
        isset($in_data['plateNo']) ? $array['plateNo'] = $in_data['plateNo']:'';
        isset($in_data['cardNo']) ? $array['cardNo'] = $in_data['cardNo']:'';
        isset($in_data['startTime']) ? $array['startTime'] = date(DATE_ISO8601,$in_data['startTime']):'';
        isset($in_data['endTime']) ? $array['endTime'] = date(DATE_ISO8601,$in_data['endTime']):'';
        isset($in_data['vehicleOut']) ? $array['vehicleOut'] = $in_data['vehicleOut']:'';
        isset($in_data['vehicleType']) ? $array['vehicleType'] = $in_data['vehicleType']:'';
        isset($in_data['releaseResult']) ? $array['releaseResult'] = $in_data['releaseResult']:'';
        isset($in_data['releaseWay']) ? $array['releaseWay'] = $in_data['releaseWay']:'';
        isset($in_data['releaseReason']) ? $array['releaseReason'] = $in_data['releaseReason']:'';
        isset($in_data['carCategory']) ? $array['carCategory'] = $in_data['carCategory']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询车辆抓拍图片
     * 停车库过车记录、预账单中带有车辆或人员图片信息，此接口提供这些图片信息展示的数据
     * */
    public function queryImage($in_data){
        $array = array();
        if(!isset($in_data['aswSyscode'])){
            $this->error[] = '图片服务唯一标识码必须';
        }else{
            $array['aswSyscode'] = $in_data['aswSyscode'];
        }
        if(!isset($in_data['picUri'])){
            $this->error[] = '图片Uri必须';
        }else{
            $array['picUri'] = $in_data['picUri'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据车道编码反控道闸
     * 简述：车道下的道闸拥有开闸、关闸和常开的管控能力，为调用方提供控闸能力；
     * 支持：支持通过车道唯一标识和控闸命令信息进行控闸。
     * */
    public function deviceControl($in_data){
        $array = array();
        if(!isset($in_data['roadwaySyscode'])){
            $this->error[] = '车道唯一标识必须';
        }else{
            $array['roadwaySyscode'] = $in_data['roadwaySyscode'];
        }
        if(!isset($in_data['command'])){
            $this->error[] = '控闸命令必须';
        }else{
            $array['command'] = $in_data['command'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据车道编码反控道闸
     * 简述：车道下的道闸拥有关闸、常开的批量管控能力，为调用方提供批量控闸能力；
     * 支持：支持通过控闸命令批量控制停车库下的所有道闸。
     * */
    public function deviceControlBatch($in_data){
        $array = array();
        if(!isset($in_data['parkSyscode'])){
            $this->error[] = '停车场唯一标识码必须';
        }else{
            $array['parkSyscode'] = $in_data['parkSyscode'];
        }
        if(!isset($in_data['command'])){
            $this->error[] = '控闸命令必须';
        }else{
            $array['command'] = $in_data['command'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 车辆充值
     * 简述：车辆添加后，有临时车、固定车之分，充值包期后是固定车，未包期或包期过期的是临时车，车辆出场需要进行收费。
     * 支持：支持通过车牌号进行特定停车场的包期充值。
     * */
    public function carCharge($in_data){
        $array = array();
        if(!isset($in_data['parkSyscode'])){
            $this->error[] = '停车场唯一标识码必须';
        }else{
            $array['parkSyscode'] = $in_data['parkSyscode'];
        }
        if(!isset($in_data['plateNo'])){
            $this->error[] = '车牌号码必须';
        }else{
            $array['plateNo'] = $in_data['plateNo'];
        }
        if(!isset($in_data['startTime'])){
            $this->error[] = '包期开始时间必须';
        }else{
            $array['startTime'] = $in_data['startTime'];
        }
        if(!isset($in_data['endTime'])){
            $this->error[] = '包期结束时间必须';
        }else{
            $array['endTime'] = $in_data['endTime'];
        }
        isset($in_data['fee']) ? $array['fee'] = $in_data['fee']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 取消车辆包期
     * 简述：车辆取消包期后变为临时车，可以取消某个停车库的包期，也可以取消平台所有停车库下的包期。
     * 支持：支持通过车牌号、停车库编号取消包期；停车库编号可为空，为空时取消平台所有包期
     * */
    public function deleteCarCharge($in_data){
        $array = array();
        if(!isset($in_data['plateNo'])){
            $this->error[] = '车牌号码必须';
        }else{
            $array['plateNo'] = $in_data['plateNo'];
        }
        isset($in_data['parkSyscode']) ? $array['parkSyscode'] = $in_data['parkSyscode']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询车辆包期信息
     * 简述：车辆包期后在当前停车场是固定车，自由进出场；在未包期的停车场进出场是临时车，需要收费。
     * 可通过此接口查询平台所有车辆或某个停车场里车辆的包期状态，便于展示车辆包期状态和是否固定车查询。
     * 支持：支持通过车牌号、停车场编号分页查询车辆包期信息。
     * */
    public function queryCarChargeList($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '获取数量必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        isset($in_data['parkSyscode']) ? $array['parkSyscode'] = $in_data['parkSyscode']:'';
        isset($in_data['plateNo']) ? $array['plateNo'] = $in_data['plateNo']:'';
        $this->request_data = $array;
        return true;
    }














    /*
     * 查询停车库节点信息
     * */
    public function queryParkItem($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '获取数量必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        isset($in_data['parentIndexCode']) ? $array['parentIndexCode'] = $in_data['parentIndexCode']:'';
        isset($in_data['parentResourceType']) ? $array['parentResourceType'] = $in_data['parentResourceType']:'';
        isset($in_data['resourceTypes']) ? $array['resourceTypes'] = $in_data['resourceTypes']:'';
        isset($in_data['name']) ? $array['name'] = $in_data['name']:'';
        isset($in_data['expressions']) ? $array['expressions'] = $in_data['expressions']:'';
        isset($in_data['orderBy']) ? $array['orderBy'] = $in_data['orderBy']:'';
        isset($in_data['orderType']) ? $array['orderType'] = $in_data['orderType']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取停车库节点详细信息
     * 根据节点编号indexCode、类型查询节点详细信息。
     * */
    public function queryParkItemDetail($in_data){
        $array = array();
        if(!isset($in_data['indexCodes'])){
            $this->error[] = '资源唯一标示必须';
        }else{
            $array['indexCodes'] = $in_data['indexCodes'];
        }
        if(!isset($in_data['resourceType'])){
            $this->error[] = '资源类型必须';
        }else{
            $array['resourceType'] = $in_data['resourceType'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取出入口列表
     * 根据停车场唯一标识集合取指定的车入口信息
     * */
    public function getEntranceList($in_data){
        $array = array();
        if(!isset($in_data['parkIndexCodes'])){
            $this->error[] = '停车场唯一标识集必须';
        }else{
            $array['parkIndexCodes'] = $in_data['parkIndexCodes'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取车道列表
     * 根据出入口唯一标识集合获取车道信息
     * */
    public function getRoadwayList($in_data){
        $array = array();
        if(!isset($in_data['entranceIndexCodes'])){
            $this->error[] = '出入口唯一标识集必须';
        }else{
            $array['entranceIndexCodes'] = $in_data['entranceIndexCodes'];
        }
        $this->request_data = $array;
        return true;
    }

}