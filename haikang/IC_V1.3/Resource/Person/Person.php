<?php
namespace HaiKang\Resource\Person;

use HaiKang\Base\ApiBase;

class Person extends ApiBase
{
    static $uri_array = array(
        'getProperties'=>'/api/resource/v1/resource/properties',//获取资源属性
        'singleAdd'=>'/api/resource/v1/person/single/add',//添加人员
        'singleCarAdd'=>'/api/resource/v1/person/single/add',//添加无人脸人员
        'singleUpdate'=>'/api/resource/v1/person/single/update',//修改人员
        'batchAdd'=>'/api/resource/v1/person/batch/add',//批量添加人员
        'batchDelete'=>'/api/resource/v1/person/batch/delete',//批量删除人员
        'faceSingelAdd'=>'/api/resource/v1/face/single/add',//添加人脸
        'faceSingelUpdate'=>'/api/resource/v1/face/single/update',//修改人脸
        'faceSingelDelete'=>'/api/resource/v1/face/single/delete',//删除人脸
        'personListByOrg'=>'/api/resource/v2/person/orgIndexCode/personList',//获取组织下人员列表v2
        'personList'=>'/api/resource/v2/person/personList',//获取人员列表v2
        'personList1'=>'/api/resource/v2/person/advance/personList',//获取人员列表v2 1
        'personInfoByCert'=>'/api/resource/v1/person/certificateNo/personInfo',//根据证件号码获取单个人员信息
        'personInfoById'=>'/api/resource/v1/person/personId/personInfo',//根据人员编号获取单个人员信息
        'personInfoByMoblie'=>'/api/resource/v1/person/phoneNo/personInfo',//根据手机号码获取单个人员信息
        'getPersonPicture'=>'/api/resource/v1/person/picture',//提取人员图片
    );

    /*
     * 获取资源属性
     * */
    public function getProperties($in_data){
        $array = array();
        if(!isset($in_data['resourceType'])){
            $this->error[] = '资源类型必须';
        }else{
            $array['resourceType'] = $in_data['resourceType'];
            $this->request_data = $array;
        }
        return true;
    }

