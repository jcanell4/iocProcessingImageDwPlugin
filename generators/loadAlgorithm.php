<?php

if(!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");


require_once DOKU_PROCESSING.'abstractGenerateHtml.php';

class loadAlgorithm extends abstractGenerateHtml {
    
    function loadAlgorithm($adminPlugin) {
        $this->setFile(DOKU_PROCESSING.'templates/loadAlgorithm.html');
        $this->adminPlugin = $adminPlugin;
    }
    
    
    function initChanges(& $changes) {
        $changes['@emtpyError@'] = $this->adminPlugin->getLang('emptyError');
        $changes['@equalNameError@'] = $this->adminPlugin->getLang('equalNameError');
        $changes['@onlyExtensionError@'] = $this->adminPlugin->getLang('onlyExtensionError');
        $changes['@fileExistsError@'] = $this->adminPlugin->getLang('fileExistsError');
        $changes['@unexpectedError@'] = $this->adminPlugin->getLang('unexpectedError');
        $changes['@formLegend@'] = $this->adminPlugin->getLang('formLegend');
        $changes['@file@'] = $this->adminPlugin->getLang('file');
        $changes['@overwrite@'] = $this->adminPlugin->getLang('overwrite');
        $changes['@rename@'] = $this->adminPlugin->getLang('rename');
        $changes['@name@'] = $this->adminPlugin->getLang('name');
        $changes['@description@'] = $this->adminPlugin->getLang('description');
        $changes['@reset@'] = $this->adminPlugin->getLang('reset');
        $changes['@upload@'] = $this->adminPlugin->getLang('upload');
        $changes['@select@'] = $this->adminPlugin->getLang('select');
        $changes['@nameTitlte@'] = $this->adminPlugin->getLang('nameTitle');
        $changes['@renameTitle@'] = $this->adminPlugin->getLang('renameTitle');
        
        $changes['@appendAlgorithmParam@'] = $this->adminPlugin->getConf('appendAlgorithmParam');
        $changes['@modifyAlgorithmParam@'] = $this->adminPlugin->getConf('modifyAlgorithmParam');
        $changes['@existsAlgorithmParam@'] = $this->adminPlugin->getConf('existsAlgorithmParam');
        $changes['@savePdeAlgorithmCommand@'] = $this->adminPlugin->getConf('savePdeAlgorithmCommand');
        
        $changes['@pdeExtension@'] = $this->adminPlugin->getConf('pdeExtension');
        $changes['@AJAX_COMMAND_URL@'] = DOKU_URL."lib/plugins/ajaxcommand/ajax.php";
        return $changes;
    }
}
?>

