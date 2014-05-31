var user="";
var pass="";

function initLogin(){
    $('#loginWrapper').hover(
        function () {
            $('#loginDiv').stop(true, true).delay(50).slideDown(100);
        }, 
        function () {
            $('#loginDiv').stop(true, true).slideUp(200);        
        }
    );
    loginFalse();
    $('#loginDiv').stop(true, true).slideUp(0);  
    $('#user').val(getCookie('user'));
    $('#pass').val(getCookie('pass'));
    login();
  
}
function loginTrue(){
    $('#login').empty();
    $('#login').append(getCookie('user'));
    $('#loginDiv').empty();
    $('#loginDiv').append("Logged as: "+getCookie('user')+"<br>");
    $('#loginDiv').append("<button id='logout' onclick='logout()'>Logout</button>");
}
function loginFalse(){
    $('#login').empty();
    $('#login').append('Login');
    $('#loginDiv').empty();
    $('#loginDiv').append("Username:   <input type='text' size=20 id='user'><br>");
    $('#loginDiv').append("Password:   <input type='password' size=20 id='pass'><br>");
    $('#loginDiv').append('<button id="loggati" onclick="login();">Login</button>');
}
function login(){
    if($('#user').val()===''||$('#pass').val()===''){
        return;
        
    }  

    user=$('#user').val();
    pass=$('#pass').val(); 
 
    stayLogged();
    loginTrue();
}
function logout(){
        setCookie('user',user,-1);
        setCookie('pass',pass,-1);
        loginFalse();
    
}
function stayLogged(){

    setCookie('user',user,365);
    setCookie('pass',pass,365);
}
function md5pass(pass,salt){
    return CryptoJS.MD5(pass||salt);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}
