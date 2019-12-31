<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/13
 * Time: 17:23
 */

namespace Chuwei\Cardpay\Controller;

use Chuwei\Cardpay\Contracts\Builder;
use Chuwei\Cardpay\Models\LakalaLog;
use Chuwei\Cardpay\Util\HttpCurlUtil;

abstract class Controller implements Builder
{
    // 支付通道：微信
    const PAYCHLTYP_WECHAT = 'WECHAT';
    // 支付通道：支付宝
    const PAYCHLTYP_ALIPAY = 'ALIPAY';


    // 交易方式：支付宝-扫码支付
    const TRADE_TYPE_ALIPAY_NATIVE = 'ALINATIVE';
    // 交易方式：支付宝-APP支付
    const TRADE_TYPE_ALIPAY_APP = 'ALIAPP';
    // 交易方式：微信-扫码支付
    const TRADE_TYPE_WECHAT_NATIVE = 'WXNATIVE';
    // 交易方式：微信-JSAPI支付
    const TRADE_TYPE_WECHAT_JSAPI = 'JSAPI';
    // 交易方式：微信-APP支付
    const TRADE_TYPE_WECHAT_APP = 'WECHATAPP';
    // 交易方式：微信-小程序支付
    const TRADE_TYPE_WECHAT_MINIAPP = 'MINIAPP';

    /**
     * @var array 网关
     */
    protected $apiConfig = [
        'release' => 'https://intpay.lakala.com/mrpos/cashier',
    ];

    /**
     * @var array 配置s
     */
    protected $config = [];

    public function __construct()
    {
        $this->setConfig(config('lakala.driver.'.config('lakala.default_driver')));
    }

    /**
     * @param array $config
     * @return $this
     * 配置
     */
    public function setConfig($config = [],$sign = true)
    {
        if(!$config && $sign){
            throw new \Exception('config not found');
        }

        foreach ($config as $k => $v){
            $this->config[$k] = $v;
        };

        return $this;
    }

    /**
     * 获取公用请求参数
     * @return array
     */
    private function getCommonParams()
    {
        $params = [
            'charset' => '00',
            'version' => '1.0',
            'signType' => 'RSA',
            'requestId' => $this->mkRequestId(),
            'merchantId' => $this->config['merchant_id'],
        ];
        return $params;
    }

    /**
     * 发送请求
     * @param array $params
     * @return array
     */
    public function request(array $params)
    {
        // 合并公共请求参数
        $params = array_merge($this->getCommonParams(), $params);
        $params['merchantSign'] = $this->signature($params);
        $url = $this->getApiUrl();
        $responseData = $this->post($url, json_encode($params,JSON_UNESCAPED_UNICODE));
        $response = $this->parseResponse($responseData);
        $this->logs($params,$responseData);
        return $response;
    }

    /**
     * 记录请求日志
     * @param $params
     * @param $response
     */
    public function logs($params,$response){

        LakalaLog::create([
            'merchant_id' => $params['merchantId'] ?? '',
            'request_id' => $params['requestId'] ?? '',
            'type' => $params['service'] ?? '',
            'post' => json_encode($params),
            'result' => json_encode($response),
        ]);
    }

