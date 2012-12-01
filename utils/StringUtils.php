<?php

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    $start  = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}
function matchLanguageIcon($sourceCode)
{
	//0 Default
	//1 java
    //2 php
    //3 c++
    //4 c#
    //5 phyton
	if (strpos($sourceCode,'#include<') !== false) {
	    return "c++.png";
	}elseif (strpos($sourceCode,'import java') !== false) {
		return "java.png";
	}elseif (strpos($sourceCode,'<?php') !== false) {
		return "php.png";
	}elseif (strpos($sourceCode,'﻿using System;') !== false) {
		return "csharp.png";
	}
    return "";
}
?>
