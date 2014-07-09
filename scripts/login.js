function initLogin() {
    loginFalse();
    $('#user').val(getCookie('user'));
    $('#pass').val(getCookie('pass'));
    login();
    
}

function login() {
    if ($('#user').val() === '' || $('#pass').val() === '') {
        return;
    }
    $.ajax({
        type: "POST",
        url: 'login.php',
        data: {username: $('#user').val(), password: $('#pass').val()},
        success: function(output) {
            parsedOutput = $.parseJSON(output);
            login_succes(parsedOutput);
        }
    });
}

function loginTrue() {
    $('#loginDiv').empty();
    $('#loginDiv').append("<div class='login'>Bentornato "+usr.user +  "!</div>");
    $('#loginDiv').append("<label class='killableCat loggati' onclick='logout()'>Logout</label>");
    $("#editButton").css("visibility","visible");
    showStuff();


}
function loginFalse() {
    $('#loginDiv').empty();
    $('#loginDiv').append("<form name='form1>");
    $('#loginDiv').append("<div class='login'><label for='user'>Username:</label><input type='text' size=20 id='user' name='user'></div>");
    $('#loginDiv').append("<div class='login'><lable for='pass'>Password:</label><input type='password' size=20 id='pass' name='pass'></div>");
    $('#loginDiv').append('<div class="login"><label class="killableCat loggati" onclick="login();">Login</label></div>');
    $('#loginDiv').append("</form>");
    $('#loginDiv').keypress(function(e)
        {
            if(e.which === 13) 
            {
                login();
            }
        });
    $("#editButton").css("visibility","hidden");   
    $("#ed").attr("href","css/noedit.css");
    showStuff();
}

function stayLogged() {
    setCookie('user', $('#user').val(), 365);
    setCookie('pass', $('#pass').val(), 365);
}

function login_succes(parsedOutput) {
    if (parsedOutput.status) 
    {
        if(parsedOutput.data.status==="BD_USER_LOGGED")
        {
            usr=parsedOutput.data;
            usr.edit=true;
            stayLogged();
            loginTrue();    
        }
        else
        {
            alert(parsedOutput.data.status);
        }
    } 
    else 
    {
        alert(parsedOutput.data.error); //trasformare l'alert in un messaggio in rosso nel form di login
    }
    
}

function logout() {
    $.ajax({
        url: 'logout.php',
        success: function(output) {
            parsedOutput = $.parseJSON(output);
            logout_succes(parsedOutput);
        }
    });
}

function logout_succes(parsedOutput)
{
    if (!parsedOutput.status)
    {
        alert('something bad happend!!'); //trasformare l'alert in un messaggio in rosso nel form di login
        return;
    }
    usr = {edit: false};
    setCookie('user', "", -1);
    setCookie('pass', "", -1);
    loginFalse();

}