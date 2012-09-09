<?php

class PHPClass {
    //put your code here
    function boolean compareTwoFiles(){
        $file_handle = fopen("myfile", "r");
        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            echo $line;
        }
        fclose($file_handle);
    }
}
?>
