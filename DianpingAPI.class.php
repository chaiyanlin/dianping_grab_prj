<?php
include_once('./utils.class.php');
include_once('./Business.class.php');


//需要PHP 5 以上以及安装curl扩展

//AppKey 信息，请替换
define('APPKEY', 40230190);
//AppSecret 信息，请替换
define('SECRET','b0667554255a40ebab8a4e368e773e5b');
#define('APPKEY', 24793981);
#define('SECRET','72829a0567894a5295d4665c28eb8531');


//API 请求地址
define('BUSINESS_REQUEST_URL','http://api.dianping.com/v1/business/get_single_business');
define('CITYLIST_REQUEST_URL','http://api.dianping.com/v1/metadata/get_cities_with_businesses');



abstract class DianpingAPI {
    protected $_APPKEY;
    protected $_SECRET;
    
    public function __construct ( $appkey = APPKEY, $secret = SECRET ) {
        $this->_APPKEY = $appkey;
        $this->_SECRET = $secret;
    }
    
    abstract protected function create_request_url();

    public function request () {
        $url = $this->create_request_url();
        
        $curl = curl_init();

        // 设置你要访问的URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');

        // 运行cURL，请求API
        $data = json_decode(curl_exec($curl), true);
        //print_r($data);

        // 关闭URL请求
        curl_close($curl);
        
        trace("API return:");
        print_r($data);
        return $data;
    }
}


class DianpingAPI_Business extends DianpingAPI {
    protected $_BUSINESS_ID;
    
    public function __construct () {
        parent::__construct();
    }
    
    public function set_BusinessID ( $id ) {
        $this->_BUSINESS_ID = $id;
        trace("Set Business ID as ".$this->_BUSINESS_ID);
    }
 
    /**
     *产生API请求链接
     *@return String
     */
    protected function create_request_url () {
        //请求参数
        $params = array('business_id'=>$this->_BUSINESS_ID);
        //按照参数名排序
        ksort($params);
        trace("Your request based on:");
        trace($params);
        //连接待加密的字符串
        $codes = APPKEY;
        //请求的URL参数
        $queryString = '';
        while (list($key, $val) = each($params))
        {
            $codes .=($key.$val);
            $queryString .=('&'.$key.'='.urlencode($val));
        }
        $codes .=SECRET;
        $sign = strtoupper(sha1($codes));
        $url= BUSINESS_REQUEST_URL . '?appkey='.APPKEY.'&sign='.$sign.$queryString;
        
        trace("Genereate request url: $url");
        return $url;
    }
}


class DianpingAPI_City extends DianpingAPI {
    
    public function __construct () {
        parent::__construct();
    }
    
    
    protected function create_request_url () {
        //请求参数
        //$params = array('city'=>$this->_CITY);
        $params = array();
        //按照参数名排序
        ksort($params);
        trace("Your request based on:");
        trace($params);
        //连接待加密的字符串
        $codes = APPKEY;
        //请求的URL参数
        $queryString = '';
        while (list($key, $val) = each($params))
        {
            $codes .=($key.$val);
            $queryString .=('&'.$key.'='.urlencode($val));
        }
        $codes .=SECRET;
        $sign = strtoupper(sha1($codes));
        $url= CITYLIST_REQUEST_URL . '?appkey='.APPKEY.'&sign='.$sign.$queryString;
        
        trace("Genereate request url: $url");
        return $url;
    }
}

?>
