<?php
namespace HaiKang\OneCard\OneCard;

use HaiKang\Base\ApiBase;

class OneCard extends ApiBase
{
    static $uri_array = array(
        'authConfigAdd'=>'/api/acps/v1/auth_config/add',//添加权限配置
        'queryAuthConfig'=>'/api/acps/v1/auth_config/search',//查询权限配置
        'deleteAuthConfig'=>'/api/acps/v1/auth_config/delete',//删除权限配置
        'addTaskByConfiguration'=>'/api/acps/v1/download/configuration/task/add',//创建下载任务_根据出入权限配置下载
        'addDataByConfiguration'=>'/api/acps/v1/download/configuration/data/add',//添加待下载的设备通道_根据出入权限配置下载
        'shortcutByConfiguration'=>'/api/acps/v1/authDownload/configuration/shortcut',//根据出入权限配置快捷下载
        'addTaskByAddition'=>'/api/acps/v1/authDownload/task/addition',//创建下载任务_根据人员与设备通道指定下载
        'addDataByAddition'=>'/api/acps/v1/authDownload/data/addition',//下载任务中添加数据_根据人员与设备通道指定下载
        'simpleDownloadTask'=>'/api/acps/v1/authDownload/task/simpleDownload',//简单同步权限下载_根据人员与设备通道指定下载
        'startTask'=>'/api/acps/v1/authDownload/task/start',//开始下载任务
        'progressTask'=>'/api/acps/v1/authDownload/task/progress',//查询下载任务进度
        'listTask'=>'/api/acps/v1/authDownload/task/list',//查询正在下载的任务列表
        'deletionTask'=>'/api/acps/v1/authDownload/task/deletion',//删除未开始的下载任务
        'stopTask'=>'/api/acps/v1/authDownload/task/stop',//终止正在下载的任务
        'queryDownloadRecordList'=>'/api/acps/v1/download_record/channel/list/search',//查询设备通道权限下载记录列表
        'queryDownloadRecordTotal'=>'/api/acps/v1/download_record/channel/total/search',//查询设备通道权限下载记录总数
        'queryPersonDownloadRecord'=>'/api/acps/v1/download_record/person/detail/search',//查询设备通道的人员权限下载详情
        'queryPersonDownloadRecordTotal'=>'/api/acps/v1/download_record/person/total/search',//查询设备通道的人员权限下载详情总数
        'queryAuthItemTotal'=>'/api/acps/v1/auth_item/total/search',//查询权限条目总数
        'queryAuthItemList'=>'/api/acps/v1/auth_item/list/search',//查询权限条目列表
        'querySingleAuthItem'=>'/api/acps/v1/auth_item/single/search',//查询单个权限条目
    );

