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
<style>
body{margin:0;background:#f2f2f2;text-align:center;font-family:Arial,sans-serif;font-weight:100}
svg{width:100%;height:100%}
.center{position:absolute;top:50%;left:50%;-ms-transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}
label{position:relative;margin:0 auto 25px auto;display:inline-block}
.padlock{position:relative;width:60px;height:60px;opacity:1;-webkit-transition:.3s ease-in-out;transition:.3s ease-in-out}
.padlock.hide{opacity:0}
.padlock svg circle{stroke:#a2a2a2}
.padlock svg path{fill:#a2a2a2}
.padlock.unlock svg circle{stroke:#1fd34a}
.padlock.unlock svg path{fill:#1fd34a}
.input-icon{position:relative;-webkit-transition:.3s ease-in-out;transition:.3s ease-in-out}
.arrow-icon{position:absolute;top:0;right:0;width:50px;height:100%;background:#fff;display:none;z-index:-2}
.arrow-icon.show{display:block;z-index:2}
.arrow-icon svg{fill:#d8d8d8}
.arrow-icon:hover svg{fill:#444}
.password{position:relative;width:180px;height:45px;letter-spacing:0.05em;line-height:1em;margin:0;padding:2px 15px 2px 15px;outline:none;border:none;border-radius:1px;font-size:14px;-webkit-transition:box-shadow 0.25s ease;transition:box-shadow 0.25s ease}
.password:focus{-webkit-box-shadow:0 5px 10px rgba(0,0,0,0.2);box-shadow:0 5px 10px rgba(0,0,0,0.2)}
.submit{position:relative;background:#ffffff;width:80px;height:45px;letter-spacing:0.05em;line-height:1em;margin:10px auto;padding:0;outline:none;border:none;border-radius:1px;font-size:14px;-webkit-transition:box-shadow 0.25s ease;transition:box-shadow 0.25s ease}
.submit:focus{-webkit-box-shadow:0 5px 10px rgba(0,0,0,0.2);box-shadow:0 5px 10px rgba(0,0,0,0.2)}
.input-icon.unlock{opacity:0}
</style>
</head>
<body>

<div class="center">
    <label>
        <div class="padlock">
            <svg id="lock" viewbox="0 0 64 64">
                <circle stroke-width="2" fill="none" cx="32" cy="32" r="31" />
                <path d="M40.2,29v-4c0-4.5-3.7-8.2-8.2-8.2s-8.2,3.7-8.2,8.2v4H21v15h22V29H40.2z M27.2,25 c0-2.6,2.1-4.8,4.8-4.8s4.8,2.1,4.8,4.8v4h-9.5V25z" />
            </svg>
        </div>
    </label>
    <div class="input-icon">
        <form name="loginForm" id="login" action="/admin-lock" method="post">
            <input type="hidden" name="satoken" id="satoken" value="{$token}" autocomplete="off" class="hide"/>
            <input type="hidden" name="pubKey" id="pubKey" value="{$pubKey}" autocomplete="off" class="hide"/>
            <input type="hidden" name="ip" id="ip" value="" autocomplete="off" class="hide"/>
            <input class="password" type="password" name="lock_pwd" id="lock_pwd" value="" autocomplete="off" placeholder="Lock screen password" >
            <input class="submit" type="submit" name="submit" id="loginsubmit" value="UNLOCK"/>
        </form>
        <div class="arrow-icon">
            <svg id="arrow" viewbox="0 0 48 44">
                <polygon points="27.7,13.3 26.3,14.7 31.6,20 14,20 14,22 31.6,22 26.3,27.3 27.7,28.7 35.4,21 " />
            </svg>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function() {
  var t = $('#lock_pwd');
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