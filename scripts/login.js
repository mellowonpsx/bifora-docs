function initLogin() {
    $('#loginWrapper').hover(
            function() {
                $('#loginDiv').stop(true, true).delay(50).slideDown(100);
            },
            function() {
                $('#loginDiv').stop(true, true).slideUp(200);
            }
    );
    loginFalse();
    $('#loginDiv').stop(true, true).slideUp(0);
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
    $('#login').empty();
    $('#login').append(usr.user);
    $('#loginDiv').empty();
    $('#loginDiv').append(usr.name + " " + usr.surname + "<br>" + usr.mail + "<br>");
    $('#loginDiv').append("<button id='logout' onclick='logout()'>Logout</button>");
    $("#editButton").css("visibility","visible");
    showStuff();


}
function loginFalse() {
    $('#login').empty();
    $('#login').append('Login');
    $('#loginDiv').empty();
    $('#loginDiv').append("<form name='form1>");
    $('#loginDiv').append("Username:   <input type='text' size=20 id='user' name='user'><br>");
    $('#loginDiv').append("Password:   <input type='password' size=20 id='pass' name='pass' ><br>");
    $('#loginDiv').append('<button id="loggati" onclick="login();">Login</button>');
    $('#loginDiv').append("</form>");
    $('#loginDiv').keypress(function(e)
        {
            if(e.which === 13) 
            {
                login();
            }
        });
    $("#editButton").css("visibility","hidden");        
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