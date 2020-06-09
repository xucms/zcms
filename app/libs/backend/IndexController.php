<?php
namespace app\libs\backend;

use Api;

class IndexController {

    /**
     * 首页
     * @param  [type] $cid  [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public static function index() {
        Api::fun()->getSESS();

        $config = Api::request()->data;
        //print_r(Api::request());
        if(!empty($config['satoken'])&&!empty($_SESSION['token'])){
            $token = unserialize($_SESSION['token']);
            if(trim($token[0])==trim($config['satoken'])&&trim($token[2])>(time()-trim($token[1]))) {
                $_SESSION['token'] = 0;
                $user_name = trim($config['user_name']);
                if(!Api::verify()->isNames($user_name)){
                    header('Location: ' . Api::request()->url);exit();
                }
                $user_pwd = Api::fun()->getRSA('rd',trim($config['user_pwd']));
                if(!Api::verify()->isPWD($user_pwd)){
                    header('Location: ' . Api::request()->url);exit();
                }
                header('Location: /');exit();
            }
        }

        $pubKey = Api::fun()->getKey();
        $token = Api::fun()->getToken();
        Api::render('admin/index', array('title' => '地球村','pubKey' => base64_encode($pubKey),'token' => $token));
    }

}