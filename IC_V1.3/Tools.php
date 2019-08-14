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
        $response = $client->request('POST', $this->config['api_root'].$request_obj->use_uri, [
            'verify'=>false,
            'body' => json_encode($request_obj->request_data),
            'headers' => [
                'Accept' => $this->header['Accept'],
                'Content-Type' => $this->header['ContentType'],
                'X-Ca-Key' => $this->config['appKey'],
                'X-Ca-Signature' => $X_Ca_Signature,
                'X-Ca-Signature-Headers' => $X_Ca_Signature_Headers
            ]
        ]);
        $this->log('$request_obj->request_data');
        $this->log($request_obj->request_data);
        $callback = json_decode($response->getBody()->getContents(),true);
        $this->log('$callback');
        $this->log($callback);
        if($response->getStatusCode() != 200){
            return array('succ'=>false,'msg'=>'请求失败!!!');
        }
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

    public function log($log){
        if(is_array($log)){
            $log = json_encode($log,256);
        }
        if(!is_dir($this->config['log_path'].'cache/haikangv1.3/')){
            mkpath($this->config['log_path'].'cache/haikangv1.3/');
        }
        file_put_contents($this->config['log_path'] . 'cache/haikangv1.3/'.date('Ymd').'log.txt',$log.PHP_EOL,FILE_APPEND);
    }

}