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


