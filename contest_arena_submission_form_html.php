<label>
    <br>
    When you are ready, download the input file.
    <br>&nbsp;
</label>
<!--BEGIN SUBMIT FORM -->
<form method="POST" action="contest_arena_process_submit.php" enctype="multipart/form-data">
    <table border="0" width="600" height="50" >
        <tr>
            <td colspan="2" width="100%">
                <?php
                include_once 'data_objects/DAOCampaign.php';
                $isSubmissionPending = DAOCampaign_isSubmissionPending($contestId, $campaignId, $problemId);
                $result ='';
                                    // print_r($isSubmissionPending);
                $downloadLink = "<a href='contest_arenadownloadinput.php?pid=".$problemId."&cmpid=".$userCampaignData['id_campaign']."'>Download New Input File</a>";
                if($isSubmissionPending){
                    $divId = "submission_left_time'.$contestId.'_'.$campaignId.'_'.$problemId.'";
                    $countDown = '<div id="'.$divId.'"></div>
                    <script type="text/javascript">
                    timers[timerCount++]={
                        div_name:"'.$divId.'"
                        ,left_time:'.$isSubmissionPending['submission_left_time'].'
                        ,end_message:"'.$downloadLink.'"
                    };
                    </script>';
                    $result = $countDown;
                }else{
                    $result = $downloadLink;
                                        // '<a href="contest_arenadownloadinput.php?pid='.$problemId.'&cmpid='.$userCampaignData['id_campaign'].'">Download Input File</a>';
                }
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
                <label class="comment">
                    <!-- Para practicar no se requiere presentar el c&oacute;digo fuente. -->
                </label>
            </td>
        </tr>
        <tr>
            <td height="30" width="478" colspan="2">
                <p align="center">
                    <input type="submit" value="Submit solution" name="B1">
                    <input type="hidden" name="pid" value=<?php echo$problemId?> >
                    <input type="hidden" name="cmpid" value=<?php echo$userCampaignData['id_campaign']?> >
                </p>
            </td>            
        </tr>
    </table>
</form>