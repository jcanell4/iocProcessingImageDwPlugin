<?php

if(!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");


require_once DOKU_PROCESSING.'abstractGenerateHtml.php';

class generateImage extends abstractGenerateHtml {
    
    private static $COMMA = ",";
    
    function generateImage($adminPlugin) {
        $this->setFile(DOKU_PROCESSING.'templates/generateImage.html');
        $this->adminPlugin = $adminPlugin;
    }
    
    function initChanges($changes) {
        //PARAMS
        $changes['@urlsParam@'] = $this->adminPlugin->getConf('urlsParam');
        $changes['@CookieParam@'] = $this->adminPlugin->getConf('CookieParam');
        $changes['@sectokParam@'] = $this->adminPlugin->getConf('sectokParam');
        $changes['@getPdeClassesURLParam@'] = $this->adminPlugin->getConf('getPdeClassesURLParam');
        $changes['@fileSenderURLParam@'] = $this->adminPlugin->getConf('fileSenderURLParam');
        $changes['@nameSenderURLParam@'] = $this->adminPlugin->getConf('nameSenderURLParam');
        //VALUES
        $changes['@appletHeight@'] = $this->adminPlugin->getConf('appletHeight');
        $changes['@appletWidth@'] = $this->adminPlugin->getConf('appletWidth');
        $changes['@codeValue@'] = $this->adminPlugin->getConf('codeValue');
        $changes['@archiveValue@'] = $this->adminPlugin->getConf('appletJarName');
        $changes['@urlsValue@'] = $this->getUrlsValue();
        $changes['@CookieValue@'] = $this->getCookies();
        $changes['@sectokValue@'] = getSecurityToken();
        $changes['@getPdeClassesURLValue@'] = DOKU_URL . "lib/plugins/ajaxcommand/ajax.php?call=" . $this->adminPlugin->getConf('getPdeClassesInfoCommand');
        $changes['@fileSenderURLValue@'] = DOKU_URL . "lib/plugins/ajaxcommand/ajax.php?call=" . $this->adminPlugin->getConf('fileSenderCommand');
        $changes['@nameSenderURLValue@'] = DOKU_URL . "lib/plugins/ajaxcommand/ajax.php?call=" . $this->adminPlugin->getConf('nameSenderCommand');
        $changes['@imageNameOption@'] = $this->adminPlugin->getConf('imageNameOption');
        $changes['@imageNameOptionValue@'] = $this->adminPlugin->getConf('imageNameOptionValue');
        return $changes;
    }
    
    private function getUrlsValue() {
        $urls = $this->adminPlugin->getConf('urls');
        $arrayUrls = split(',', $urls);
//        $urlsValue = DOKU_URL."lib/_java/pde/classes/";
        $urlsValue = "";
        $urlsLink = "";
        foreach ($arrayUrls as $key => $value) {
            $urlsValue .= $urlsLink . DOKU_URL . $value;
            $urlsLink = self::$COMMA;
        }
        return $urlsValue;
    }
    
    private function getCookies() {
        global $_COOKIE;
        $strCookie = "";
        $ctrl = false;
        foreach ($_COOKIE as $key => $value) {
            if ($ctrl) {
                $strCookie .= "; ";
            } else {
                $ctrl = true;
            }
            $strCookie .= $key . "=" . rawurlencode($value);
        }
        return $strCookie;
    }
}
?>

