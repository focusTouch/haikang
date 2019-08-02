<?php
namespace HaiKang\Resource\Regions;

use HaiKang\Base\ApiBase;

class Regions extends ApiBase
{
    static $uri_array = array(
        'getRootRegions'=>'/api/resource/v1/regions/root',//获取根区域信息
        'getRegionsList'=>'/api/irds/v2/region/nodesByParams',//查询区域列表v2
        'getSubRegions'=>'/api/resource/v2/regions/subRegions',//根据区域编号获取下一级区域列表v2
        'getRegionInfo'=>'/api/resource/v1/region/indexCode/regionInfo',//获取单个区域信息
        'batchAdd'=>'/api/resource/v1/region/batch/add',//批量添加区域
        'singleUpdate'=>'/api/resource/v1/region/single/update',//修改区域

    );


    /*
     * 获取根区域信息
     * */
    public function getRootRegions($in_data){
        $array = array();
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询区域列表v2
     * 根据查询条件查询区域列表信息，主要用于区域信息查询过滤。
     * 相对V1接口，支持级联场景的区域查询
     * */
    public function getRegionsList($in_data){
        $array = array();
        if(!isset($in_data['resourceType'])){
            $this->error[] = '资源类型必须';
        }else{
            $array['resourceType'] = $in_data['resourceType'];
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
        isset($in_data['parentIndexCodes']) ? $array['parentIndexCodes'] = $in_data['parentIndexCodes']:'';
        isset($in_data['isSubRegion']) ? $array['isSubRegion'] = $in_data['isSubRegion']:'';
        isset($in_data['authCodes']) ? $array['authCodes'] = $in_data['authCodes']:'';
        isset($in_data['regionType']) ? $array['regionType'] = $in_data['regionType']:'';
        isset($in_data['regionName']) ? $array['regionName'] = $in_data['regionName']:'';
        isset($in_data['sonOrgIndexCodes']) ? $array['sonOrgIndexCodes'] = $in_data['sonOrgIndexCodes']:'';
        isset($in_data['cascadeFlag']) ? $array['cascadeFlag'] = $in_data['cascadeFlag']:'';
        isset($in_data['orderBy']) ? $array['orderBy'] = $in_data['orderBy']:'';
        isset($in_data['orderType']) ? $array['orderType'] = $in_data['orderType']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据区域编号获取下一级区域列表v2
     * 注：保留接口，暂不开放
     * 根据用户请求的资源类型和资源权限获取父区域的下级区域列表，主要用于逐层获取父区域的下级区域信息，
     * 例如监控点预览业务的区域树的逐层获取。下级区域只包括直接下级子区域。
     * 注：查询区域管理权限（resourceType为region），若父区域的子区域无权限、但是其孙区域有权限时
     * 会返回该无权限的子区域，但是该区域的available标记为false（表示无权限）
     * */
    public function getSubRegions($in_data){
        $array = array();
        if(!isset($in_data['parentIndexCode'])){
            $this->error[] = '父区域编号必须';
        }else{
            $array['parentIndexCode'] = $in_data['parentIndexCode'];
        }
        if(!isset($in_data['resourceType'])){
            $this->error[] = '资源类型必须';
        }else{
            $array['resourceType'] = $in_data['resourceType'];
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
        isset($in_data['authCodes']) ? $array['authCodes'] = $in_data['authCodes']:'';
        isset($in_data['cascadeFlag']) ? $array['cascadeFlag'] = $in_data['cascadeFlag']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取单个区域信息
     * */
    public function getRegionInfo($in_data){
        $array = array();
        if(!isset($in_data['regionIndexCode'])){
            $this->error[] = '区域编号必须';
        }else{
            $array['regionIndexCode'] = $in_data['regionIndexCode'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 批量添加区域
     * */
    public function batchAdd($in_data){
        $array = array();
        if(!is_array($in_data)){
            $this->error[] = '区域数组格式错误';
        }else{
            $array = $in_data;
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 修改区域
     * */
    public function singleUpdate($in_data){
        $array = array();
        if(!isset($in_data['regionIndexCode'])){
            $this->error[] = '区域编号必须';
        }else{
            $array['regionIndexCode'] = $in_data['regionIndexCode'];
        }
        isset($in_data['parentIndexCode']) ? $array['parentIndexCode'] = $in_data['parentIndexCode']:'';
        isset($in_data['regionName']) ? $array['regionName'] = $in_data['regionName']:'';
        isset($in_data['regionCode']) ? $array['regionCode'] = $in_data['regionCode']:'';
        isset($in_data['regionType']) ? $array['regionType'] = $in_data['regionType']:'';
        isset($in_data['description']) ? $array['description'] = $in_data['description']:'';
        $this->request_data = $array;
        return true;
    }


}