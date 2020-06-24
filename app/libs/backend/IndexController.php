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

        //$data = array('user_name'=>'tongji','user_pwd'=>'d6ceebf494d774931e92e45f834d490f','user_ok'=>'1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1','user_look'=>'d6ceebf494d774931e92e45f834d490f','user_email'=>'10010@qq.com','user_ip'=>'127.0.0.1','user_logintime'=>1311954804);
        //$dbData = Api::fun()->getDB()->insert('user',$data);
        //$option = array('user.user_name','=','admin.admin_name');
        //$dbData = Api::fun()->getDB()->field('user.user_name,admin.admin_name')->join(array('LEFT','admin',$option))->select('user',1);
        //$option = array('user_name'=>'admin');
        //$dbData = Api::fun()->getDB()->where($option)->select('user',1);
        //$dbData = Api::fun()->getDB()->startTrans();
        //$dbData = Api::fun()->getDB()->where(array('user_name'=>'user'))->update('user',array('user_face'=>'测试一下'));
        //$dbData = Api::fun()->getDB()->where(array('user_name'=>'tongji'))->delete('user');
        //$dbData = Api::fun()->getDB()->commit();

        if(!empty($config['satoken'])&&!empty($_SESSION['token'])) {
            $token = unserialize($_SESSION['token']);
            if(trim($token[0])===trim(hex2bin($config['satoken']))&&trim($token[2])>(time()-trim($token[1]))) {
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
                    $_SESSION['t'] = time();
                    $verify = Api::fun()->getXTea(array($dbData['user_email'],$dbData['user_name'],$dbData['user_ok'],trim(Api::request()->user_agent),trim(Api::request()->ip),$dbData['user_logintime']));
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
        $Domain = Api::fun()->getDomain();
        Api::render('admin/index', array('domain' => $Domain,'title' => '地球村','pubKey' => base64_encode($pubKey),'token' => $token));
    }

}