<?php

if(!defined('DOKU_INC')) die();
require_once DOKU_INC.'lib/plugins/processingmanager/abstractGenerateHtml.php';

class generateImage extends abstractGenerateHtml {
    
    function generateImage() {
        $this->setFile(DOKU_INC.'lib/plugins/processingmanager/templates/generate.html');
    }
    
}
?>

