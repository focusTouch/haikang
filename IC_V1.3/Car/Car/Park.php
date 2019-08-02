<?php
namespace HaiKang\Car\Car;

use HaiKang\Base\ApiBase;

class Park extends ApiBase
{
    static $uri_array = array(
        'getParkList'=>'/api/resource/v1/park/parkList',//获取停车库列表
        'queryParkItem'=>'/api/resource/v1/park/search',//查询停车库节点信息
        'queryParkItemDetail'=>'/api/resource/v1/park/detail/get',//获取停车库节点详细信息
        'getEntranceList'=>'/api/resource/v1/entrance/entranceList',//获取出入口列表
        'getRoadwayList'=>'/api/resource/v1/roadway/roadwayList',//获取车道列表
    );

    /*
     * 获取停车库列表
     * 根据停车场唯一标识集合获取停车列表信息。
     * */
    public function getParkList($in_data){
        $array = array();
        isset($in_data['parkIndexCodes']) ? $array['parkIndexCodes'] = $in_data['parkIndexCodes']:'';
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