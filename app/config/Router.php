<?php 
// 路由映射表
return array(
    'auth'=>'index,admin,list,user,data', // 默认需要认证模型
    'index' => array(
        array('GET|POST /((@cid:[0-9]+/)@page:[0-9]+)', 'frontend\Index:index'),
        array('GET /search', 'frontend\Index:search'),
    ),
    'admin' => array(
        array('GET|POST /((@cid:[0-9]+/)@page:[0-9]+)', 'frontend\Index:index'),
        array('GET /search', 'frontend\Index:search'),
    ),
    'list' => array(
        array('GET|POST /((@cid:[0-9]+/)@page:[0-9]+)', 'frontend\Index:index'),
        array('GET /search', 'frontend\Index:search'),
    ),
    'user' => array(
        array('GET|POST /((@cid:[0-9]+/)@page:[0-9]+)', 'frontend\Index:index'),
        array('GET /search', 'frontend\Index:search'),
    ),
    'data' => array(
        array('GET|POST /((@cid:[0-9]+/)@page:[0-9]+)', 'frontend\Index:index'),
        array('GET /search', 'frontend\Index:search'),
    ),
);
