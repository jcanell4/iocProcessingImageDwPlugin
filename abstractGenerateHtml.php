<?php

if(!defined('DOKU_INC')) die();

abstract class abstractGenerateHtml {
   
    function remplacements($array, $fileString) {
        foreach ($array as $key => $value) {
            $fileString = preg_replace('/'.$key.'/',$value, $fileString);
        }
        return $fileString;
    }
}

?>
