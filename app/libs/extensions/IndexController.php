<?php
namespace app\libs\extensions;

use Api;

class IndexController extends BaseController{

    /**
     * 首页
     * @param  [type] $cid  [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public static function index() {
        $Domain = Api::fun()->getDomain();
        Api::render('index', array('domain' => $Domain,'title' => '地球村'));
    }

}