<?php

goTCLogin();
// $html = goTCProblem(
// 	'http://community.topcoder.com/stat?c=problem_solution&cr=22691410&rd=15181&pm=12200',
	// '/Users/rc/test1/dumpfile.txt');

$html = goTCProblem(
	'http://community.topcoder.com/stat?c=problem_solution&cr=22691410&rd=15181&pm=12200');

// echo $html;
$arr = explode("<!-- System Testing -->", $html);
// $arr = explode("<!-- System Testing -->", $html);
// echo sizeof($arr);
$finalArr = explode("<!-- End System Testing -->", $arr[1]);
// echo sizeof($finalArr);
$finalArr=$finalArr[0];
$finalArr = preg_replace("/<IMG.+</", "<", $finalArr);

// $finalArr=str_replace("<", '&lt;', $finalArr);$finalArr=str_replace(">", '&gt;', $finalArr);echo $finalArr;



// foreach ($finalArr as $key => $value) {
// 	if(trim($value)=='<!-- System Testing -->'){
// 		$go=1;continue;
// 	}
// 	if(trim($value)=='<!-- End System Testing -->'){
// 		$go=0;break;
// 	}
// 	if($go){
// 		// $value=str_replace("<", '&lt;', $value);
// 		// $value=str_replace(">", '&gt;', $value);
// 		$result+=$value;
// 	}
// }
// echo $result;
// $finalArr = '<table>
// 			<td align="right">x
// 			</td>
// 			</table>';


$xml = new SimpleXMLElement($finalArr);

/* Search for <a><b><c> */
// <TR valign="top">
//             <TD BACKGROUND="/i/steel_blue_bg.gif" WIDTH="10">
//                 </TD>
//             <TD BACKGROUND="/i/steel_blue_bg.gif" CLASS="statText" ALIGN="left">{7, 4}</TD>
//             <TD BACKGROUND="/i/steel_blue_bg.gif" WIDTH="5">
//                 </TD>
//             <TD BACKGROUND="/i/steel_blue_bg.gif" CLASS="statText" ALIGN="right">7</TD>
//             <TD BACKGROUND="/i/steel_blue_bg.gif" WIDTH="5">
//                 </TD>
//             <TD BACKGROUND="/i/steel_blue_bg.gif" CLASS="statText" ALIGN="right">Passed</TD>
//             <TD BACKGROUND="/i/steel_blue_bg.gif" WIDTH="5">
//                 </TD>
//         </TR>

$xPathResult = $xml->xpath("//TR [@valign='top']/TD[@ALIGN = 'left']//text()");
echo sizeof($xPathResult)."</br>";
foreach ($xPathResult as $key => $obValue) {
	echo $obValue[0]."<br>";
}
$xPathResult = $xml->xpath("//TR[@valign='top']/TD[@ALIGN = 'right'][text()!='Passed']/text()");

echo sizeof($xPathResult)."</br>";
foreach ($xPathResult as $key => $obValue) {
	echo $obValue[0]."<br>";
}

// print_r($xPathResult);

function goTCLogin(){
	include_once '../scrap/Curl.php';
	$curl = new Curl('/Users/rc/test1/cookiejar.txt');
	$url = "http://community.topcoder.com/tc";


	$html = $curl->postForm($url,
		array('module' => 'Login', 'username'=>'raulooo','password'=>'presenta'));
	
}

function goTCProblem($url, $dumpFile=null){
	include_once '../scrap/Curl.php';

	$curl = new Curl('/Users/rc/test1/cookiejar.txt', $dumpFile);
	$html = $curl->get($url);
	// $curl->closeDumpFile();
	return $html;
}

function go(){
	include_once '../scrap/Curl.php';
	$curl = new Curl();
	$html = $curl->get("http://www.google.com");
}

function pureCurl(){
	// $ch = curl_init("http://www.huahcoding.com/");
	$ch = curl_init("http://community.topcoder.com/tc?module=Login&username=raulooo&password=presenta");
	$fp = fopen("/Users/rc/test1/example_homepage.txt", "w");

	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "/Users/rc/test1/cookiejar.txt"); 


	curl_exec($ch);

	echo curl_error($ch);

	curl_close($ch);
	fclose($fp);
	echo 'done';

}

?>