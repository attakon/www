function log_out(confirmation_message)
{
    var ht = document.getElementsByTagName("html")[0];
    ht.style.filter = "progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)";
    if (confirm(confirmation_message))
    {
        return true;
    }
    else
    {
        ht.style.filter = "";
        return false;
    }
}


function fblogin(){
    var nextPage = window.location;
    FB.login(function(response) {
        if (response.authResponse) {
            window.location = "/login_from_fb.php?nextPage="+nextPage;
         // window.location.reload();
        }
    });
}

function fblogout(){
    window.location = "/logout.php";
    // we dont want the user to log out of his fb session
    // FB.logout(function(response) {
    //     alert('xxx');
    //     window.location.reload();
    // });
}


function doit(){
    var fu1 = document.getElementById("FileUpload1");
    console.debug(fu1.files);
    console.debug(fu1.value);
    alert("You selected " + fu1.value);
}