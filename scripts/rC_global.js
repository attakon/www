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

function hideElement(elementId){
    document.getElementById(elementId).style.display='none';
}
function showElement(elementId){
    document.getElementById(elementId).style.display='block';
}

function selectProblemParseTypeForCaseInput(option){
    var option = option.trim();
    if(option=='STATIC-LINE-separated'){
        showElement('input-lines-per-case-div');
        hideElement('input-casemark-div');
        hideElement('input-include-match-div');
        
    }else if (option=='#CASEMARK-separated'){
        hideElement('input-lines-per-case-div');
        showElement('input-casemark-div');
        showElement('input-include-match-div');
        // document.getElementById('input-lines-per-case-div').style.display='none';
        // document.getElementById('input-casemark-div').style.display='block';
    }
}
function selectProblemParseTypeForCaseOutput(option){
    var option = option.trim();
    if(option=='STATIC-LINE-separated'){
        showElement('output-lines-per-case-div');
        hideElement('output-casemark-div');
        // document.getElementById('output-lines-per-case-div').style.display='block';
        // document.getElementById('output-casemark-div').style.display='none';
    }else if (option=='#CASEMARK-separated'){
        document.getElementById('output-lines-per-case-div').style.display='none';
        document.getElementById('output-casemark-div').style.display='block';
    }
}