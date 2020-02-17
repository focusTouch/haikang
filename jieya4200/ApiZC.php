<?php

namespace JieYa4200;

class ApiZC
{
    static $api_map = array(
        'person' => '\JieYa4200\Api\Person',
    );

    //发送数据
    public function go($xls_path, $log_path)
    {
        $log_path = end(explode('\\', $log_path));
        //读取csv数据生成器
        try {
            $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($xls_path); //自动获取文件的类型
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType); //获取文件读取操作对象
            $spreadSheet = $objReader->load($xls_path); //加载文件
        } catch (\Exception $e) {
            custom_log('excel读取异常' . $e->getMessage(), $config = array('path' => $log_path, 'name' => 'run', 'log' => true, 'echo' => true));
            die();
        }
        /**读取excel文件中的第一个工作表*/
        $currentSheet = $spreadSheet->getSheet(0);
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();
        /**取得最大的列号*/
        $allColumn ='S';
        $all_row_arr = array();
        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        for ($rowIndex = 1; $rowIndex <= $allRow; $rowIndex++) {
            $row_arr = array();
            //前12行是注释行
            if ($rowIndex < 12) continue;
            for ($colIndex = 'A'; $colIndex <= $allColumn; $colIndex++) {
                $addr = $colIndex . $rowIndex;
                $value = $currentSheet->getCell($addr)->getValue();
                if($colIndex == 'M'){
                    $value = date("Y-m-d", \PHPExcel_Shared_Date::ExcelToPHP($value));
                }
                $row_arr[] = $value;
            }
            $all_row_arr[$rowIndex] = $row_arr;
        }
        $this->go1($all_row_arr, $log_path, dirname($xls_path));
    }


    public function go1($row_list, $log_path, $face_dir)
    {
        $name_arr = array_column($row_list, 2);
        $same_name_arr = array_diff_assoc($name_arr, array_unique($name_arr));
        //循环读取
        foreach ($row_list as $rowIndex => $row_arr) {
            custom_log('原始职工数据：' . json_encode($row_arr, 256), $config = array('path' => $log_path, 'name' => 'run', 'log' => true, 'echo' => true));
            if (in_array($row_arr[2], $same_name_arr)) {
                custom_log('存在同名人错误,请手动备案-行数：' . $rowIndex . '|' . $row_arr[2], $config = array('path' => $log_path, 'name' => '同名人名单', 'log' => true, 'echo' => true));
                continue;
            }
            $people_data = array();
            $people_data['name'] = $row_arr[2];
            $people_data['card_num'] = $row_arr[13];
            switch ($row_arr[4]) {
                case 1:
                    $people_data['cardtype'] = 'SFZ';
                    break;
                default:
                    custom_log('证件类型错误-行数：' . $rowIndex, $config = array('path' => $log_path, 'name' => '证件类型错误', 'log' => true, 'echo' => true));
                    continue;
            }
            $people_data['cardno'] = trim((string)$row_arr[5], '\'');
            if (!$people_data['cardno']) {
                custom_log('证件号码为空错误：' . $rowIndex . '-' . $row_arr[2], $config = array('path' => $log_path, 'name' => '证件号码为空', 'log' => true, 'echo' => true));
                continue;
            }

            if (in_array($row_arr[3], [1, 2])) {
                $people_data['sex'] = $row_arr[3];
            } else {
                $people_data['sex'] = 0;
            }
            $dept = explode('/', $row_arr[1]);
            $firm =  explode('/', $row_arr[16]);
            switch ($firm[0]) {
                case '第三方企业':
                    custom_log('第三方公司人员：' . $rowIndex . '-' . $row_arr[2], $config = array('path' => $log_path, 'name' => 'run', 'log' => true, 'echo' => true));
                    $people_data['people_type'] = 2;
                    $people_data['firm_name'] = $this->getFirmIdHY($firm[1]);
                    $people_data['job_name'] = end($dept).'/'.$row_arr[8];
                    if (!$people_data['firm_name']) {
                        custom_log('第三方公司id获取错误：' . $rowIndex . '-' . $row_arr[2], $config = array('path' => $log_path, 'name' => '第三方公司失败', 'log' => true, 'echo' => true));
                        continue;
                    }
                    break;
                case '临时':
                    custom_log('临时人员不备案：' . $rowIndex . '-' . $row_arr[2], $config = array('path' => $log_path, 'name' => '临时人员', 'log' => true, 'echo' => true));
                    continue;
                    break;
                default:
                    $people_data['job_name'] = end($dept).'/'.$row_arr[8];
                    $people_data['people_type'] = 1;
            }
            
            //获取人脸数据
            $file_path = $face_dir . DIRECTORY_SEPARATOR . '人脸' . DIRECTORY_SEPARATOR . $people_data['name'] . '.jpg';
            if (file_exists($file_path)) {
                $people_data['facephoto'] = base64_encode(file_get_contents($file_path));
            } else {
                custom_log('无人脸人员' . $rowIndex . '-' . $row_arr[2], $config = array('path' => $log_path, 'name' => '无人脸人员,请到后台补充', 'log' => true, 'echo' => true));
            }
            
            $people_data['phone'] = $row_arr[7];
            $people_data['comm_countrys'] = undic('comm_countrys',$row_arr[9],'id','name');
            $people_data['nation'] = undic('comm_nation',$row_arr[14],'id','name');
            
            if($row_arr[17] == '是'){
                $people_data['contract_has'] = 1;
                $people_data['contract_timeout'] = $row_arr[12];
            }

            //开发接口不审核必填项
            $people_data['data_check'] = true;

            file_put_contents(rootpath . 'cache' . DIRECTORY_SEPARATOR . $log_path . DIRECTORY_SEPARATOR . 'data_json.log', json_encode($people_data, 256) . PHP_EOL, FILE_APPEND);
            unset($people_data['facephoto']);
            custom_log('处理后职工数据：' . json_encode($people_data, 256), $config = array('path' => $log_path, 'name' => 'run', 'log' => true, 'echo' => true));
        }
        custom_log('处理完毕！！！', $config = array('path' => $log_path, 'name' => 'run', 'log' => true, 'echo' => true));
    }


    //动作执行
    public function exec()
    {
        static $i = 0;
        $log_path = '福建省马尾造船厂股份有限公司（旧）';
        $api = '\JieYa4200\Api\Person';
        $obj = new $api();
        $file_path = rootpath . 'cache' . DIRECTORY_SEPARATOR . $log_path . DIRECTORY_SEPARATOR . 'data_json.log';
        $json_obj = $this->readFile($file_path);
        foreach($json_obj as $json){
            $i++;
            if(!$json){
                custom_log('没有数据了:' . $json, $config = array('path' => $log_path, 'name' => '没有数据了', 'log' => true, 'echo' => true));
                continue;
            }
            $people_data = json_decode($json,true);
            $res = $obj->add($people_data);
            if($res['succ']){
                custom_log('备案成功:' . $people_data['name'].'|'.json_encode($res,256), $config = array('path' => $log_path, 'name' => '备案成功', 'log' => true, 'echo' => true));
            }elseif(explode('_',$res['msg'])[0] == '该证件人员已存在'){
                $people_data['id'] = explode('_',$res['msg'])[1];
                $people_data['status'] = 0;
                $res = $obj->edit($people_data);
                if($res['succ']){
                    custom_log('编辑备案成功:' . $people_data['name'].'|'.json_encode($res,256), $config = array('path' => $log_path, 'name' => '编辑备案成功', 'log' => true, 'echo' => true));
                }else{
                    custom_log('编辑备案失败:' . $people_data['name'].'|'.json_encode($res,256), $config = array('path' => $log_path, 'name' => '编辑备案失败', 'log' => true, 'echo' => true));
                }
            }else{
                custom_log('备案失败:' . $people_data['name'].'|'.json_encode($res,256), $config = array('path' => $log_path, 'name' => '备案失败', 'log' => true, 'echo' => true));
            }
            usleep(500);
        }
        custom_log('没有数据了,次数:' . $i, $config = array('path' => $log_path, 'name' => '没有数据了', 'log' => true, 'echo' => true));
    }
    
    private function readFile($file_path){
        $fp = fopen($file_path,"r");
        while(!feof($fp)){
            yield fgets($fp,1024000);//逐行读取。如果fgets不写length参数，默认是读取1k。
        }
        fclose($fp);
    }


    public function getFirmIdHY($name)
    {
        $firm_list = array(
            '27' => '福州市马尾区闽旺船舶工程有限公司',
            '24' => '福州福港船舶工程有限公司',
            '28' => '福州金盾保安有限公司',
            '25' => '福州航道处',
            '26' => '福建逸航渔业有限公司'
        );
        if (in_array($name, $firm_list)) {
            return array_search($name, $firm_list);
        }
        return 0;
    }




















    /**
     *获取csv内容 使用 yield
     */
    private function getCsv($fname)
    {
        $handle = fopen("$fname", "r");
        while (feof($handle) === false) {
            yield fgetcsv($handle, 0, ',');
        }
        fclose($handle);
    }
}
