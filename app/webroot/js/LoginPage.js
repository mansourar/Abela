var LoginLock = false;

function Login() {
    if(LoginLock){
        return false;
    }
    LoginLock = true;
    $("#fountainG").show();
    var URL = "/Abela/Login/AutheticateUser";
    setTimeout(function () {
        $.ajax({
            url: URL,
            dataType: "json",
            type: 'POST',
            async: false,
            data: {
                UserName: function () {
                    return document.getElementById('UserName').value.trim();
                },
                Password: function () {
                    return document.getElementById('Password').value.trim();
                }
            },
            success: function (data) {
                if (data === "false") {
                    new PNotify({
                        title: 'Login Error. ',
                        text: 'Invalid Login Credential! ',
                        type: 'error',
                        delay:1500
                    });
                    $("#fountainG").hide();
                    document.getElementById('UserName').value = "";
                    document.getElementById('Password').value = "";
                    LoginLock = false;
                    return false;
                }
                if(data === "true"){
                    window.location.href = "/Abela/Cycles/index";
                    LoginLock = false;
                }
            },
            error: function (request, error) {
                new PNotify({
                    title: 'Network Error. ',
                    text: 'An Error Has Occured ! Please Try Again Later. ',
                    type: 'error',
                    delay:1500
                });
                $("#fountainG").hide();
                document.getElementById('UserName').value = "";
                document.getElementById('Password').value = "";
                LoginLock = false;
            }
        });
    }, 3000);
}