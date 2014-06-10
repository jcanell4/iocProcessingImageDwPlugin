<?php

/*
 *
 * @author Daniel Criado Casas<dani.criado.casas@gmail.com>
 */
// must be run within Dokuwiki
if (!defined('DOKU_INC'))
    die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");

require_once DOKU_INC . 'inc/common.php';
require_once DOKU_PROCESSING . 'generators/generateImage.php';
require_once DOKU_PROCESSING . 'generators/loadAlgorithm.php';
require_once DOKU_PROCESSING . 'generators/galleryImage.php';

/**
 * All DokuWiki plugins to extend the admin function
 * need to inherit from this class
 */
class admin_plugin_processingmanager extends DokuWiki_Admin_Plugin {

    private static $COMMA = ",";

    /**
     * Constructor
     */
    function admin_plugin_processingmanager() {
        global $auth;

        $this->setupLocale();

        if (!isset($auth)) {
            $this->disabled = $this->lang['noauth'];
        } else {
            // we're good to go
            $this->_auth = & $auth;
        }
    }

    /**
     * return prompt for admin menu
     */
    function getMenuText($language) {

        if (!is_null($this->_auth))
            return parent::getMenuText($language);

        return $this->getLang('menu') . ' ' . $this->disabled;
    }

    /**
     * return sort order for position in admin menu
     */
    function getMenuSort() {
        return 3;
    }

    /**
     * handle user request
     */
    function handle() {
        
    }

    /**
     * output appropriate html
     */
    function html() {
        $changes = array();
        $this->setParamsChanges($changes);

        $generateImageGenerator = new generateImage();
        $loadAlgorithmGenerator = new loadAlgorithm();
        $galleryImageGenerator = new galleryImage();


        $generateImageGenerator->setChanges($changes);
        $generateImageHtml = $generateImageGenerator->getHtml();

        $loadAlgorithmGenerator->setChanges($changes);
        $loadAlgorithmHtml = $loadAlgorithmGenerator->getHtml();

        $galleryImageGenerator->setChanges($changes);
        $galleryImageHtml = $galleryImageGenerator->getHtml();
        
        if(isset($_REQUEST['tab'])){
            switch ($_REQUEST['tab']){
                case "generateImage":
                    echo $generateImageHtml;
                    break;
                case "loadAlgorithm":
                    echo $loadAlgorithmHtml;
                    break;
                case "galleryImage":
                    echo $galleryImageHtml;
                    break;
            }            
        }else{
            $dojo = "<script type='text/javascript'>"
                    . "require(["
                    . "'dojo/parser', "
                    . "'dijit/layout/TabContainer', "
                    . "'dijit/layout/ContentPane'"
                    . "]);</script>";
            $html = ""
                    . "<div style='width: 800; height: 650px;'>"
                    . "<div data-dojo-type='dijit/layout/TabContainer' style='width:100%; height: 100%;'>"
                    . "<div data-dojo-type='dijit/layout/ContentPane' title='Generació' id='generateImage' >" . $generateImageHtml . "</div>"
                    . "<div data-dojo-type='dijit/layout/ContentPane' title='Càrrega' id='loadAlgorithm'>" . $loadAlgorithmHtml . "</div>"
                    . "<div data-dojo-type='dijit/layout/ContentPane' title='Galeria' id='galleryImage'>" . $galleryImageHtml . "</div>"
                    . "</div>"
                    . "</div>"
                    . "";
            echo $dojo . $html;
        }
    }

    private function setParamsChanges(& $changes) {
        $this->setGenerateImageChanges($changes);
        $this->setLoadAlgorithmChanges($changes);
        $this->setGalleryImageChanges($changes);
    }

