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

</body>
</html>