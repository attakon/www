<label>
    <br>
    <br>
</label>
<form method="POST" action="admin_myproblem_submission_process.php" enctype="multipart/form-data">
    <table border="0" width="600" height="50" >
        <tr>
            <td colspan="2" width="100%">
                <div id="myDiv"/>
                <?php

                // $divId = "submission_left_time_".$campaignId.'_'.$problemId;
                $result ='<div id="download-link-div"></div>';
                                    // print_r($isSubmissionPending);
                $downloadLink = "<div id='download-link'>
                    <a href='contest_arenadownloadinput.php?pid=".$problemId."'>Download Input File</a></div>";
                
                $result .= $downloadLink;
                
                
                echo $result;
                ?>
            </td>
        </tr>
        <tr>
            <td height="26" width="86">
                <p>Your output file:</p>
            </td>
            <td height="26" width="386">
                <input type="file" name="userout" size="39">
            </td>
        </tr>
        <tr>
            <td height="26" width="86">

                <p>Your source code:</p>
            </td>
            <td height="26" width="386">
                <input type="file" name="usersource" size="39">
            </td>
        </tr>
        <tr>
            <td colspan="2">
            </td>
        </tr>
        <tr>
            <td height="30" width="478" colspan="2">
                <p align="center">
                    <input type="submit" value="Submit solution" name="B1">
                    <input type="hidden" name="pid" value=<?php echo$problemId?> >
                </p>
            </td>            
        </tr>
    </table>
</form>