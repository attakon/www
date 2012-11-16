<?php
function SEOshuffle(&$items, $seed=false) {
  $original = md5(serialize($items));
  mt_srand(crc32(($seed) ? $seed : $items[0]));
  for ($i = count($items) - 1; $i > 0; $i--){
    $j = @mt_rand(0, $i);
    list($items[$i], $items[$j]) = array($items[$j], $items[$i]);
  }
  if ($original == md5(serialize($items))) {
    list($items[count($items) - 1], $items[0]) = array($items[0], $items[count($items) - 1]);
  }
}

function compareOutputs($tmpName, $problemId, $seed=null, $isTest=null) {

    //[TODO] Chance of improvement. Bring only Output
    include_once 'data_objects/DAOProblem.php';

    $problemIO = DAOProblem_getProblemIO($problemId);


    if($seed)
        SEOshuffle($problemIO, $seed);

    // print_r($problemIO);
    // $correctOutputContent = "";
    // foreach ($problemIO as $key => $value) {
    //     // $inputContent .= $value['case_input'];
    //     $correctOutputContent .= $value['case_output'];
    // }

    // $correct = explode("\n", $correctOutputContent);

    //PHP 3 and higher
    // $finfo = finfo_open(FILEINFO_MIME);
    // $fin = finfo_file($finfo, $tmpName);
    // echo $fin;
    // die;

    
    $file_handle = fopen($tmpName, "r");
    $i = 0;
    $res;

    foreach($problemIO as $key=>$val) {
        // print_r($correctLine);
        $correctLine = trim($val['case_output']);
        // $correctLine = trim($correctLine);
        // if(strcmp($correctLine,"")!=0) {
        $i++;
        if(!feof($file_handle)) {
            $userLine = trim(str_replace("\n", "",fgets($file_handle)));
            // print_r($userLine);
            if(strcmp($correctLine, $userLine)!=0) {
                fclose($file_handle);
                
                $userLine = str_replace("<", "&lt;", $userLine);
                $userLine = str_replace(">", "&gt;", $userLine);

                return array('accepted'=>false,
                'killer_case_id'=>$val['testcase_id'],
                'killed_answer'=>$userLine,
                // 'message'=>"Wrong Answer: line ".$i." tu salida[".$userLine."] esperado[".$correctLine."]");
                'message'=>"Wrong Answer: line ".$i." tu salida[".$userLine."] esperado[".$correctLine."]");
                // break;
            }
        }else {
            fclose($file_handle);
            return array('accepted'=>false,
                'killer_case_id'=>$val['testcase_id'],
                'killed_answer'=>'',
                // 'message'=>"Wrong Answer: Your output[] <br/>Expected:[".$correctLine."]");
                'message'=>"Wrong Answer");
            // break;
        }
        // }
    }
    fclose($file_handle);
    $res = array('accepted'=>true);
    return $res;
}
?>