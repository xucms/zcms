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

        $token = Api::fun()->getToken();
        Api::render('index', array('title' => '测试接口','token' => $token));
    }

}