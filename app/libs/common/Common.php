<?php 
namespace app\libs\common;

use app;

class Common extends app\Engine {

    // 网站名称
    public function getTitle() {
        $config = $this->get('web.config');
        return trim($config['title']);
    }

    // 作用域
    public function getDomain() {
        $config = $this->get('web.config');
        return trim($config['sess.domain']);
    }

    // 获取 SESSION_NAME
    public function getSessName() {
        $config = $this->get('web.config');
        return trim($config['sess.name']);
    }

    // 作用域有效时间
    public function getDomTime() {
        $config = $this->get('web.config');
        return trim($config['usertime']);
    }

    // 锁屏时间
    public function getLockTime() {
        $config = $this->get('web.config');
        return trim($config['lock']);
    }

    // RSA 第三次公共证书
    public function getKey($name = 'public') {
        $config = $this->get('web.config');  // 密钥
        return trim(preg_replace('/[\r\n]/', '',$config[$name.'.third']));
    }

    // TOKEN 令牌
    public function getToken($string = '') {
        $request = $this->request()->user_agent;
        $config = $this->get('web.config');
        $token = trim(md5(md5($request).md5(uniqid('',true).md5($string).md5($this->getKey('token')))));
        $_SESSION['token'] = serialize(array($token,time(),$config['token']));
        return preg_replace('/\s+/','',$this->escape(trim($token)));
    }

    // 设置SESSION链接
    public function getSESS($name = 'sess') {
        if (!isset(self::$dbInstances[$name])) {
            $config = $this->get('web.config');
            $request = $this->request()->scheme;
            $this->loader->register('getRedisSESS', 'app\libs\common\RedisSESS',array (
                $config[$name.'.host'],   // 服务器连接地址。默认='127.0.0.1'
                $config[$name.'.port'],   // 端口号。默认='6379'
                $config[$name.'.auth'],   // 连接密码，如果有设置密码的话
                $config[$name.'.db'],     // 缓存库选择。默认0
                $config[$name.'.ttl'],    // 连接超时时间（秒）。默认10
                $config['usertime'],      // 默认用户登录过期时间，单位秒。不填默认3600
                $config['timeout'],       // 默认用户未登录过期时间，单位秒。不填默认3600
                $config[$name.'.name'],   // SESSION name
                $config[$name.'.domain'], // 作用域
                ($request=='http'?false:true),

            ));
            try {
                $dbs = $this->getRedisSESS();
                if (!$dbs) {
                    throw new \Exception();
                }
                self::$dbInstances[$name] = $dbs;
            } catch (\Exception $e) {
                die(json_encode(array('code'=>500, 'msg'=>'Redis数据库连接失败', 'data'=>false), JSON_UNESCAPED_UNICODE));
            }
        }
        return self::$dbInstances[$name];
    }

    // 设置SESSION_ID链接
    public function getSSID($name = 'user') {
        if (!isset(self::$dbInstancesi[$name])) {
            $config = $this->get('web.config');
            $this->loader->register('getRedisSSID', 'app\libs\common\RedisSSID',array (
                $config[$name.'.host'],   // 服务器连接地址。默认='127.0.0.1'
                $config[$name.'.port'],   // 端口号。默认='6379'
                $config[$name.'.auth'],   // 连接密码，如果有设置密码的话
                $config[$name.'.db'],     // 缓存库选择。默认0
                $config[$name.'.ttl'],    // 连接超时时间（秒）。默认10
            ));
            try {
                $dbs = $this->getRedisSSID();
                if (!$dbs) {
                    throw new \Exception();
                }
                self::$dbInstancesi[$name] = $dbs;
            } catch (\Exception $e) {
                die(json_encode(array('code'=>500, 'msg'=>'Redis数据库连接失败', 'data'=>false), JSON_UNESCAPED_UNICODE));
            }
        }
        return self::$dbInstancesi[$name];
    }

    // 设置数据库链接
    public function getDB($name = 'db') {
        if (!isset(self::$dbsInstances[$name])) {
            $config = $this->get('web.config');
            $this->loader->register('getDbPdo', 'app\libs\common\MySQLPDO',array (
                $config[$name.'.host'],    // 数据库主机地址 默认='127.0.0.1'
                $config[$name.'.user'],    // 数据库用户名
                $config[$name.'.pass'],    // 数据库密码
                $config[$name.'.name'],    // 数据库名称
                $config[$name.'.charset'], // 数据库编码 默认=utf8
                $config[$name.'.port'],    // 数据库端口 默认=3306
                $config[$name.'.prefix'],  // 数据库表前缀
            ));
            try {
                $dbs = $this->getDbPdo();
                if (!$dbs) {
                    throw new \Exception();
                }
                self::$dbsInstances[$name] = $dbs;
            } catch (\Exception $e) {
                die(json_encode(array('code'=>500, 'msg'=>'Mysqli数据库连接失败', 'data'=>false), JSON_UNESCAPED_UNICODE));
            }
        }
        return self::$dbsInstances[$name];
    }

