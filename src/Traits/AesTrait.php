<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/18
 * Time: 11:50
 */

namespace Chuwei\Cardpay\Traits;


trait AesTrait
{
    private $method='AES-128-CBC'; //128无误
    private $options = OPENSSL_RAW_DATA;
    private $iv = '';
    private $pk = '';

    /**
     * 数据加密方法
     *
     * @param String $data 待加密数据
     * @param String $key 密钥
     * @return String
     */
    public function encrypt($data='', $key='')
    {
        $data    = $data ?: $this->data;
        $key     = $key ?: $this->key;
        $method  = $this->method;
        $options = $this->options;
        $iv      = self::getIvFromKey($key);
        $res = openssl_encrypt($data, $method, $key, $options, $iv);
        return \base64_encode($res);
    }
    /**
     * 数据解密方法
     *
     * @param String $data 待解密数据
     * @param String $key 密钥
     * @return String
     */
    public function decrypt($data='', $key='')
    {
        $data    = $data ?: $this->data;
        $key     = $key ?: $this->key;
        $method  = $this->method;
        $options = $this->options;
        $iv      = self::getIvFromKey($key);
        $data    = base64_decode($data);
        $res = openssl_decrypt($data, $method, $key, $options, $iv);
        return $res;
    }

    /**
     * 按规定方式从密钥生成向量
     *
     * @param String $key 密钥
     * @return String
     */
    private static function getIvFromKey($key)
    {
        $key_byte = self::getBytes($key);
        $iv_byte = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
        $tmp = [];
        foreach($iv_byte as $k=>$v){
            if(isset($key_byte[$k])){
                $tmp[] = $key_byte[$k] ^ $v;
            }else{
                $tmp[] = $v;
            }
        }
        $iv_str = self::toStr($tmp);
        return $iv_str;
    }

    /**

     * 转换一个String字符串为byte数组
     * @param String $string 需要转换的字符串
     * @return Array $bytes 目标byte数组
     */
    public static function getBytes($string) {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i++) {
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }
    /**

     * 将字节数组转化为String类型的数据
     * @param Array $bytes 字节数组
     * @return String  $str 目标字符串
     */
    public static function toStr($bytes) {
        $str = '';
        foreach ($bytes as $ch) {
            $str.= chr($ch);

        }
        return $str;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
    public function setOptions($op)
    {
        $this->options = $op;
        return $this;
    }
    public function setIv($iv)
    {
        $this->iv = $iv;
        return $this;
    }
    public function setPk($pk)
    {
        $this->pk = $pk;
        return $this;
    }
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
