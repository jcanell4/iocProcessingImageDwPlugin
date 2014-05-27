<?php

if(!defined('DOKU_INC')) die();
require_once DOKU_INC.'lib/plugins/processingmanager/abstractGenerateHtml.php';

class generateImage extends abstractGenerateHtml {
    
    function getFileString() {
        return io_readFile(DOKU_INC.'lib/plugins/processingmanager/templates/generate.html');
    }
    
}
?>