    private function setGenerateImageChanges(& $changes) {
        //PARAMS
        $changes['@urlsParam@'] = $this->getConf('urlsParam');
        $changes['@CookieParam@'] = $this->getConf('CookieParam');
        $changes['@sectokParam@'] = $this->getConf('sectokParam');
        $changes['@getPdeClassesURLParam@'] = $this->getConf('getPdeClassesURLParam');
        $changes['@fileSenderURLParam@'] = $this->getConf('fileSenderURLParam');
        $changes['@nameSenderURLParam@'] = $this->getConf('nameSenderURLParam');
        //VALUES
        $changes['@appletHeight@'] = $this->getConf('appletHeight');
        $changes['@appletWidth@'] = $this->getConf('appletWidth');
        $changes['@codeValue@'] = $this->getConf('codeValue');
        $changes['@archiveValue@'] = $this->getConf('appletJarName');
        $changes['@urlsValue@'] = $this->getUrlsValue();
        $changes['@CookieValue@'] = $this->getCookies();
        $changes['@sectokValue@'] = getSecurityToken();
        $changes['@getPdeClassesURLValue@'] = DOKU_URL . "lib/plugins/ajaxcommand/ajax.php?call=" . $this->getConf('getPdeClassesInfoCommand');
        $changes['@fileSenderURLValue@'] = DOKU_URL . "lib/plugins/ajaxcommand/ajax.php?call=" . $this->getConf('fileSenderCommand');
        $changes['@nameSenderURLValue@'] = DOKU_URL . "lib/plugins/ajaxcommand/ajax.php?call=" . $this->getConf('nameSenderCommand');
        $changes['@imageNameOption@'] = $this->getConf('imageNameOption');
        $changes['@imageNameOptionValue@'] = $this->getConf('imageNameOptionValue');
    }

    private function setLoadAlgorithmChanges(& $changes) {
        $changes['@emtpyError@'] = $this->getLang('emptyError');
        $changes['@equalNameError@'] = $this->getLang('equalNameError');
        $changes['@onlyExtensionError@'] = $this->getLang('onlyExtensionError');
        $changes['@fileExistsError@'] = $this->getLang('fileExistsError');
        $changes['@unexpectedError@'] = $this->getLang('unexpectedError');
        $changes['@formLegend@'] = $this->getLang('formLegend');
        $changes['@file@'] = $this->getLang('file');
        $changes['@overwrite@'] = $this->getLang('overwrite');
        $changes['@rename@'] = $this->getLang('rename');
        $changes['@name@'] = $this->getLang('name');
        $changes['@description@'] = $this->getLang('description');
        $changes['@reset@'] = $this->getLang('reset');
        $changes['@upload@'] = $this->getLang('upload');
        $changes['@select@'] = $this->getLang('select');
        $changes['@nameTitlte@'] = $this->getLang('nameTitle');
        $changes['@renameTitle@'] = $this->getLang('renameTitle');
        
        
        $changes['@appendAlgorithmParam@'] = $this->getConf('appendAlgorithmParam');
        $changes['@modifyAlgorithmParam@'] = $this->getConf('modifyAlgorithmParam');
        $changes['@existsAlgorithmParam@'] = $this->getConf('existsAlgorithmParam');
        $changes['@savePdeAlgorithmCommand@'] = $this->getConf('savePdeAlgorithmCommand');
        
        $changes['@pdeExtension@'] = $this->getConf('pdeExtension');
    }

    private function setGalleryImageChanges(& $changes) {
        
        $changes['@galleryImage@'] = $this->getGalleryImage();
        
        
        $changes['@unexpectedError@'] = $this->getLang('unexpectedError');
        $changes['@emptyNameImageError@'] = $this->getLang('emptyNameImageError');
        $changes['@noImageSelectError@'] = $this->getLang('noImageSelectError');
        $changes['@submitTitle@'] = $this->getLang('submitTitle');
        $changes['@copyImage@'] = $this->getLang('copyImage');
    }

    private function getGalleryImage() {
        $dir = "data/media" . $this->getConf('processingImageRepository');
        $arrayDir = scandir($dir);
        $html = "";
        if (count($arrayDir) > 2) {
            unset($arrayDir[0]);
            unset($arrayDir[1]);
            $arrayDir = array_values($arrayDir);
            foreach ($arrayDir as $file) {
                $name = substr($file, 0, -4); //Li treu la extensio .pde
                $html .="<div class='iGallery'>"
                        . "<div class='iCheckbox'><input type='radio' name='checkImage' value='" . $file . "'/></div>"
                        . "<img src='" . $dir . $file . "' />"
                        . "<div class='iLink'><a href='" . DOKU_URL . $dir . $file . "' target='_blank'>Veure Original</a></div></div>";
            }
        } else {
            $html = "<div>@noPdeImage@</div>";
        }
        closedir($arrayDir);
        return $html;
    }

    private function getUrlsValue() {
        $urls = $this->getConf('urls');
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

    /**
     * Genera un string amb les cookies.
     * @global type $_COOKIE
     * @return string 
     */
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
