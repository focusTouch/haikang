<?php
namespace HaiKang;

use GuzzleHttp\Client;

class Tools
{
    private $config;

    public $header = array(
        'Accept'=>'*/*',
        'ContentType'=>'application/json',
    );

    public function __construct($config=array()) {
        $this->config = $config;
    }

    public function http_post($request_obj){
        $client = new Client(['base_uri' => $this->config['host']]);
        $X_Ca_Signature = $this->get_sign($request_obj->request_data,$this->config['api_root'].$request_obj->use_uri);;
        $X_Ca_Signature_Headers = strtolower('X-Ca-Key');
        //日志分类
        if(in_array($request_obj->use_uri,['/api/acs/v1/door/events','/api/acs/v1/event/pictures'])){
            $log_name = 'event';
        }elseif(in_array($request_obj->use_uri,['/api/resource/v2/regions/subRegions','/api/resource/v1/regions/root','/api/resource/v1/org/parentOrgIndexCode/subOrgList'])){
            $log_name = 'resource';
        }else{
            $log_name = false;
        }
        
        
        $this->log('urlisc:',$log_name);
        $this->log($this->config['host'],$log_name);
        $this->log($this->config['api_root'].$request_obj->use_uri,$log_name);
        $this->log('$request_obj->request_data',$log_name);
        $response = $client->request('POST', $this->config['api_root'].$request_obj->use_uri, [
            'allow_redirects'=>false,
            'verify'=>false,
            'body' => $request_obj->request_data?json_encode($request_obj->request_data):'{}',
            'headers' => [
                'Accept' => $this->header['Accept'],
                'Content-Type' => $this->header['ContentType'],
                'X-Ca-Key' => $this->config['appKey'],
                'X-Ca-Signature' => $X_Ca_Signature,
                'X-Ca-Signature-Headers' => $X_Ca_Signature_Headers
            ]
        ]);

        //下发人脸时不记录图片数据
        if(array_key_exists('faces', $request_obj->request_data) || array_key_exists('faceData', $request_obj->request_data)){
            $log_arr = $request_obj->request_data;
            $this->log($log_arr['faces'][0]['faceData'],$log_name);
            $log_arr['faces'] = '人脸照片';
            $log_arr['faceData'] = '人脸照片';
            $this->log($log_arr,$log_name);
        }else{
            $this->log($request_obj->request_data,$log_name);
        }
        //获取事件图片时
        if('/api/acs/v1/event/pictures' == $request_obj->use_uri){
            if(in_array($response->getStatusCode(),['301','302'])){
                $location = $response->getHeaderLine('Location');
//                $location = str_replace('10.10.10.1','192.168.1.181',$location);
                $pic_client = new Client();
                $pic_response = $pic_client->request('GET', $location,[
                    'allow_redirects'=>false,
                    'verify'=>false
                    ]);
                if(in_array($pic_response->getStatusCode(),['301','302'])){
                    $location = $pic_response->getHeaderLine('Location');
//                    $location = str_replace('10.10.10.1','192.168.1.181',$location);
                }
                return array('succ'=>true,'msg'=>'成功','data'=>$location);
            }else{
                return array('succ'=>false,'msg'=>'人脸图片为空');
            }
        }

        $callback = json_decode($response->getBody()->getContents(),true);
        if($response->getStatusCode() != 200){
            return array('succ'=>false,'msg'=>'请求失败!!!');
        }
        $this->log('$callback',$log_name);
        $this->log($callback,$log_name);
        if($callback['code']){
            return array('succ'=>false,'msg'=>Error::getMsg($callback['code']));
        }
        return array('succ'=>true,'msg'=>'成功','data'=>$callback['data']);
    }


    /**
     * 以appSecret为密钥，使用HmacSHA256算法对签名字符串生成消息摘要，对消息摘要使用BASE64算法生成签名（签名过程中的编码方式全为UTF-8）
     */
    function get_sign($postData,$url){
        $sign_str = $this->get_sign_str($postData,$url); //签名字符串
        $priKey=$this->config['appSecret'];
        $sign = hash_hmac('sha256', $sign_str, $priKey,true); //生成消息摘要
        $result = base64_encode($sign);
        return $result;
    }

    function get_sign_str($postData,$url){
        $next = "\n";
        $accept = $this->header['Accept'];
        $content_type = $this->header['ContentType'];
        $str = "POST".$next.$accept.$next.$content_type.$next;
        $str .= "x-ca-key:".$this->config['appKey'].$next;
        $str .= $url;
        return $str;
    }

    public function log($log,$log_name=false){
        if(!is_scalar($log)){
            $log = json_encode($log,256);
        }
        if(!is_dir($this->config['log_path'].'cache/haikangv1.3/')){
            mkpath($this->config['log_path'].'cache/haikangv1.3/');
        }
        if($log_name){
            file_put_contents($this->config['log_path'] . 'cache/haikangv1.3/'.date('Ymd').$log_name.'.log',$log.PHP_EOL,FILE_APPEND);
        }else{
            file_put_contents($this->config['log_path'] . 'cache/haikangv1.3/'.date('Ymd').'log.txt',$log.PHP_EOL,FILE_APPEND);
        }
    }

}