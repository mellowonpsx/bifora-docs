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
        return; //alert (?)
    }
    $.ajax({
        type: "POST",
        url: 'login.php',
        data: {username: $('#user').val(), password: $('#pass').val()},
        success: function(output) {
            login_succes(output);
        }
    });
}

function loginTrue() {
    $('#login').empty();
    $('#login').append(usr.user);
    $('#loginDiv').empty();
    $('#loginDiv').append(usr.name + " " + usr.surname + "<br>" + usr.mail + "<br>");
    $('#loginDiv').append("<button id='logout' onclick='logout()'>Logout</button>");
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

}

function stayLogged() {
    setCookie('user', $('#user').val(), 365);
    setCookie('pass', $('#pass').val(), 365);
}

function login_succes(output) {
    usr = $.parseJSON(output);
    usr.edit = true;
    if (usr.status === "BD_USER_LOGGED") {
        stayLogged();
        loginTrue();
        //showStuff(); //aggiunto per ricaricare dopo login!
        updateCategory();
    } else {
        alert('Invalid username or password'); //trasformare l'alert in un messaggio in rosso nel form di login
    }


}

function logout() {
    $.ajax({
        url: 'logout.php',
        success: function(output) {
            logout_succes(output);
        }
    });
}

function logout_succes(output)
{
    usr = $.parseJSON(output);
    if (usr.status !== "BD_USER_UNLOGGED")
    {
        alert('something bad happend!!'); //trasformare l'alert in un messaggio in rosso nel form di login
    }
    usr = {edit: false};
    setCookie('user', "", -1);
    setCookie('pass', "", -1);
    loginFalse();
    //showStuff(); //aggiunto per ricaricare dopo login!
    updateCategory();
}




