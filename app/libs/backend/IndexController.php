<?php
namespace app\libs\backend;

use Api;

class IndexController extends BaseController{

    /**
     * 首页
     * @param  [type] $cid  [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public static function index() {
        parent::__checkManagePrivate();
        $config = Api::request()->data;
        if(!empty($config['satoken'])&&!empty($_SESSION['token'])) {
            $token = unserialize($_SESSION['token']);
            if(trim($token[0])==trim($config['satoken'])&&trim($token[2])>(time()-trim($token[1]))) {
                $_SESSION['token'] = 0;
                $user_name = trim($config['user_name']);
                if(!parent::isNames($user_name)) {
                    header('Location: ' . Api::request()->url);exit();
                }
                $user_pwd = Api::fun()->getRSA('rd',trim($config['user_pwd']));
                if(!parent::isPWD($user_pwd)) {
                    header('Location: ' . Api::request()->url);exit();
                }
                $option = array('user_name'=>trim($user_name),'user_pwd'=>array(trim(md5(Api::fun()->getRSA('re',$user_pwd))),'=','and'));
                $dbData = Api::fun()->getDB()->where($option)->select('user',1);
                //echo Api::fun()->getDB()->getLastSql(); // 最后一次运行 SQL 语句
                if(!empty($dbData)) {
                    $verify = Api::fun()->getXTea(array($dbData['user_email'],$dbData['user_name'],$dbData['user_ok'],$dbData['user_ip'],$dbData['user_logintime']));
                    $_SESSION['user'] = md5($verify);
                    setcookie('TREE', md5(session_id()), time()+Api::fun()->getDomTime(), '/', Api::fun()->getDomain(), ((Api::request()->scheme)=='http'?false:true),true);
                    setcookie('Q', $verify, time()+Api::fun()->getDomTime(), '/', Api::fun()->getDomain(), ((Api::request()->scheme)=='http'?false:true),true);
                    header('Location: /admin-index');exit();
                } else {
                    header('Location: ' . Api::request()->url);exit();
                }
            }
        }
        $pubKey = Api::fun()->getKey();
        $token = Api::fun()->getToken();
        Api::render('admin/index', array('title' => '地球村','pubKey' => base64_encode($pubKey),'token' => $token));
    }

}