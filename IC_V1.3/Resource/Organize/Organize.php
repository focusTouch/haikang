<?php
namespace HaiKang\Resource\Organize;

use HaiKang\Base\ApiBase;

class Organize extends ApiBase
{
    static $uri_array = array(
        'getRootOrgList'=>'/api/resource/v1/org/rootOrg',//获取根组织
        'getOrgList'=>'/api/resource/v1/org/orgList',//获取组织列表
        'getAdvanceOrgList'=>'/api/resource/v1/org/advance/orgList',//查询组织列表
        'getSubOrgListByParent'=>'/api/resource/v1/org/parentOrgIndexCode/subOrgList',//根据父组织编号获取下级组织列表
        'orgSingleUpdate'=>'/api/resource/v1/org/single/update',//修改组织
        'orgBatchDelete'=>'/api/resource/v1/org/batch/delete',//批量删除组织
        'orgBatchAdd'=>'/api/resource/v1/org/batch/add',//批量添加组织
        'getSingleOrgInfo'=>'/api/resource/v1/org/orgIndexCode/orgInfo',//获取单个组织信息
    );

    /*
     * 获取根组织
     * 获取根组织接口用来获取组织的根节点。
     * */
    public function getRootOrgList($in_data){
        $array = array();
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取组织列表
     * 根据该接口全量同步组织信息,不作权限过滤，返回结果分页展示。
     * */
    public function getOrgList($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '当前页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '每页记录总数必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询组织列表
     * 根据不同的组织属性分页查询组织信息。
     * 查询组织列表接口可以根据组织唯一标识集、组织名称、组织状态这些查询条件来进行高级查询；若不指定查询条件，即全量获取所有的组织信息。返回结果分页展示。
     * 注：若指定多个查询条件，表示将这些查询条件进行”与”的组合后进行精确查询。
     * 根据”组织名称orgName”查询为模糊查询。
     * 根据该接口全量同步组织信息,不作权限过滤，返回结果分页展示。
     * */
    public function getAdvanceOrgList($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '当前页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '每页记录总数必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        isset($in_data['orgName']) ? $array['orgName'] = $in_data['orgName']:'';
        isset($in_data['orgIndexCodes']) ? $array['orgIndexCodes'] = $in_data['orgIndexCodes']:'';

        $this->request_data = $array;
        return true;
    }

    /*
     * 修改组织
     * 根据组织编号修改组织信息。其它扩展属性按照定义以 key：value 上传即可，根据获取资源属性接口查询平台已配置的资源属性
     * */
    public function orgSingleUpdate($in_data){
        $array = array();
        if(!isset($in_data['orgIndexCode'])){
            $this->error[] = '组织标识必须';
        }else{
            $array['orgIndexCode'] = $in_data['orgIndexCode'];
        }
        isset($in_data['orgName']) ? $array['orgName'] = $in_data['orgName']:'';
        isset($in_data['parentIndexCode']) ? $array['parentIndexCode'] = $in_data['parentIndexCode']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 批量删除组织
     * 仅支持删除无子结点且组织下不存在人员的组织。
     * */
    public function orgBatchDelete($in_data){
        $array = array();
        if(!isset($in_data['indexCodes'])){
            $this->error[] = '组织标识必须';
        }elseif(!is_array($in_data['indexCodes'])){
            $this->error[] = '组织标识格式错误';
        }else{
            $array['orgIndexCode'] = $in_data['orgIndexCode'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 批量添加组织
     * 支持三方指定组织唯一标识， 也支持ISC独立生成组织唯一标识。其它扩展属性按照定义以 key：value 上传即可，根据获取资源属性接口查询平台已配置的资源属性。
     * */
    public function orgBatchAdd($in_data){
        $array = array();
        if(!is_array($in_data)){
            $this->error[] = '组织格式错误';
        }else{
            $array = $in_data;
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据父组织编号获取下级组织列表
     * 根据父组织编号获取下级组织列表，主要用于逐层获取父组织的下级组织信息，返回结果分页展示。
     * */
    public function getSubOrgListByParent($in_data){
        $array = array();
        if(!isset($in_data['parentOrgIndexCode'])){
            $this->error[] = '父组织编号必须';
        }else{
            $array['parentOrgIndexCode'] = $in_data['parentOrgIndexCode'];
        }
        if(!isset($in_data['pageNo'])){
            $this->error[] = '当前页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '每页记录总数必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取单个组织信息
     * 根据组织唯一标识orgIndexCode获取指定的组织信息。
     * */
    public function getSingleOrgInfo($in_data){
        $array = array();
        if(!isset($in_data['orgIndexCode'])){
            $this->error[] = '组织编号必须';
        }else{
            $array['orgIndexCode'] = $in_data['orgIndexCode'];
        }
        $this->request_data = $array;
        return true;
    }



}