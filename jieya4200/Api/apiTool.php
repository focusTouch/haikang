<?php
namespace JieYa4200\Api;

class ApiTool{
    /**
     * 接口密钥
     * @var string
     */
    private $secret;
    
    public function __construct($secret){
        $this->secret = $secret;
    }
    
    /**
     * 对数组排序
     * @param array $para 排序前的数组
     * return 排序后的数组
     */
    function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    
    
    /**
     * 除去数组中的空值和签名参数
     * @param array $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    function paraFilter($para) {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if($key == "sign" || $key=="imgData" || $key == "jsonData" || $val==="" || is_array($val)){
                continue;
            }
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    
    
    /**
     * 生成签名结果
     * @param array $para_sort 已排序要签名的数组
     * @param string $secret 密钥
     * return 签名结果字符串
     */
    function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        $mysign = md5($prestr.$this->secret);
        
        return $mysign;
    }
    
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param array $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    function createLinkstring($para) {
        $arg  = "";
        foreach ($para as $key=>$val){
            $arg .= $key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = trim($arg,'&');
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }
    
    /**
     * 获取签名
     * @param array $param 待签名数据
     * @param string $secret
     * @return string
     */
    function getSign($param){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($param);
        
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);
        
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
        
        return $mysign;
    }

    /*
    @desc：获取文件真实后缀
    @param   name    文件名
    @return  suffix  文件后缀
    */
    static function getfilesuffix($name) {
        if(is_file($name)){
            $file = fopen($name, "rb");
            $bin = fread($file, 2); // 只读2字节
            fclose($file);
        }elseif(is_string($name)){
            $file = $name;
            $bin = substr($file,0,2); // 只读2字节
        }else{
            return false;
        }
        $info = @unpack("C2chars", $bin);
        $code = intval($info['chars1'] . $info['chars2']);
        $suffix = "unknow";
        if($code == 255216){
            $suffix = "jpg";
        }elseif($code == 7173){
            $suffix = "gif";
        }elseif($code == 13780){
            $suffix = "png";
        }elseif($code == 6677){
            $suffix = "bmp";
        }elseif($code == 7798){
            $suffix = "exe";
        }elseif($code == 7784){
            $suffix = "midi";
        }elseif($code == 8297){
            $suffix = "rar";
        }elseif($code == 7368){
            $suffix = "mp3";
        }elseif($code == 0){
            $suffix = "mp4";
        }elseif($code == 8273){
            $suffix = "wav";
        }
        return $suffix;
    }

}