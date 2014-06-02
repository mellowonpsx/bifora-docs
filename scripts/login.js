var usr;

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
    $('#login').append(usr.user);
    $('#loginDiv').empty();
    $('#loginDiv').append(usr.name+" "+usr.surname+"<br>"+usr.mail+"<br>");
    $('#loginDiv').append("<button id='logout' onclick='logout()'>Logout</button>");
}
function loginFalse(){
    $('#login').empty();
    $('#login').append('Login');
    $('#loginDiv').empty();
    $('#loginDiv').append("<form name='form1>");
    $('#loginDiv').append("Username:   <input type='text' size=20 id='user' name='user'><br>");
    $('#loginDiv').append("Password:   <input type='password' size=20 id='pass' name='pass' ><br>");
    $('#loginDiv').append('<button id="loggati" onclick="login();">Login</button>');
    $('#loginDiv').append("</form>");
}
function login(){
    if($('#user').val()===''||$('#pass').val()===''){
        return; //alert (?)
   
    }
    
  $.ajax({
        type: "POST",
        //url  : 'login.php'+'?user='+$('#user').val()+'&pass='+$('#pass').val(), 
        url  : 'login.php', 
        data: { username: $('#user').val(), password: $('#pass').val()},
        success :  function(output){login_succes(output);}

});
}

function login_succes(output){
    usr=$.parseJSON(output);
    if(usr.status==="BD_USER_LOGGED"){
        stayLogged();
        loginTrue();
        showStuff(); //aggiunto per ricaricare dopo login!
    }else{
        alert('Invalid username or password'); //trasformare l'alert in un messaggio in rosso nel form di login
    }
        

}

function logout(){
    $.ajax({
        url  : 'logout.php', 
        success :  function(output){logout_succes(output);}
    });
}

function logout_succes(output)
{
        //alert(output);
        usr=$.parseJSON(output);
        if(usr.status!=="BD_USER_UNLOGGED")
        {
            alert('something bad happend!!'); //trasformare l'alert in un messaggio in rosso nel form di login
        }
        setCookie('user',"",-1);
        setCookie('pass',"",-1);
        loginFalse();
    
}
function stayLogged(){

    setCookie('user',$('#user').val(),365);
    setCookie('pass',$('#pass').val(),365);
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
