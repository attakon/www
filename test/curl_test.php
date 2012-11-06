<?php

// goTCLogin();
goTCProblem('http://community.topcoder.com/stat?c=problem_solution&cr=22691410&rd=15181&pm=12200');

function goTCLogin(){
	
	include_once '../scrap/Curl.php';
	$curl = new Curl('/Users/rc/test1/cookiejar.txt');
	$url = "http://community.topcoder.com/tc";
	$html = $curl->postForm($url,
		array('module' => 'Login', 'username'=>'raulooo','password'=>'presenta'));

}
function goTCProblem($url){
	
	include_once '../scrap/Curl.php';
	$curl = new Curl('/Users/rc/test1/cookiejar.txt');
	// $url = "http://community.topcoder.com/tc";
	$html = $curl->get($url);
	echo $html;
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