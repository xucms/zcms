<!doctype html>
<html lang="zh-cmn-Hans">
<head>
<meta charset="utf-8">
<title>{$title}</title>
<script src="/public/creator/jquery/jquery.min.js"></script>
<script src="/public/creator/jquery/jquery.cookie.js"></script>
<script src="/public/creator/jquery/jsencrypt.min.js"></script>
<script src="/public/creator/js/md5.min.js"></script>
<script src="/public/creator/js/common.js"></script>
<link rel="stylesheet" type="text/css" href="/public/creator/css/common.min.css">
<style>
.center{position:absolute;top:50%;left:50%;-ms-transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);}
.login{position:relative;margin:auto;padding:20px 20px 20px;width:310px;background:white;border-radius:3px;-webkit-box-shadow:0 0 200px rgba(255,255,255,0.5),0 1px 2px rgba(0,0,0,0.3);box-shadow:0 0 200px rgba(255,255,255,0.5),0 1px 2px rgba(0,0,0,0.3);}
.login:before{content:'';position:absolute;top:-8px;right:-8px;bottom:-8px;left:-8px;z-index:-1;background:rgba(0,0,0,0.08);border-radius:4px;}
.login h1{margin:-20px -20px 21px;line-height:40px;font-size:15px;font-weight:bold;color:#555;text-align:center;text-shadow:0 1px white;background:#f3f3f3;border-bottom:1px solid #cfcfcf;border-radius:3px 3px 0 0;background-image:-webkit-linear-gradient(top,whiteffd,#eef2f5);background-image:-moz-linear-gradient(top,whiteffd,#eef2f5);background-image:-o-linear-gradient(top,whiteffd,#eef2f5);background-image:linear-gradient(to bottom,whiteffd,#eef2f5);-webkit-box-shadow:0 1px whitesmoke;box-shadow:0 1px whitesmoke;}
.login p{margin:20px 0 0;}
.login p:first-child{margin-top:0;}
.login input[type=text],.login input[type=password]{width:278px;}
.login p.remember_me{float:left;line-height:31px;}
.login p.remember_me label{font-size:12px;color:#777;cursor:pointer;}
.login p.remember_me input{position:relative;bottom:1px;margin-right:4px;vertical-align:middle;}
.login p.submit{text-align:center;}
:-moz-placeholder{color:#c9c9c9 !important;font-size:13px;}
::-webkit-input-placeholder{color:#ccc;font-size:13px;}
input{font-family:'Lucida Grande',Tahoma,Verdana,sans-serif;font-size:14px;}
input[type=text],input[type=password]{margin:5px;padding:0 10px;width:200px;height:34px;color:#404040;background:white;border:1px solid;border-color:#c4c4c4 #d1d1d1 #d4d4d4;border-radius:2px;outline:5px solid #eff4f7;-moz-outline-radius:3px;-webkit-box-shadow:inset 0 1px 3px rgba(0,0,0,0.12);box-shadow:inset 0 1px 3px rgba(0,0,0,0.12);}
input[type=text]:focus,input[type=password]:focus{border-color:#7dc9e2;outline-color:#dceefc;outline-offset:0;}
input[type=submit]{padding:0 18px;width:80px;height:30px;font-size:12px;font-weight:bold;color:#527881;text-shadow:0 1px #e3f1f1;background:#cde5ef;border:1px solid;border-color:#b4ccce #b3c0c8 #9eb9c2;border-radius:16px;outline:0;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;background-image:-webkit-linear-gradient(top,#edf5f8,#cde5ef);background-image:-moz-linear-gradient(top,#edf5f8,#cde5ef);background-image:-o-linear-gradient(top,#edf5f8,#cde5ef);background-image:linear-gradient(to bottom,#edf5f8,#cde5ef);-webkit-box-shadow:inset 0 1px white,0 1px 2px rgba(0,0,0,0.15);box-shadow:inset 0 1px white,0 1px 2px rgba(0,0,0,0.15);}
input[type=submit]:active{background:#cde5ef;border-color:#9eb9c2 #b3c0c8 #b4ccce;-webkit-box-shadow:inset 0 0 3px rgba(0,0,0,0.2);box-shadow:inset 0 0 3px rgba(0,0,0,0.2);}
.lt-ie9 input[type=text],.lt-ie9 input[type=password]{line-height:34px;}
</style>
</head>
<body>

<div class="center">
  <div class="login">
    <h1>{$title}管理登录</h1>
    <form name="loginForm" id="login" action="/login" method="post">
      <p><input type="text" name="user_name" id="user_name" value="" autocomplete="off" placeholder="用户名" /></p>
      <p><input type="password" name="user_pwd" id="user_pwd" value="" autocomplete="off" placeholder="密码" /></p>
      <input type="hidden" name="satoken" id="satoken" value="{$token}" autocomplete="off" class="hide"/>
      <input type="hidden" name="pubKey" id="pubKey" value="{$pubKey}" autocomplete="off" class="hide"/>
      <input type="hidden" name="ip" id="ip" value="" autocomplete="off" class="hide"/>
      <p class="submit"><input type="submit" name="submit" id="loginsubmit" value="登陆"/></p>
    </form>
  </div>
</div>

<script type="text/javascript">
$(function() {
  var t = $('#user_pwd');
  var k = $('#pubKey');
  t.focus(function() {
    t.val('');
  });
  $("#loginsubmit").click(function() {
    if(t.val()!==''){
      var encrypt = new JSEncrypt();
      encrypt.setPublicKey(window.atob(k.val()));
      var encrypted = encrypt.encrypt(t.val());
      t.val(encrypted);
    }
  });
});
</script>

</body>
</html>