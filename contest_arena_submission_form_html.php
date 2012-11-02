
<!--BEGIN SUBMIT FORM -->
<script type="text/javascript">

    function getInputFile(campaignId, problemId, leftTime){
        startTimer(campaignId, problemId, leftTime);
        downloadFile(campaignId, problemId);
    }
    function downloadFile(campaignId, problemId){       
        var url = "contest_arenadownloadinput.php?pid="+problemId+"&cmpid="+campaignId;
        document.location.href=url;
    }
    function startTimer(campaignId, problemId, leftTime){
         var divId = "submission_left_time_"+campaignId+'_'+problemId;

        document.getElementById('download-link').style.display='none';
        var callback = function(){
            document.getElementById(divId).innerHTML = "Time's Up";
            document.getElementById('download-link').style.display='block';
        }

        timers[timerCount]={
                        div_name:divId
                        ,left_time:leftTime
                        ,end_callback: callback
                    };
        CreateTimer(timers[timerCount]['div_name'],timers[timerCount]['left_time'],timerCount);
        timerCount++;
    }
</script>
<form method="POST" action="contest_arena_process_submit.php" enctype="multipart/form-data">
    <table id="submit-table" border="0" height="50" style="display:none">
        <tr>
            <td colspan="2" width="100%">
                <div id="myDiv"/>
                <?php
                include_once 'data_objects/DAOCampaign.php';
                $lastSubmission = DAOCampaign_getLastSubmission($contestId, $campaignId, $problemId);

                $divId = "submission_left_time_".$campaignId.'_'.$problemId;
                $result ='<div id="'.$divId.'" style="font-size: 16px; color:red"></div>';
                // $result ='';
                include_once 'GLOBALS.php'; 
                $downloadLink = 
                    "<div id='download-link'>
                        <a style='cursor: pointer' onclick='getInputFile(".$campaignId.",".$problemId.",".SUBMISSION_ALLOWED_SECONDS.")'>Download New Input File</a>
                        <label> when you are ready (You will have 4 minutes to submit your solution)</label>
                    </div>";
                // print_r($lastSubmission);
                if($lastSubmission){

                    if($lastSubmission['killed_answer']!=null){
                        $escapedKilledAnswer = str_replace("<", "&lt;", $lastSubmission['killed_answer']);
                        $escapedKilledAnswer = str_replace(">", "&gt;", $escapedKilledAnswer);

                        // echo 'XXX'.$lastSubmission['killed_answer']."XXX";
                        $result .= 
                            '<div id="last-case-notice" style="border-width:1px; border-color:red;border-style:solid;"> 
                                <div style="color:red"> Last Failed Submission </div>
                                <div style="width:65px; display:inline-block">For Input:</div> <div style="display:inline-block; background-color:#DAECFF" id="input-case">'.trim($lastSubmission['case_input']).'</div>
                                <br/> <div style="width:65px; display:inline-block">Expected:</div> <div style="display:inline; background-color:#DAECFF" id="expected-answer">'.trim($lastSubmission['case_output']).'</div>
                                <br/> <div style="width:65px; display:inline-block">Received:</div> <div style="display:inline; background-color:#FCC7C7" id="received-answer"> '.$escapedKilledAnswer.'</div>'.
                            '</div>';
                        $result .= $downloadLink;
                    }
                    else if($lastSubmission['submission_left_time']>0 && $lastSubmission['submission_left_time']<=SUBMISSION_ALLOWED_SECONDS){
                        $countDown = '
                        <script type="text/javascript">
                            jQuery(document).ready(function(){
                                startTimer('.$campaignId.','.$problemId.','.$lastSubmission['submission_left_time'].');
                            });
                        </script>';
                        $result .= $countDown.$downloadLink;
                    }else {
                        $result=$result.$downloadLink;
                    }
                }else{
                    $result .= $downloadLink;
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