<!doctype html>
<html lang="zh-cmn-Hans">
<head>
<meta charset="utf-8">
<title>{$title}</title>
<script src="/public/creator/jquery/jquery.min.js"></script>
<script src="/public/creator/jquery/jquery.cookie.js"></script>
<script src="/public/creator/jquery/jsencrypt.min.js"></script>
<script src="/public/creator/js/common.js"></script>
<link rel="stylesheet" type="text/css" href="/public/creator/css/common.min.css">
<script>$.cookie('GUID', '1', { expires: 7, path: '/', domain: '{$domain}' });</script>
<script>getUserIP(function(ip){$('#ip').val(ip);});</script>
</head>
<body>

<h1>{$title}</h1>

<form name="loginForm" id="login" action="/login" method="post">
    <input type="text" name="user_name" id="user_name" value="" autocomplete="off" placeholder="手机/邮箱/用户名" />
    <input type="password" name="user_pwd" id="user_pwd" value="" autocomplete="off" placeholder="密码" />
    <input type="hidden" name="satoken" id="satoken" value="{$token}" autocomplete="off" class="hide"/>
    <input type="hidden" name="pubKey" id="pubKey" value="{$pubKey}" autocomplete="off" class="hide"/>
    <input type="hidden" name="ip" id="ip" value="" autocomplete="off" class="hide"/>
    <input type="submit" name="submit" id="loginsubmit" value="登录"/>
</form>

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