<?php
namespace HaiKang\Resource\Card;

use HaiKang\Base\ApiBase;

class Card extends ApiBase
{
    static $uri_array = array(
        'batchBindCard'=>'/api/cis/v1/card/bindings',//批量开卡
        'cardDelete'=>'/api/cis/v1/card/deletion',//卡片退卡
        'getCardList'=>'/api/resource/v1/card/cardList',//获取卡片列表
        'getCardInfo'=>'/api/irds/v1/card/cardInfo',//获取单个卡片信息
        'getCardAdvanceList'=>'/api/irds/v1/card/advance/cardList',//查询卡片列表

    );


    /*
     * 批量开卡
     * 该接口主要是应用于对多个人同时开卡的场景，输入卡片开始有效日期、卡片截止有效日期以及对应的人员、卡片关联列表，
     * 实现对多个人员同时开卡的功能，开卡成功后，可以到相应子系统开启卡片的权限，例如到门禁子系统开启人员门禁权限。
     * */
    public function batchBindCard($in_data){
        $array = array();
        if(!isset($in_data['startDate'])){
            $this->error[] = '开始时间必须';
        }else{
            $array['startDate'] = $in_data['startDate'];
        }
        if(!isset($in_data['endDate'])){
            $this->error[] = '结束时间必须';
        }else{
            $array['endDate'] = $in_data['endDate'];
        }
        if(!isset($in_data['cardList']) || count($in_data['cardList']) < 1){
            $this->error[] = '卡信息数组必须';
        }else{
            $array['cardList'] = $in_data['cardList'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 卡片退卡
     * 该接口主要是应用于对人员下卡片进行退卡，
     * 输入卡号以及所属人员id，实现卡片退卡的功能。退卡成功后，相应子系统的卡片权限清除，
     * 例如所属卡片在门禁子系统的门禁权限全部清除。
     * */
    public function cardDelete($in_data){
        $array = array();
        if(!isset($in_data['cardNumber'])){
            $this->error[] = '卡号必须';
        }else{
            $array['cardNumber'] = $in_data['cardNumber'];
        }
        if(!isset($in_data['personId'])){
            $this->error[] = '人员唯一标示必须';
        }else{
            $array['personId'] = $in_data['personId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 获取卡片列表
     * 获取卡片列表接口可用来全量同步卡片信息，返回结果分页展示，不作权限过滤。
     * */
    public function getCardList($in_data){
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
     * 获取单个卡片信息
     * 获取卡片列表接口可用来全量同步卡片信息，返回结果分页展示，不作权限过滤。
     * 注：卡号为精确查找
     * */
    public function getCardInfo($in_data){
        $array = array();
        isset($in_data['cardNo']) ? $array['cardNo'] = $in_data['cardNo']:'';
        isset($in_data['cardId']) ? $array['cardId'] = $in_data['cardId']:'';

        if(!isset($in_data['cardNo']) && !isset($in_data['cardId'])){
            $this->error[] = '请输入卡号或卡id必须';
        }
        if(isset($in_data['cardNo']) && isset($in_data['cardId'])){
            $this->error[] = '只能卡号或卡id';
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询卡片列表
     * 查询卡片列表接口可以根据卡片号码、人员姓名、卡片状态、人员ID集合等查询条件来进行高级查询；若不指定查询条件，即全量获取所有的卡片信息。返回结果分页展示。
     * 注：若指定多个查询条件，表示将这些查询条件进行“与”的组合后进行查询。
     * “人员名称personName”条件查询为模糊查询。
     * “卡片号码cardNo”条件查询为模糊查询。
     * */
    public function getCardAdvanceList($in_data){
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
        isset($in_data['cardNo']) ? $array['cardNo'] = $in_data['cardNo']:'';
        isset($in_data['useStatus']) ? $array['useStatus'] = $in_data['useStatus']:'';
        isset($in_data['personIds']) ? $array['personIds'] = $in_data['personIds']:'';
        $this->request_data = $array;
        return true;
    }

}