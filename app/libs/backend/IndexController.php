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

        $pubKey = Api::fun()->getKey();
        $token = Api::fun()->getToken();
        Api::render('admin/index', array('title' => '地球村','pubKey' => $pubKey,'token' => $token));
    }

}