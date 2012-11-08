<?php

class TCIOScrap
{
	public $inputList;
	public $outputList;
	public $ioURL;
	public function __construct($ioURL) {
        $this->ioURL=$ioURL;
        $this->doProcess();
    }

    function doProcess(){
    	$this->goTCLogin();
		$html = $this->goTCProblem($this->ioURL);
		$arr = explode("<!-- System Testing -->", $html);
		$finalArr = explode("<!-- End System Testing -->", $arr[1]);
		$finalArr=$finalArr[0];
		$finalArr = preg_replace("/<IMG.+</", "<", $finalArr);
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

		// do input
		$xPathResult = $xml->xpath("//TR [@valign='top']/TD[@ALIGN = 'left']//text()");
		// echo sizeof($xPathResult)."</br>";
		// $inputList = array();
		foreach ($xPathResult as $key => $obValue) {
			$this->inputList[] = (string)$obValue[0];
		}

		// do output
		$xPathResult = $xml->xpath("//TR[@valign='top']/TD[@ALIGN = 'right'][text()!='Passed']/text()");

		// echo sizeof($xPathResult)."</br>";
		foreach ($xPathResult as $key => $obValue) {
			$this->outputList[] = (string)$obValue[0];
		}
    }

    function goTCLogin(){
		include_once 'Curl.php';
		$curl = new Curl('cookiejar.txt');
		$url = "http://community.topcoder.com/tc";

		$html = $curl->postForm($url,
			array('module' => 'Login', 'username'=>'12481632','password'=>'darngood'));
	}

	function goTCProblem($url, $dumpFile=null){
		include_once 'Curl.php';
		$curl = new Curl('cookiejar.txt', $dumpFile);
		$html = $curl->get($url);
		// $curl->closeDumpFile();
		return $html;
	}
}