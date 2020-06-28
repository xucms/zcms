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

        //$data = array('user_name'=>'tongji','user_pwd'=>'d6ceebf494d774931e92e45f834d490f','user_ok'=>'1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1','user_lock'=>'d6ceebf494d774931e92e45f834d490f','user_email'=>'10010@qq.com','user_ip'=>'127.0.0.1','user_logintime'=>1311954804);
        //$dbData = Api::fun()->getDB()->insert('user',$data);
        //$option = array('user.user_name','=','admin.admin_name');
        //$dbData = Api::fun()->getDB()->field('user.user_name,admin.admin_name')->join(array('LEFT','admin',$option))->select('user',1);
        //$option = array('user_name'=>'admin');
        //$dbData = Api::fun()->getDB()->where($option)->select('user',1);
        //$dbData = Api::fun()->getDB()->startTrans();
        //$dbData = Api::fun()->getDB()->where(array('user_name'=>'user'))->update('user',array('user_face'=>'测试一下'));
        //$dbData = Api::fun()->getDB()->where(array('user_name'=>'tongji'))->delete('user');
        //$dbData = Api::fun()->getDB()->commit();

        $Domain = Api::fun()->getDomain();
        Api::render('admin/index', array('domain' => $Domain,'title' => '后台首页'));
    }

    /**
     * 解锁
     */
    public static function lock() {
        parent::__checkManagePrivate();
        $config = Api::request()->data;
        if(!empty($config['satoken'])&&!empty($_SESSION['token'])) {
            $token = unserialize($_SESSION['token']);
            if(trim($token[0])===trim(hex2bin($config['satoken']))&&trim($token[2])>(time()-trim($token[1]))) {
                $_SESSION['token'] = 0;
                $lock_pwd = Api::fun()->getRSA('rd',trim($config['lock_pwd']));
                if(!parent::isPWD($lock_pwd)) {
                    header('Location: ' . Api::request()->url);exit();
                }
                $sess = json_decode(Api::fun()->getXTea(Api::request()->cookies->Q,'d'), true);
                $option = array('user_name'=>trim($sess['u']),'user_lock'=>trim(md5(Api::fun()->getRSA('re',$lock_pwd))));
                $dbData = Api::fun()->getDB()->where($option)->select('user',1);
                if(!empty($dbData['user_name'])&&trim($sess['u'])===trim($dbData['user_name'])) {
                    $_SESSION['t'] = time();
                    header('Location: /admin-index');exit();
                }
            }
        }
        $pubKey = Api::fun()->getKey();
        $token = Api::fun()->getToken();
        $Domain = Api::fun()->getDomain();
        Api::render('admin/lock', array('domain' => $Domain,'title' => '地球村','pubKey' => base64_encode($pubKey),'token' => $token));
    }

    /**
     * 登陆
     */
    public static function login() {
        parent::__checkManagePrivate();
        $config = Api::request()->data;
        if(!empty($_SESSION['lock'])&&$_SESSION['lock']>3) {
            header('Location: /');exit();
        }
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
                    $seid = Api::fun()->getSessName();
                    $_SESSION['t'] = time()-Api::fun()->getLockTime()*2;
                    $verify = Api::fun()->getXTea(array('e'=>$dbData['user_email'],'u'=>$dbData['user_name'],'au'=>$dbData['user_ok'],'ua'=>trim(Api::request()->user_agent),'ip'=>trim(Api::request()->ip),'t'=>$dbData['user_logintime'],'id'=>Api::request()->cookies->$seid));
                    $ssid = Api::fun()->getSSID()->getid(md5(trim($dbData['user_name'])));
                    if(!empty($ssid)){
                        Api::fun()->getSESS()->destroy(trim($ssid));
                    }
                    Api::fun()->getSSID()->setid(md5(trim($dbData['user_name'])),trim(Api::request()->cookies->$seid),Api::fun()->getDomTime());
                    $_SESSION['user'] = md5($verify);
                    setcookie('TREE', md5(Api::request()->cookies->$seid), time()+Api::fun()->getDomTime(), '/', Api::fun()->getDomain(), ((Api::request()->scheme)==='http'?false:true),true);
                    setcookie('Q', $verify, time()+Api::fun()->getDomTime(), '/', Api::fun()->getDomain(), ((Api::request()->scheme)==='http'?false:true),true);
                    header('Location: /admin-index');exit();
                } else {
                    $_SESSION['lock'] = empty($_SESSION['lock'])?1:ceil($_SESSION['lock'])+1;
                    header('Location: ' . Api::request()->url);exit();
                }
            }
        }
        $pubKey = Api::fun()->getKey();
        $token = Api::fun()->getToken();
        $Domain = Api::fun()->getDomain();
        Api::render('admin/login', array('domain' => $Domain,'title' => '地球村','pubKey' => base64_encode($pubKey),'token' => $token));
    }
}