    // XAES加密 返回JSON
    public function getXTea($data = 'str', $id = 'e') {
        $this->loader->register('getTea', 'app\libs\common\CommonTea');
        $srt = $this->getTea();
        switch ($id) {
            case 'e':
                return str_replace(array('+', '/', '='), array('-', '_', '~'),$this->encrypt(str_replace(array('+', '/', '='), array('-', '_', '~'),$srt->XEncrypt($data, md5($this->getKey('cookie')))), substr(md5($this->getKey('cookie')), 8, 16))); // 加密
                break;
            case 'd':
                return $srt->XDecrypt(str_replace(array('-', '_', '~'), array('+', '/', '='),$this->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $data), substr(md5($this->getKey('cookie')), 8, 16))), md5($this->getKey('cookie'))); // 解密
                break;
            default:
                return 'AES Error: Data not';
        }
    }

    /**
     * @param string $string 需要加密的字符串
     * @param string $key 密钥
     * @return string
     */
    public function encrypt($string, $key) {
        // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变
        $data = openssl_encrypt($string, 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
        return base64_encode($data);
    }

    /**
     * @param string $string 需要解密的字符串
     * @param string $key 密钥
     * @return string
     */
    public function decrypt($string, $key) {
        $decrypted = openssl_decrypt(base64_decode($string), 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
        return $decrypted;
    }

    // RSA 加密, 解密, 签名, 验签 返回JSON
    public function getRSA($id = 're', $data, $sign = false, $third = false) {
        $config = $this->get('web.config');  // 密钥
        $this->loader->register('getRsaSrt', 'app\libs\common\CommonRsa',array(
            $config['public.third'],
            $config['private'],
            (empty($third)?$this->getKey():$this->getKey($third)),
        ));
        $srt = $this->getRsaSrt();
        switch ($id) {
            case 're':
                return $srt->privEncrypt($data); // 私钥加密
                break;
            case 'ud':
                return $srt->publicDecrypt($data); // 公钥解密
                break;
            case 'ue':
                return $srt->publicEncrypt($data); // 公钥加密
                break;
            case 'rd':
                return $srt->privDecrypt($data); // 私钥解密
                break;
            case 'rs':
                return $srt->privSign($data); // 私钥签名
                break;
            case 'uv':
                return $srt->publicVerifySign($data, $sign); // 公钥验证
                break;
            case 'tv':
                return $srt->publicVerifySignThird($data, $sign); // 第三方公钥验证
                break;
            default:
                return 'RSA Error: Data not';
        }
    }

    // 将二进制转换十六进制
    public function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') {
        $return = '';
        if (function_exists('mb_get_info')) {
            for($x = 0; $x<mb_strlen($string, $in_encoding); $x++) {
                $str = mb_substr($string, $x, 1, $in_encoding);
                if (strlen($str)>1) { // 多字节字符
                    $return .= '%'.'u' . strtoupper(bin2hex(mb_convert_encoding($str, $out_encoding, $in_encoding)));
                } else {
                    $return .= '%' . strtoupper(bin2hex($str));
                }
            }
        }
        return str_replace('%', ' ', $return);
    }

    // 修复 HTML 标签闭合问题（检查并补全）
    public function fixHtml($srt){
        $srt = preg_replace('/<[^>]*$/','',$srt);
        preg_match_all('/<([a-z]+)(?: .*)?(?<![\/|\/ ])>/iU', $srt, $result);
        if($result){
            $opentags = $result[1];
            preg_match_all('/<\/([a-z]+)>/iU', $srt, $result);
            if($result){
                $closetags = $result[1];
                $len_opened = count($opentags);
                if (count($closetags) == $len_opened) {
                    return $srt;  //没有未关闭标签
                }
                $opentags = array_reverse($opentags);
                $sc = array('br','input','img','hr','meta','link');  //跳过这些标签
                for ($i=0; $i < $len_opened; $i++) {
                    $ot = strtolower($opentags[$i]);
                    if (!in_array($opentags[$i], $closetags) && !in_array($ot,$sc)) {
                        $srt .= '</'.$opentags[$i].'>';  //补齐标签
                    } else {
                        unset($closetags[array_search($opentags[$i], $closetags)]);
                    }
                }
            }
        }
        return $srt;
    }

}