    /**
     * 发送Http请求
     * @param string $url
     * @param $params
     * @return array|bool|false|string|null
     */
    private function post(string $url, $params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json;charset=GBK'));
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return null;
        }
        $data = iconv("gbk", "UTF-8//TRANSLIT", $data);
        $responseData = json_decode($data, true);
        return $responseData;
    }

    /**
     * 解析响应数据
     * @param array $response
     * @return array
     */
    public function parseResponse(array $response)
    {
        if (!in_array($response['returnCode'], ['000000', 'MCG00000'])) {
            throw new \Exception($response['returnMessage']);
        }
        if (!$this->verifySignature($response)) {
            throw new \Exception('签名校验错误');
        }
        return $response;
    }

    /**
     * 签名（私钥）
     * @param array $params
     * @return string
     */
    public function signature(array $params,string $string = null)
    {
        $originStr = $string ?: $this->dealSianParams($params);
        $p12CertPath = $this->config['key_rsa_path'];
        $p12CertPass = $this->config['key_rsa_pass'];
        openssl_pkcs12_read(file_get_contents($p12CertPath), $cert, $p12CertPass);
        $priKey = $cert['pkey'];
        $priKey = openssl_get_privatekey($priKey);
//        $originStr = iconv("UTF-8", "gbk//TRANSLIT", $originStr);
        openssl_sign($originStr, $sign, $priKey, OPENSSL_ALGO_SHA256);
        $sign = strtoupper(bin2hex($sign));
        return $sign;
    }

    /**
     * 加密（共钥）
     * @param array $params
     * @return string
     */
    public function signpublic(string $string)
    {
        $p12CertPath = $this->config['key_rsa_path'];
        $p12CertPass = $this->config['key_rsa_pass'];
        openssl_pkcs12_read(file_get_contents($p12CertPath), $cert, $p12CertPass);
        $pubKey = $cert['cert'];
        $pubKey = openssl_get_publickey($pubKey);
        $string = iconv("UTF-8", "gbk//TRANSLIT", $string);
        openssl_public_encrypt($string, $sign, $pubKey);
        $sign = strtoupper(bin2hex($sign));
        return $sign;
    }


    /**
     * 处理待签名参数
     * @param array $params
     * @return string
     */
    public function dealSianParams(array $params)
    {
        ksort($params);
        $requestSignParams = [];
        foreach ($params as $key => $value) {
            if ($value === '') {
                continue;
            }
            $requestSignParams[] = $key . '=' . $value;
        }
        $originStr = implode('&', $requestSignParams);
        return $originStr;
    }

    /**
     * 验签
     * @param array $params
     * @return int
     */
    public function verifySignature(array $params)
    {
        $serverCert = $params['serverCert'];
        $sign = $params['serverSign'];
        unset($params['serverCert']);
        unset($params['serverSign']);
        // 不同的接口过滤掉不同的参数
        switch ($params['service']) {
            case 'QRCodePaymentCommit':
                unset($params['service']);
                unset($params['orderId']);
                unset($params['payInfo']);
                unset($params['payOrdNo']);
                break;
        }
        $originStr = $this->dealSianParams($params);
        $x509Data =
            "-----BEGIN CERTIFICATE-----\n"
            . wordwrap(base64_encode(hex2bin($serverCert)), 64, "\n", true)
            . "\n-----END CERTIFICATE-----\n";
        $originStr = iconv("UTF-8", "gbk//TRANSLIT", $originStr);
        $pubKey = openssl_pkey_get_public($x509Data);
        $result = openssl_verify($originStr, hex2bin($sign), $pubKey, OPENSSL_ALGO_SHA256);
        return $result;
    }

    /**
     * 获取接口地址
     * @return mixed
     */
    public function getApiUrl()
    {
        $apiUrls = $this->apiConfig;
        return $apiUrls['release'];
    }

    /**
     * 生成请求ID
     * @return bool|string
     */
    private function mkRequestId()
    {
        return substr(md5($this->buildOrder() . $this->getRandChar(10)), 8, 16);
    }

    /**
     * 生成唯一订单号
     * @return string
     */
    public function buildOrder()
    {
        return date('Ymd') . substr(time(), -2) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
    }

    /**
     * 随机获取字符串
     * @param $length
     * @param bool $isStr
     * @return string|null
     */
    private function getRandChar($length, $isStr = true)
    {
        $str = null;
        $strPol = $isStr ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz' : '0123456789';
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }

    /**
     * 客户端IP
     * @param int $type
     * @return mixed
     */
    public function getClientIP($type = 0)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '';
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * @param $func
     * @param $arguments
     * @return mixed
     * @throws \Exception
     * 分发请求
     */
    public function __call($func, $arguments){

        if(!class_exists($application = "\\Chuwei\\Cardpay\\Controller\\".ucfirst($func))){
            throw new \Exception("method {$func} not found");
        }
        return (new $application())->setConfig($arguments ? $arguments[0] : [],false);
    }

}
