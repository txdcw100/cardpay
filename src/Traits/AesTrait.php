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
    public $password = 'g87y65ki6e8p93av'; //变更password 需要同步使用JAVA生成新的key

    /**
     *
     * @param string $string 需要加密的字符串
     * @param string $password 密码原文 16位字节
     * @return string
     */
    public function encrypt($string)
    {
        $iv = 'f96x74jh7d9q82`w';// 向量
        // 通过 第1步java demo 获取 密钥串 注意：第1步中是进行BASE64编码的，下面使用的时候需要进行BASE64解码
        $key = "Zzg3eTY1a2k2ZThwOTNhdg==";
        $data = openssl_encrypt($string, 'AES-128-CBC', base64_decode($key), OPENSSL_RAW_DATA,$iv);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * @param string $string 需要解密的字符串
     * @param string $password 密码原文 16位字节
     * @return string
     */
    public function decrypt($string)
    {
        $iv = 'f96x74jh7d9q82`w';// 向量
        // 通过 第1步java demo 获取 密钥串 注意：第1步中是进行BASE64编码的，下面使用的时候需要进行BASE64解码
        $key = "Zzg3eTY1a2k2ZThwOTNhdg==";
        $decrypted = openssl_decrypt(base64_decode($string), 'AES-128-CBC', base64_decode($key), OPENSSL_RAW_DATA,$iv);
        return $decrypted;
    }
}
