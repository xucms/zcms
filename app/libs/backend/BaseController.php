<?php
namespace app\libs\backend;

use Api;

class BaseController {

    /**
     * 检测管理员权限
     * @param  boolean $force [description]
     * @return [type]         [description]
     */
    protected static function __checkManagePrivate() {
        Api::fun()->getSESS();
        if(empty($_COOKIE['GUID'])||(!empty($_COOKIE['TREE'])&&md5(session_id())!=trim($_COOKIE['TREE']))||(!empty($_SESSION['user'])&&trim($_SESSION['user'])!=md5($_COOKIE['Q']))) {
            header('Location: /error.html');
            exit();
        }
        if(!empty($_SESSION['user'])&&!empty($_COOKIE['Q'])&&(trim($_SESSION['user'])==md5($_COOKIE['Q']))){
            setcookie('Q', $_COOKIE['Q'], time()+Api::fun()->getDomTime(), '/', Api::fun()->getDomain(), ((Api::request()->scheme)=='http'?false:true),true);
            setcookie('TREE', md5(session_id()), time()+Api::fun()->getDomTime(), '/', Api::fun()->getDomain(), ((Api::request()->scheme)=='http'?false:true),true);
        }
    }

    /**
     * 验证用户名
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public static function isNames($value, $minLen=2, $maxLen=25, $charset='ALL') {
        if(empty($value)) {
            return false;
        }
        switch($charset) {
            case 'EN': $match = '/^[_\w\d\.\@]{'.$minLen.','.$maxLen.'}$/iu';
                break;
            case 'CN':$match = '/^[_\.\@\x{4e00}-\x{9fa5}\d]{'.$minLen.','.$maxLen.'}$/iu';
                break;
            default:$match = '/^[_\.\@\w\d\x{4e00}-\x{9fa5}]{'.$minLen.','.$maxLen.'}$/iu';
        }
        return preg_match($match,$value);
    }

    /**
     * 验证密码
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public static function isPWD($value,$minLen=5,$maxLen=64) {
        $match='/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{'.$minLen.','.$maxLen.'}$/';
        $v = trim($value);
        if(empty($v)) {
            return false;
        }
        return preg_match($match,$v);
    }
 
    /**
     * 验证eamil
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public static function isEmail($value,$match='/^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/i') {
        $v = trim($value);
        if(empty($v)) {
            return false;
        }
        return preg_match($match,$v);
    }
 
    /**
     * 验证电话号码
     * @param string $value
     * @return boolean
     */
    public static function isTelephone($value,$match='/^0[0-9]{2,3}[-]?\d{7,8}$/') {
        $v = trim($value);
        if(empty($v)) {
            return false;
        }
        return preg_match($match,$v);
    }
 
    /**
     * 验证手机
     * @param string $value
     * @param string $match
     * @return boolean
     */
    public static function isMobile($value,$match='/^[(86)|0]?(13\d{9})|(15\d{9})|(18\d{9})$/') {
        $v = trim($value);
        if(empty($v)) {
            return false;
        }
        return preg_match($match,$v);
    }

    /**
     * 验证邮政编码
     * @param string $value
     * @param string $match
     * @return boolean
     */
    public static function isPostcode($value,$match='/\d{6}/') {
        $v = trim($value);
        if(empty($v)) {
            return false;
        }
        return preg_match($match,$v);
    }

    /**
     * 验证IP
     * @param string $value
     * @param string $match
     * @return boolean
     */
    public static function isIP($value,$match='/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/') {
        $v = trim($value);
        if(empty($v)){
            return false;
        }
        return preg_match($match,$v);
    }

    /**
     * 验证身份证号码
     * @param string $value
     * @param string $match
     * @return boolean
     */
    public static function isIDcard($value,$match='/^\d{6}((1[89])|(2\d))\d{2}((0\d)|(1[0-2]))((3[01])|([0-2]\d))\d{3}(\d|X)$/i') {
        $v = trim($value);
        if(empty($v)){
            return false;
        } else if(strlen($v)>18) {
            return false;
        }
        return preg_match($match,$v);
    }

    /**
     * 验证URL
     * @param string $value
     * @param string $match
     * @return boolean
     */
    public static function isURL($value,$match='/^(http:\/\/)?(https:\/\/)?([\w\d-]+\.)+[\w-]+(\/[\d\w-.\/?%&=]*)?$/') {
        $v = strtolower(trim($value));
        if(empty($v)) {
            return false;
        }
        return preg_match($match,$v);
    }

    // 18位身份证校验码有效性检查
    public static function check18IDCard($IDCard) {
        if (strlen($IDCard) != 18) {
            return false;
        }
        $IDCardBody = substr($IDCard, 0, 17); //身份证主体
        $IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码
        if (calcIDCardCode($IDCardBody) != $IDCardCode) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 金额格式化
     * @param string $number 数字
     */
    public static function format_money($number) {
        return number_format($number,2,'.','');
    }

    /**
     * 校验金额格式
     * @param  [type] $accountPrice 金额值
     * @return [type] [description]
     */
    public static function check_money_format($accountPrice) {
        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $accountPrice)){ return false; }
        return true;
    }
}