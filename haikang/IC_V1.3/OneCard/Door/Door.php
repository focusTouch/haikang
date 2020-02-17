<?php
namespace HaiKang\OneCard\Door;

use HaiKang\Base\ApiBase;

class Door extends ApiBase
{
    static $uri_array = array(
        'doControl'=>'/api/acs/v1/door/doControl',//门禁点反控
        'events'=>'/api/acs/v1/door/events',//查询门禁点事件
        'pictures'=>'/api/acs/v1/event/pictures',//获取门禁事件的图片
        'getDeviceList'=>'/api/resource/v1/acsDevice/acsDeviceList',//获取门禁设备列表
        'queryDeviceList'=>'/api/resource/v1/acsDevice/advance/acsDeviceList',//查询门禁设备列表
        'deviceListByRegion'=>'/api/resource/v1/acsDevice/region/acsDeviceList',//根据区域编号获取下级门禁设备列表
        'getDeviceInfo'=>'/api/resource/v1/acsDevice/indexCode/acsDeviceInfo',//获取单个门禁设备信息
        'queryDoorList'=>'/api/resource/v1/acsDoor/advance/acsDoorList',//查询门禁点列表
        'queryReaderCardList'=>'/api/resource/v1/reader/search',//查询门禁读卡器列表
        'queryDoorStates'=>'/api/acs/v1/door/states',//查询门禁点状态
        'getDoorOnline'=>'/api/nms/v1/online/acs_device/get',//获取门禁设备在线状态
        'getReaderCardOnline'=>'/api/nms/v1/online/reader/get',//获取门禁读卡器在线状态
    );