    /*
     * 添加人员
     * */
    public function singleAdd($in_data){
        $array = array();
        isset($in_data['personId']) ? $array['personId'] = $in_data['personId']:'';
        isset($in_data['personName']) ? $array['personName'] = $in_data['personName']:'';
        isset($in_data['gender']) ? $array['gender'] = $in_data['gender']:'';
        if(!isset($in_data['orgIndexCode'])){
            $this->error[] = '所属组织标识必须';
        }else{
            $array['orgIndexCode'] = $in_data['orgIndexCode'];
        }
        isset($in_data['birthday']) ? $array['birthday'] = $in_data['birthday']:'';
        isset($in_data['phoneNo']) ? $array['phoneNo'] = $in_data['phoneNo']:'';
        isset($in_data['email']) ? $array['email'] = $in_data['email']:'';
        isset($in_data['certificateType']) ? $array['certificateType'] = $in_data['certificateType']:'';
        isset($in_data['certificateNo']) ? $array['certificateNo'] = $in_data['certificateNo']:'';
        isset($in_data['jobNo']) ? $array['jobNo'] = $in_data['jobNo']:'';
        if(!isset($in_data['faces'])){
            $this->error[] = '人脸信息必须';
        }elseif(!is_array($in_data['faces'])){
            $this->error[] = '人脸信息格式问题';
        }else{
            $array['faces'] = $in_data['faces'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 添加无人脸人员
     * */
    public function singleCarAdd($in_data){
        $array = array();
        isset($in_data['personId']) ? $array['personId'] = $in_data['personId']:'';
        isset($in_data['personName']) ? $array['personName'] = $in_data['personName']:'';
        isset($in_data['gender']) ? $array['gender'] = $in_data['gender']:'';
        if(!isset($in_data['orgIndexCode'])){
            $this->error[] = '所属组织标识必须';
        }else{
            $array['orgIndexCode'] = $in_data['orgIndexCode'];
        }
        isset($in_data['birthday']) ? $array['birthday'] = $in_data['birthday']:'';
        isset($in_data['phoneNo']) ? $array['phoneNo'] = $in_data['phoneNo']:'';
        isset($in_data['email']) ? $array['email'] = $in_data['email']:'';
        isset($in_data['certificateType']) ? $array['certificateType'] = $in_data['certificateType']:'';
        isset($in_data['certificateNo']) ? $array['certificateNo'] = $in_data['certificateNo']:'';
        isset($in_data['jobNo']) ? $array['jobNo'] = $in_data['jobNo']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 修改人员
     * */
    public function singleUpdate($in_data){
        $array = array();
        if(!isset($in_data['personId'])){
            $this->error[] = '人员id必须';
        }else{
            $array['personId'] = $in_data['personId'];
        }
        isset($in_data['personName']) ? $array['personName'] = $in_data['personName']:'';
        isset($in_data['gender']) ? $array['gender'] = $in_data['gender']:'';
        isset($in_data['birthday']) ? $array['birthday'] = $in_data['birthday']:'';
        isset($in_data['phoneNo']) ? $array['phoneNo'] = $in_data['phoneNo']:'';
        isset($in_data['email']) ? $array['email'] = $in_data['email']:'';
        isset($in_data['certificateType']) ? $array['certificateType'] = $in_data['certificateType']:'';
        isset($in_data['certificateNo']) ? $array['certificateNo'] = $in_data['certificateNo']:'';
        isset($in_data['jobNo']) ? $array['jobNo'] = $in_data['jobNo']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 批量添加人员
     * 添加人员信息接口，注意，在安保基础数据配置的必选字段必须都包括在入参中。
     * 批量添加仅支持人员基础属性。
     * 人员批量添加的时候，可以指定人员personid。如果用户不想指定personId，那么需要指定clientId，请求内的每一条数据的clientid必须唯一， 返回值会将平台生成的personid与clientid做绑定。
     * 若personId和clientId都不指定，则无法准确判断哪部分人员添加成功。
     * 本接口支持人员信息的扩展字段，按照属性定义key:value上传即可， 可通过获取资源属性接口，获取平台已启用的人员属性信息。
     * */
    public function batchAdd($in_data){
        $array = array();
        if(!is_array($in_data)){
            $this->error[] = '格式问题';
        }else{
            $array = $in_data;
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 批量删除人员
     * */
    public function batchDelete($in_data){
        $array = array();
        if(!isset($in_data['personIds'])){
            $this->error[] = '人员id必须';
        }elseif(!is_array($in_data['personIds'])){
            $this->error[] = '人员id格式错误';
        }else{
            $array['personIds'] = $in_data['personIds'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 添加人脸信息
     * */
    public function faceSingelAdd($in_data){
        $array = array();
        if(!isset($in_data['personId'])){
            $this->error[] = '人员id必须';
        }else{
            $array['personId'] = $in_data['personId'];
        }
        if(!isset($in_data['faceData'])){
            $this->error[] = '人脸数据必须';
        }else{
            $array['faceData'] = $in_data['faceData'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 修改人脸信息
     * */
    public function faceSingelUpdate($in_data){
        $array = array();
        if(!isset($in_data['faceId'])){
            $this->error[] = '人脸id必须';
        }else{
            $array['faceId'] = $in_data['faceId'];
        }
        if(!isset($in_data['faceData'])){
            $this->error[] = '人脸数据必须';
        }else{
            $array['faceData'] = $in_data['faceData'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 删除人脸信息
     * */
    public function faceSingelDelete($in_data){
        $array = array();
        if(!isset($in_data['faceId']) && !isset($in_data['personId'])){
            $this->error[] = 'personId与faceId保证其中一项不为空';
        }
        isset($in_data['faceId']) ? $array['faceId'] = $in_data['faceId']:'';
        isset($in_data['personId']) ? $array['personId'] = $in_data['personId']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取组织下人员列表v2
     * */
    public function personListByOrg($in_data){
        $array = array();
        if(!isset($in_data['orgIndexCode'])){
            $this->error[] = '组织唯一标识码必须';
        }else{
            $array['orgIndexCode'] = $in_data['orgIndexCode'];
        }
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '每页记录展示的数目必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取人员列表v2
     * */
    public function personList($in_data){
        $array = array();
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }else{
            $array['pageNo'] = $in_data['pageNo'];
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '每页记录展示的数目必须';
        }else{
            $array['pageSize'] = $in_data['pageSize'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询人员列表v2 1
     * 查询人员列表接口可以根据人员ID集、人员姓名、人员性别、所属组织、证件类型、证件号码、人员状态这些查询条件来进行高级查询；若不指定查询条件，即全量获取所有的人员信息。返回结果分页展示
     * */
    public function personList1($in_data){
        if(!isset($in_data['pageNo'])){
            $this->error[] = '页码必须';
        }
        if(!isset($in_data['pageSize'])){
            $this->error[] = '每页记录展示的数目必须';
        }
        $this->request_data = $in_data;
        return true;
    }

    /*
     * 根据证件号码获取单个人员信息
     * */
    public function personInfoByCert($in_data){
        $array = array();
        if(!isset($in_data['certificateNo'])){
            $this->error[] = '证件号码必须';
        }else{
            $array['certificateNo'] = $in_data['certificateNo'];
        }
        if(!isset($in_data['certificateType'])){
            $this->error[] = '证件类型必须';
        }else{
            $array['certificateType'] = $in_data['certificateType'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据人员编号获取单个人员信息
     * */
    public function personInfoById($in_data){
        $array = array();
        if(!isset($in_data['personId'])){
            $this->error[] = '人员编号必须';
        }else{
            $array['personId'] = $in_data['personId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 根据手机号码获取单个人员信息
     * */
    public function personInfoByMoblie($in_data){
        $array = array();
        if(!isset($in_data['phoneNo'])){
            $this->error[] = '手机号必须';
        }else{
            $array['phoneNo'] = $in_data['phoneNo'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 提取人员图片
     * */
    public function getPersonPicture($in_data){
        $array = array();
        if(!isset($in_data['picUri'])){
            $this->error[] = '图片相对URI必须';
        }else{
            $array['picUri'] = $in_data['picUri'];
        }
        if(!isset($in_data['serverIndexCode'])){
            $this->error[] = '图片服务器唯一标识必须';
        }else{
            $array['serverIndexCode'] = $in_data['serverIndexCode'];
        }
        $this->request_data = $array;
        return true;
    }


}