<?php

if(!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");


require_once DOKU_PROCESSING.'abstractGenerateHtml.php';

class loadAlgorithm extends abstractGenerateHtml {
    
    function loadAlgorithm() {
        $this->setFile(DOKU_PROCESSING.'templates/loadAlgorithm.html');
    }
    
}
?>

