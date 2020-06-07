<?php
namespace app\libs\common;

class RedisSess {

    /**
     * redis连接句柄
     * @var Redis $redis
     */
    private $redis;

    /**
     * Table prefix
     *
     * @var string
     */
    private $prefix;

    public function __construct($host = '127.0.0.1', $port = '6379', $auth = null, $db = '0', $ttl = '10', $usertime = '3600', $timeout = '3600', $sename = null, $sedomain = null, $scheme, $prefix = 'SSCC:') {

        // 配置 SESSION 保持位置
        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', 'tcp://'.$host.':'.$port.'?auth='.$auth);

        $this->redis = new \Redis();
        $this->redis->connect($host, $port, $ttl) or die('Redis 连接失败!');
        $this->redis->auth($auth);
        $this->redis->select($db);
        $this->usertime = $usertime;
        $this->timeout = $timeout;
        $this->prefix = $prefix;

        session_set_save_handler(
            array($this,"open"),
            array($this,"close"),
            array($this,"read"),
            array($this,"write"),
            array($this,"destroy"),
            array($this,"gc")
        );

        // 下面这行代码可以防止使用对象作为会话保存管理器时可能引发的非预期行为
        register_shutdown_function('session_write_close');

        session_set_cookie_params($usertime, "/", $sedomain, $scheme, true);
        session_name($sename);
        session_start();
    }

    /**
     * 打开session
     * @return bool
     */
    public function open() {
        return true;
    }

    /**
     * 关闭session
     * @return bool
     */
    public function close() {
        return true;
    }

    /**
     * 读取session
     * @param $id
     * @return bool|string
     */
    public function read($id) {
        $value = $this->redis->get($this->prefix.$id);
        if($value){
            return $value;
        }else{
            return '';
        }
    }

    /**
     * 设置session
     * @param $id
     * @param $data
     * @return bool
     */
    public function write($id, $data) {
        if($this->redis->set($this->prefix.$id, $data)) {
            if(substr_count($this->redis->get($this->prefix.$id),'user|')) {
                $this->redis->expire($this->prefix.$id, $this->usertime); 
            } else {
                $this->redis->expire($this->prefix.$id, $this->timeout); 
            }
            return true;
        }
        return false;
    }

    /**
     * 销毁session
     * @param $id
     * @return bool
     */
    public function destroy($id) {
        if($this->redis->delete($this->prefix.$id)) {
            return true;
        }
        return false;
    }

    /**
     * gc回收
     * @return bool
     */
    public function gc() {
        return true;
    }

    public function __destruct() {
        session_write_close();
    }

}
