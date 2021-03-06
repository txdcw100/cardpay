<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/12/18
 * Time: 10:54
 */

namespace Chuwei\Cardpay\Traits;

trait MercTrait
{
    public function mercInfo($params){

        $password = substr(md5(time()),-16);

        $administrator = [
            [
                'usrOprNm' => $params['usr_opr_nm'] ?? '',
                'usrOprMbl' => $this->encrypt($params['usr_opr_mbl'],$password),
                'usrOprEmail' => $params['usr_opr_email'] ?? '',
                'mercCertTyp' => '1-RSA',
            ]
        ];

        $contact = [
            [
                'contactType' => $params['contact_type'] ?? '',
                'contactName' => $params['contact_name'] ?? '',
                'contactMobile' => $this->encrypt($params['contact_mobile'],$password),
            ]
        ];

        $owner = [
            [
                'enterpriseOwner' => $params['enterprise_owner'] ?? '',
                'enterpriseOwnerIdType' => $params['enterprise_owner_id_type'] ?? '',
                'enterpriseOwnerIdNo' => $this->encrypt($params['enterprise_owner_id_no'],$password),
                'enterpriseOwnerIdEffDt' => $params['enterprise_owner_id_eff_dt'] ?? '',
                'enterpriseOwnerIdExpDt' => $params['enterprise_owner_id_exp_dt'] ?? '',
                'ownImg1' => 'front.png',
                'othImg2' => 'back.png',
            ]
        ];

        $stlLst = [
            [
                'stlSign' => "0",
                'selfStlFlg' => 'Y',
                'bankName' => $params['bank_name'] ?? '',
                'stlBankName' => $params['stl_bank_name'] ?? '',
                'lBnkNo' => $params['i_bnk_no'] ?? '',
                'stlOac' => $this->encrypt($params['stl_oac'],$password),
                'bnkOpnName' => $params['bnk_opn_name'] ?? '',
                'opnBnkProv' => $params['province'] ?? '',
                'opnBnkCity' => $params['city'] ?? '',
                'stlCls' => "1",
                'stlPerd' => "0",
                'pasStlFlg' => "0",
                'minRtnAmt' => "0",
                'stlTrfDays' => "1",
                'stlDay' => "1",
                'nextStlDt' => date('Ymd'),
                'stlBegAmt' => "0",
                'minRtnAmt' => "0",
            ]
        ];

        return [
            'reqTime' => date('YmdHis'),
            'merchantName' => $params['sub_merchant_name'] ?? '',
            'merchantShortName' => $params['sub_merchant_short_name'] ?? '',
            'mercIdEffDt' => $params['merc_id_eff_dt'] ?? '',
            'mercIdExpDt' => $params['merc_id_exp_dt'] ?? '',
            'regAddress' => $params['reg_address'] ?? '',
            'merchantIdType' => '01',
            'merchantIdNo' => $this->encrypt($params['merchant_id_no'],$password),
            'province' => $params['province'] ?? '',
            'city' => $params['city'] ?? '',
            'region' => $params['region'] ?? '',
            'mccCd' => $params['mcc_cd'] ?? '',
            'mccSubCd' => $params['mcc_sub_cd'] ?? '',
            'mercAttr' => (string)$params['merc_attr'] ?? '',
            'administrator' => json_encode($administrator,JSON_UNESCAPED_UNICODE),
            'contact' => json_encode($contact,JSON_UNESCAPED_UNICODE),
            'owner' => json_encode($owner,JSON_UNESCAPED_UNICODE),
            'stlLst' => json_encode($stlLst,JSON_UNESCAPED_UNICODE),
            'feeChargeFlg' => "1",
            'certPhotoImg' => 'certPhoto.png',
            'secretKey' => $this->signencrypt($password),
        ];

    }
}
