<?php
namespace HaiKang\Car\Car;

use HaiKang\Base\ApiBase;

class Car extends ApiBase
{
    static $uri_array = array(
        'batchAdd'=>'/api/resource/v1/vehicle/batch/add',//批量添加车辆
        'singleUpdate'=>'/api/resource/v1/vehicle/single/update',//修改车辆
        'batchDelete'=>'/api/resource/v1/vehicle/batch/delete',//批量删除车辆
        'queryVehicleList'=>'/api/resource/v1/vehicle/advance/vehicleList',//查询车辆列表
    );

    /*
     * 批量添加车辆
     * 单个添加车辆信息接口，注意，车辆的必选字段必须都包括在入参中。
     * 若需支持批量添加的后续业务处理，请求需指定每个车辆的clientId，
     * 服务端完成添加后将生成的车辆indexCode与此clientId绑定返回，服务端不对clientId做校验。
     * */
    public function batchAdd($in_data){
        $array = array();
        if(!is_array($in_data) || count($in_data) < 1){
            $this->error[] = '待添加的车辆列表必须';
        }else{
            $array = $in_data;
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 修改车辆
     * 根据车辆编号修改车辆信息。
     * */
    public function singleUpdate($in_data){
        $array = array();
        if(!isset($in_data['vehicleId'])){
            $this->error[] = '车辆ID必须';
        }else{
            $array['vehicleId'] = $in_data['vehicleId'];
        }
        isset($in_data['plateNo']) ? $array['plateNo'] = $in_data['plateNo']:'';
        isset($in_data['personId']) ? $array['personId'] = $in_data['personId']:'';
        isset($in_data['description']) ? $array['description'] = $in_data['description']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 批量删除车辆
     * 根据车辆编码删除车辆。
     * */
    public function batchDelete($in_data){
        $array = array();
        if(!isset($in_data['vehicleIds'])){
            $this->error[] = '待删除的车辆Id列表必须';
        }else{
            $array['vehicleIds'] = $in_data['vehicleIds'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询车辆列表
     * 查询车辆列表接口可以根据车牌号码、车主姓名、车辆类型、车牌类型、是否关联人员、
     * 车辆状态这些查询条件来进行高级查询；若不指定查询条件，即全量获取所有的车辆信息。返回结果分页展示。
     * */
    public function queryVehicleList($in_data){
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
        isset($in_data['personName']) ? $array['personName'] = $in_data['personName']:'';
        isset($in_data['plateNo']) ? $array['plateNo'] = $in_data['plateNo']:'';
        isset($in_data['plateType']) ? $array['plateType'] = $in_data['plateType']:'';
        isset($in_data['isBandPerson']) ? $array['isBandPerson'] = $in_data['isBandPerson']:'';
        isset($in_data['vehicleType']) ? $array['vehicleType'] = $in_data['vehicleType']:'';
        $this->request_data = $array;
        return true;
    }


}