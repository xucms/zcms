//setInterval(function(){(function (x){return (function (x){return (Function('Function(arguments[0]+"'+x+'")()'))})(x)})('bugger')('de',0,0,(0,0));},0.001);

if(navigator.cookieEnabled){
    $.cookie('GUID', md5(navigator.userAgent), {expires: 7, path: '/'});
}

console.log(navigator);
console.log(document);