    /*
     * 查询权限配置
     * 配置人员或组织的物联资源权限， 包含门禁，可视对讲，梯控，人脸消费业务的资源权限，此接口是一个异步接口。
     * */
    public function authConfigAdd($in_data){
        $array = array();
        if(!isset($in_data['personDatas'])){
            $this->error[] = '人员数据列表必须';
        }else{
            $array['personDatas'] = $in_data['personDatas'];
        }
        if(!isset($in_data['resourceInfos'])){
            $this->error[] = '设备通道对象列表必须';
        }else{
            $array['resourceInfos'] = $in_data['resourceInfos'];
        }
        isset($in_data['startTime']) ? $array['startTime'] = $in_data['startTime']:'';
        isset($in_data['endTime']) ? $array['endTime'] = $in_data['endTime']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询权限配置
     * 根据人员数据（组织、人员）、设备通道查询已配置的权限，只能查询OpenAPI添加的权限。
     * */
    public function queryAuthConfig($in_data){
        $array = array();
        if(!isset($in_data['personDataIds'])){
            $this->error[] = '数据编号必须';
        }else{
            $array['personDataIds'] = $in_data['personDataIds'];
        }
        if(!isset($in_data['personDataType'])){
            $this->error[] = '数据类型必须';
        }else{
            $array['personDataType'] = $in_data['personDataType'];
        }
        if(!isset($in_data['resourceInfos'])){
            $this->error[] = '设备通道对象列表必须';
        }else{
            $array['resourceInfos'] = $in_data['resourceInfos'];
        }
        if(!isset($in_data['resourceDataType'])){
            $this->error[] = '资源数据类型，目前仅开放了一种类型：resource必须';
        }else{
            $array['resourceDataType'] = $in_data['resourceDataType'];
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
        $this->request_data = $array;
        return true;
    }

    /*
     * 删除权限配置
     * 根据人员数据、设备通道删除已配置的权限，只能删除组件自身添加的权限。
     * 接口中人员数据、设备通道至少一个不为空。
     * */
    public function deleteAuthConfig($in_data){
        $array = array();
        if(!isset($in_data['personDatas']) || !is_array($in_data['personDatas'])){
            $this->error[] = '人员数据列表必须';
        }else{
            $array['personDatas'] = $in_data['personDatas'];
        }
        if(!isset($in_data['resourceInfos']) || !is_array($in_data['resourceInfos'])){
            $this->error[] = '设备通道对象列表必须';
        }else{
            $array['resourceInfos'] = $in_data['resourceInfos'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 创建下载任务_根据出入权限配置下载
     * 创建下载任务，以异步任务方式根据权限配置异动（对权限未下载、下载失败、变更的数据重新下发）下载出入控制权限。
     * 适用于通过服务管理配置权限的情况，适用综合大楼、学校、医院等批量权限下载的场景
     * 创建下载任务，使得业务组件与出入控制权限服务建立一次异步下载的通道。
     * 任务类型支持组合下载方式，只要有一个类型符合时即可下载权限（即任务类型与设备能力集取交集）。
     * 通过向下载任务中添加数据接口添加待下载的设备通道数据；可分多次调用该接口批量添加下载数据；当不调用添加数据接口，
     * 直接开始下载任务时，自动根据业务组件已配置的权限信息，对未下载成功的权限条目下载。
     * 任务的操作权限由创建的业务组件控制，包含开始下载任务，终止下载任务，删除下载任务。
     * 建议业务组件在设置回调地址接收时，异步处理内部逻辑，避免请求超时（5秒超时）
     * 新创建的下载任务有效期7天，在7天内未操作开始下载的任务将自动清理。
     * tagId用于让多个应用共用出入控制权限服务时，用以区分各自的下载任务。建议使用组件标识。
     * 每个tagId全局默认最多保持100个可操作（未下载结束）任务列表。
     * */
    public function addTaskByConfiguration($in_data){
        $array = array();
        isset($in_data['taskType']) ? $array['taskType'] = $in_data['taskType']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 添加待下载的设备通道_根据出入权限配置下载
     * 选择要下发的设备或点位的所有人权限。
     * 如果不调用此接口，代表下发所有人的所有设备和所有资源点的权限。
     * 该接口支持向新建的下载任务中添加待下载的权限数据，可通过本接口分多次向下载任务中添加数据。
     * 该接口强依赖于资源目录公共存储，请确保设备与人员相关的信息已存在公共存储，否则下载必定失败。
     * 数据添加完成后必须调用 开始下载任务接口 才能下发权限。
     * */
    public function addDataByConfiguration($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '人员数据列表必须';
        }else{
            $array['taskId'] = $in_data['taskId'];
        }
        if(!isset($in_data['resourceInfos']) || !is_array($in_data['resourceInfos'])){
            $this->error[] = '设备通道对象列表必须';
        }else{
            $array['resourceInfos'] = $in_data['resourceInfos'];
        }        $this->request_data = $array;
        return true;
    }

    /*
     * 根据出入权限配置快捷下载
     * 针对少量权限（100设备通道*1000人）的下载，减少调用方对接的复杂度，
     * 减少接口调用次数，该接口集合了创建任务-添加数据-开始下载3合1的功能。
     * */
    public function shortcutByConfiguration($in_data){
        $array = array();
        if(!isset($in_data['taskType'])){
            $this->error[] = '下载任务类型 必须';
        }else{
            $array['taskType'] = $in_data['taskType'];
        }
        if(!isset($in_data['resourceInfos']) || !is_array($in_data['resourceInfos'])){
            $this->error[] = '资源对象必须';
        }else{
            $array['resourceInfos'] = $in_data['resourceInfos'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 创建下载任务_根据人员与设备通道指定下载
     * 创建下载任务，以异步任务方式下载出入控制权限。适用于综合大楼、学校、医院等批量权限下载的场景。
     * 创建下载任务，使得业务组件与出入控制权限服务建立一次异步下载的通道。
     * 通过向下载任务中添加数据接口添加待下载的数据，包含资源、人员信息；可分多次调用该接口批量添加下载数据。
     * 任务的操作权限由创建的业务组件控制，包含开始下载任务，终止下载任务，删除下载任务。
     * 对已经开始的下载任务，可通过查询下载任务进度接口查询任务的总体下载进度和每个资源的下载进度信息。
     * 一个下载任务最大支持100个设备的卡权限下载或者100个通道的人脸。
     * 新创建的下载任务有效期7天，在7天内未操作开始下载的任务将自动清理。
     * */
    public function addTaskByAddition($in_data){
        $array = array();
        if(!isset($in_data['taskType'])){
            $this->error[] = '下载任务类型 必须';
        }else{
            $array['taskType'] = $in_data['taskType'];
        }
        if(!isset($in_data['resourceInfos']) || !is_array($in_data['resourceInfos'])){
            $this->error[] = '资源对象必须';
        }else{
            $array['resourceInfos'] = $in_data['resourceInfos'];
        }
        if(!isset($in_data['personInfos']) || !is_array($in_data['personInfos'])){
            $this->error[] = '人员信息必须';
        }else{
            $array['personInfos'] = $in_data['personInfos'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 下载任务中添加数据_根据人员与设备通道指定下载
     * 该接口支持向新建的下载任务中添加待下载的权限数据，可通过本接口分多次向下载任务中添加数据。
     * 单次接口最多支持100个设备资源和1000个人员，可分多次添加，多次添加的数据会合并处理。
     * 同一个资源相同的人员重复添加时，以最后一次为准。
     * 该接口强依赖于资源目录公共存储，请确保设备与人员相关的信息已存在公共存储，否则下载必定失败。
     * */
    public function addDataByAddition($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '下载任务唯一标识必须';
        }else{
            $array['taskId'] = $in_data['taskId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 简单同步权限下载_根据人员与设备通道指定下载
     * 简单权限下载主要用途对单个指定设备通道，下载少量简单的需即时生效的权限。通过同步下载方式下载权限，适合公安出租屋等场景。
     * 使用该接口时无需创建下载任务，权限下载记录同步返回，接口超时时间30秒。
     * 权限类型为人脸时，设备通道对象中的通道号有且只有一个。
     * */
    public function simpleDownloadTask($in_data){
        $array = array();
        if(!isset($in_data['resourceInfo'])){
            $this->error[] = '资源设备对象信息必须';
        }else{
            $array['resourceInfo'] = $in_data['resourceInfo'];
        }
        if(!isset($in_data['personInfo'])){
            $this->error[] = '人员信息必须';
        }else{
            $array['personInfo'] = $in_data['personInfo'];
        }
        if(!isset($in_data['taskType'])){
            $this->error[] = '权限类型必须';
        }else{
            $array['taskType'] = $in_data['taskType'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 开始下载任务
     * 该接口用于开始一次下载任务，只能由创建任务的组件触发。权限下载完成后会自动结束下载任务。
     * */
    public function startTask($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '下载任务唯一标识';
        }else{
            $array['taskId'] = $in_data['taskId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询下载任务进度
     * 根据任务ID查询任务的下载进度，只能查询由组件创建的任务，进度信息包含任务总体下载进度及各个资源的下载进度。
     * 建议该接口调用频率每3-5秒查询一次任务的下载进度。
     * */
    public function progressTask($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '下载任务唯一标识';
        }else{
            $array['taskId'] = $in_data['taskId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询正在下载的任务列表
     * 该接口用于查询正在下载的任务编号列表，只能查询由组件创建的任务.
     * */
    public function listTask($in_data){
        $array = array();
        $this->request_data = $array;
        return true;
    }

    /*
     * 删除未开始的下载任务
     * 该接口用于删除创建的下载任务，已经开始下载的任务不能删除，只能由创建任务的组件触发。
     * */
    public function deletionTask($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '下载任务唯一标识';
        }else{
            $array['taskId'] = $in_data['taskId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 终止正在下载的任务
     * 该接口用于终止正在下载的任务，未开始下载的任务不能停止，只能由创建任务的组件触发。
     * 当全部资源已终止下载后会自动结束下载任已经终止下载的任务，将会被清除出任务列表，无法被再次开启。
     * 终止下载任务时丢弃还未下载的数据，对已下载的数据记录下载记录和日志。
     * */
    public function stopTask($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '下载任务唯一标识';
        }else{
            $array['taskId'] = $in_data['taskId'];
        }
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询设备通道权限下载记录列表
     * 根据查询条件分页查询设备通道的下载记录，只能查询由业务组件自身创建的任务下载记录。
     * 下载记录主要展示此次下载的概览信息，可通过查询设备通道的下载记录详情接口查询每个设备通道中人员的下载详情。
     * 支持通过任务编号查询单个任务的下载记录。
     * 支持通过设备通道查询设备通道的历史下载记录（卡权限下载记录只能通过设备查询，人脸下载可通过设备和通道查询）。
     * 支持通过任务编号、设备通道对象、下载时间、下载类型查询历史下载记录。
     * 该接口仅返回分页的列表数据，不返回总数。
     * */
    public function queryDownloadRecordList($in_data){
        $array = array();
        if(!isset($in_data['taskId'])){
            $this->error[] = '下载任务唯一标识';
        }else{
            $array['taskId'] = $in_data['taskId'];
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
        isset($in_data['resourceInfos']) ? $array['resourceInfos'] = $in_data['resourceInfos']:'';
        isset($in_data['taskTypes']) ? $array['taskTypes'] = $in_data['taskTypes']:'';
        isset($in_data['downloadResult']) ? $array['downloadResult'] = $in_data['downloadResult']:'';
        isset($in_data['downloadStartTime']) ? $array['downloadStartTime'] = $in_data['downloadStartTime']:'';
        isset($in_data['downloadEndTime']) ? $array['downloadEndTime'] = $in_data['downloadEndTime']:'';
        isset($in_data['sortObjects']) ? $array['sortObjects'] = $in_data['sortObjects']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询设备通道权限下载记录总数
     * 根据查询条件查询设备通道的下载记录总数。
     * */
    public function queryDownloadRecordTotal($in_data){
        $array = array();
        isset($in_data['taskId']) ? $array['taskId'] = $in_data['taskId']:'';
        isset($in_data['resourceInfos']) ? $array['resourceInfos'] = $in_data['resourceInfos']:'';
        isset($in_data['taskTypes']) ? $array['taskTypes'] = $in_data['taskTypes']:'';
        isset($in_data['downloadResult']) ? $array['downloadResult'] = $in_data['downloadResult']:'';
        isset($in_data['downloadStartTime']) ? $array['downloadStartTime'] = $in_data['downloadStartTime']:'';
        isset($in_data['downloadEndTime']) ? $array['downloadEndTime'] = $in_data['downloadEndTime']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询设备通道的人员权限下载详情
     * 根据查询条件查询设备通道的人员下载详情。
     * 该接口用于查询单个下载任务某一设备通道的下载详情信息。
     * 接口仅返回分页的列表数据，不返回总数。
     * */
    public function queryPersonDownloadRecord($in_data){
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
        isset($in_data['downloadResultId']) ? $array['downloadResultId'] = $in_data['downloadResultId']:'';
        isset($in_data['taskId']) ? $array['taskId'] = $in_data['taskId']:'';
        isset($in_data['resourceInfos']) ? $array['resourceInfos'] = $in_data['resourceInfos']:'';
        isset($in_data['personIds']) ? $array['personIds'] = $in_data['personIds']:'';
        isset($in_data['orgId']) ? $array['orgId'] = $in_data['orgId']:'';
        isset($in_data['downloadResult']) ? $array['downloadResult'] = $in_data['downloadResult']:'';
        isset($in_data['downloadStartTime']) ? $array['downloadStartTime'] = $in_data['downloadStartTime']:'';
        isset($in_data['downloadEndTime']) ? $array['downloadEndTime'] = $in_data['downloadEndTime']:'';
        isset($in_data['sortObjects']) ? $array['sortObjects'] = $in_data['sortObjects']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询设备通道的人员权限下载详情总数
     * 根据查询条件查询设备通道的人员下载详情总数。
     * */
    public function queryPersonDownloadRecordTotal($in_data){
        $array = array();
        isset($in_data['downloadResultId']) ? $array['downloadResultId'] = $in_data['downloadResultId']:'';
        isset($in_data['taskId']) ? $array['taskId'] = $in_data['taskId']:'';
        isset($in_data['resourceInfos']) ? $array['resourceInfos'] = $in_data['resourceInfos']:'';
        isset($in_data['personIds']) ? $array['personIds'] = $in_data['personIds']:'';
        isset($in_data['orgId']) ? $array['orgId'] = $in_data['orgId']:'';
        isset($in_data['downloadResult']) ? $array['downloadResult'] = $in_data['downloadResult']:'';
        isset($in_data['downloadTime']) ? $array['downloadTime'] = $in_data['downloadTime']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询权限条目总数
     * 根据人员或设备通道查询权限条目总数。
     * */
    public function queryAuthItemTotal($in_data){
        $array = array();
        isset($in_data['personIds']) ? $array['personIds'] = $in_data['personIds']:'';
        isset($in_data['orgIds']) ? $array['orgIds'] = $in_data['orgIds']:'';
        isset($in_data['resourceInfos']) ? $array['resourceInfos'] = $in_data['resourceInfos']:'';
        isset($in_data['queryType']) ? $array['queryType'] = $in_data['queryType']:'';
        isset($in_data['personStatus']) ? $array['personStatus'] = $in_data['personStatus']:'';
        isset($in_data['cardStatus']) ? $array['cardStatus'] = $in_data['cardStatus']:'';
        isset($in_data['faceStatus']) ? $array['faceStatus'] = $in_data['faceStatus']:'';
        isset($in_data['configTime']) ? $array['configTime'] = $in_data['configTime']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询权限条目列表
     * 根据人员或设备通道分页查询权限条目信息，即人员对应的设备通道的权限配置和下载状态。
     * 适用于查询人员配置了哪些通道权限或者通道配置哪些人员权限场景。。
     * */
    public function queryAuthItemList($in_data){
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
        isset($in_data['personIds']) ? $array['personIds'] = $in_data['personIds']:'';
        isset($in_data['orgIds']) ? $array['orgIds'] = $in_data['orgIds']:'';
        isset($in_data['resourceInfos']) ? $array['resourceInfos'] = $in_data['resourceInfos']:'';
        isset($in_data['queryType']) ? $array['queryType'] = $in_data['queryType']:'';
        isset($in_data['personStatus']) ? $array['personStatus'] = $in_data['personStatus']:'';
        isset($in_data['cardStatus']) ? $array['cardStatus'] = $in_data['cardStatus']:'';
        isset($in_data['faceStatus']) ? $array['faceStatus'] = $in_data['faceStatus']:'';
        isset($in_data['configTime']) ? $array['configTime'] = $in_data['configTime']:'';
        isset($in_data['sortObjects']) ? $array['sortObjects'] = $in_data['sortObjects']:'';
        $this->request_data = $array;
        return true;
    }

    /*
     * 查询单个权限条目
     * 根据人员和设备通道查询一条权限条目信息，适用于判断指定人员与通道的权限情况，比如中心控制，远程开门等场景。
     * */
    public function querySingleAuthItem($in_data){
        $array = array();
        isset($in_data['personId']) ? $array['personId'] = $in_data['personId']:'';
        isset($in_data['resourceInfo']) ? $array['resourceInfo'] = $in_data['resourceInfo']:'';
        $this->request_data = $array;
        return true;
    }


}