    /*
     * 门禁点反控
     * 该接口支持门常开、门常闭、门开和门闭四种操作。门常开操作，门会一直处于开状态，不会自动关闭，执行门闭操作，
     * 门才会关上；门常闭操作，门会一直处于关毕状态，普通卡刷卡门不会被打开，执行门开操作，门会打开；门开操作，
     * 执行门打开动作，超过门打开时间，门会自动关上；门闭操作，执行关门动作，会立即把门关上。
     * 调用该接口，首先要通过获取门禁点资源列表的接口，获取到门禁点唯一编号，然后根据门禁点唯一编号进行反控操作，
     * 该接口支持单个和多个门禁点操作，如果所有门禁点反控操作成功，则返回成功，
     * 其他情况都返回失败，在失败的情况下，会按每个门禁点返回对应的错误。
     * */
    public function doControl($in_data){
        $array = array();
        if(!isset($in_data['doorIndexCodes'])){
            $this->error[] = '门禁点唯一标识必须';
        }else{
            $array['doorIndexCodes'] = $in_data['doorIndexCodes'];
        }
        if(!isset($in_data['controlType'])){
            $this->error[] = '具体操作必须';
        }else{
            $array['controlType'] = $in_data['controlType'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询门禁点事件
     * 该接口可以查询发生在门禁点上的人员出入事件，支持多个维度来查询，支持按时间、人员、门禁点、事件类型四个维度来查询
     * */
    public function events($in_data){
        $array = array();
        if(!isset($in_data['startTime'])){
            $this->error[] = '事件开始时间必须';
        }else{
            $array['startTime'] = date('Y-m-d\TH:i:s+08:00',$in_data['startTime']);
        }
        if(!isset($in_data['endTime'])){
            $this->error[] = '事件结束时间必须';
        }else{
            $array['endTime'] = date('Y-m-d\TH:i:s+08:00',$in_data['endTime']);
        }
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
        isset($in_data['eventType']) ? $array['eventType'] = $in_data['eventType']:'';
        isset($in_data['personName']) ? $array['personName'] = $in_data['personName']:'';
        isset($in_data['personIds']) ? $array['personIds'] = $in_data['personIds']:'';
        isset($in_data['doorName']) ? $array['doorName'] = $in_data['doorName']:'';
        isset($in_data['doorIndexCodes']) ? $array['doorIndexCodes'] = $in_data['doorIndexCodes']:'';
        isset($in_data['doorRegionIndexCode']) ? $array['doorRegionIndexCode'] = $in_data['doorRegionIndexCode']:'';
        isset($in_data['sort']) ? $array['sort'] = $in_data['sort']:'';
        isset($in_data['order']) ? $array['order'] = $in_data['order']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取门禁事件的图片
     * 配合门禁实时订阅事件附录D2.1 门禁事件使用,
     * 或配合查询门禁点事件接口使用
     * */
    public function pictures($in_data){
        $array = array();
        if(!isset($in_data['svrIndexCode'])){
            $this->error[] = '提供picUri处会提供此字段必须';
        }else{
            $array['svrIndexCode'] = $in_data['svrIndexCode'];
        }
        if(!isset($in_data['picUri'])){
            $this->error[] = '图片相对地址必须';
        }else{
            $array['picUri'] = $in_data['picUri'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取门禁设备列表
     * 获取门禁设备列表接口可用来全量同步门禁设备信息，返回结果分页展示。
     * */
    public function getDeviceList($in_data){
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
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询门禁设备列表
     * 查询门禁设备列表接口可以根据门禁设备唯一标识集、门禁设备名称、所属区域唯一标识这些查询条件来进行高级查询；
     * 若不指定查询条件，即全量获取所有的门禁设备信息。返回结果分页展示。
     * 注：若指定多个查询条件，表示将这些查询条件进行”与”的组合后进行精确查询。
     * 根据”门禁设备名称acsDevName”查询为模糊查询。
     * */
    public function queryDeviceList($in_data){
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
        isset($in_data['acsDevIndexCodes']) ? $array['acsDevIndexCodes'] = $in_data['acsDevIndexCodes']:'';
        isset($in_data['acsDevName']) ? $array['acsDevName'] = $in_data['acsDevName']:'';
        isset($in_data['regionIndexCode']) ? $array['regionIndexCode'] = $in_data['regionIndexCode']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据区域编号获取下级门禁设备列表
     * 根据指定的区域编号获取该区域下的门禁设备列表信息，返回结果分页展示。
     * 注：只返回直接下级区域的门禁设备。
     * */
    public function deviceListByRegion($in_data){
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
        isset($in_data['regionIndexCode']) ? $array['regionIndexCode'] = $in_data['regionIndexCode']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取单个门禁设备信息
     * 获取单个门禁设备信息是指根据指定的门禁设备唯一标识来获取门禁设备信息。
     * */
    public function getDeviceInfo($in_data){
        $array = array();
        if(!isset($in_data['acsDevIndexCode'])){
            $this->error[] = '门禁设备唯一标识必须';
        }else{
            $array['acsDevIndexCode'] = $in_data['acsDevIndexCode'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询门禁点列表
     * 查询门禁点列表接口可以根据门禁点唯一标识集、门禁点名称、门禁设备唯一标识、所属区域唯一标识这些查询条件来进行高级查询；
     * 若不指定查询条件，即全量获取所有的门禁点信息。返回结果分页展示。
     * 注：若指定多个查询条件，表示将这些查询条件进行”与”的组合后进行精确查询。
     * 根据”门禁点名称doorName”查询为模糊查询。
     * */
    public function queryDoorList($in_data){
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
        isset($in_data['doorIndexCodes']) ? $array['doorIndexCodes'] = $in_data['doorIndexCodes']:'';
        isset($in_data['doorName']) ? $array['doorName'] = $in_data['doorName']:'';
        isset($in_data['acsDevIndexCode']) ? $array['acsDevIndexCode'] = $in_data['acsDevIndexCode']:'';
        isset($in_data['regionIndexCode']) ? $array['regionIndexCode'] = $in_data['regionIndexCode']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询门禁读卡器列表
     * 查询目录下有权限的门禁读卡器列表。
     * */
    public function queryReaderCardList($in_data){
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
        isset($in_data['name']) ? $array['name'] = $in_data['name']:'';
        isset($in_data['regionIndexCodes']) ? $array['regionIndexCodes'] = $in_data['regionIndexCodes']:'';
        isset($in_data['isSubRegion']) ? $array['isSubRegion'] = $in_data['isSubRegion']:'';
        isset($in_data['capabilitySet']) ? $array['capabilitySet'] = $in_data['capabilitySet']:'';
        isset($in_data['orderBy']) ? $array['orderBy'] = $in_data['orderBy']:'';
        isset($in_data['orderType']) ? $array['orderType'] = $in_data['orderType']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询门禁点状态
     * 该接口支持门常开、门常闭、门开和门闭四种操作引起的门状态获取。门常开操作，门会一直处于开状态，
     * 不会自动关闭，执行门闭操作，门才会关上；门常闭操作，门会一直处于关毕状态，普通卡刷卡门不会被打开，
     * 执行门开操作，门会打开；门开操作，执行门打开动作，超过门打开时间，门会自动关上；门闭操作，
     * 执行关门动作，会立即把门关上。调用该接口，首先要通过获取门禁点资源列表的接口，
     * 获取到门禁点唯一编号，然后根据门禁点唯一编号进行门禁点状态状态查询。
     * 需要注意的是门通道必须接上门磁才能正常发送门状态变化通知，如果未接门磁，
     * 平台无法通过门状态变更通知来更新门状态。
     * */
    public function queryDoorStates($in_data){
        $array = array();
        if(!isset($in_data['doorIndexCodes'])){
            $this->error[] = '门禁点唯一标识必须';
        }else{
            $array['doorIndexCodes'] = $in_data['doorIndexCodes'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取门禁设备在线状态
     * 根据条件获取门禁设备在线状态接口
     * */
    public function getDoorOnline($in_data){
        $array = array();
        isset($in_data['regionId']) ? $array['regionId'] = $in_data['regionId']:'';
        isset($in_data['ip']) ? $array['ip'] = $in_data['ip']:'';
        isset($in_data['indexCodes']) ? $array['indexCodes'] = $in_data['indexCodes']:'';
        isset($in_data['status']) ? $array['status'] = $in_data['status']:'';
        isset($in_data['pageNo']) ? $array['pageNo'] = $in_data['pageNo']:'';
        isset($in_data['pageSize']) ? $array['pageSize'] = $in_data['pageSize']:'';
        isset($in_data['includeSubNode']) ? $array['includeSubNode'] = $in_data['includeSubNode']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取门禁读卡器在线状态
     * 根据条件获取门禁卡器在线状态接口
     * */
    public function getReaderCardOnline($in_data){
        $array = array();
        isset($in_data['regionId']) ? $array['regionId'] = $in_data['regionId']:'';
        isset($in_data['ip']) ? $array['ip'] = $in_data['ip']:'';
        isset($in_data['indexCodes']) ? $array['indexCodes'] = $in_data['indexCodes']:'';
        isset($in_data['status']) ? $array['status'] = $in_data['status']:'';
        isset($in_data['pageNo']) ? $array['pageNo'] = $in_data['pageNo']:'';
        isset($in_data['pageSize']) ? $array['pageSize'] = $in_data['pageSize']:'';
        isset($in_data['includeSubNode']) ? $array['includeSubNode'] = $in_data['includeSubNode']:'';
        $this->request_data = $array;
        return true;
    }


}