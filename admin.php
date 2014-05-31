<?php

/*
 *  User Manager
 *
 *  Dokuwiki Admin Plugin
 *
 *  This version of the user manager has been modified to only work with
 *  objectified version of auth system
 *
 *  @author  neolao <neolao@neolao.com>
 *  @author  Chris Smith <chris@jalakai.co.uk>
 */
// must be run within Dokuwiki
if (!defined('DOKU_INC'))
    die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");

if (!defined('DOKU_JAVA'))
    define('DOKU_JAVA', DOKU_INC . 'lib/_java/');

if (!defined('DOKU_JAVA_LIB'))
    define('DOKU_JAVA_LIB', DOKU_JAVA . 'lib/');

if (!defined('DOKU_JAVA_PDE_CLASSES'))
    define('DOKU_JAVA_PDE_CLASSES', DOKU_JAVA . 'pde/classes/');

if (!defined('DOKU_COMMAND'))
    define('DOKU_COMMAND', DOKU_PLUGIN . 'ajaxcommand/');


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

        $dojo = "<script type='text/javascript'>"
                . "require(["
                . "'dojo/parser', "
                . "'dijit/layout/TabContainer', "
                . "'dijit/layout/ContentPane'"
                . "]);</script>";
        $html = ""
                . "<div style='width: 800; height: 650px;'>"
                . "<div data-dojo-type='dijit/layout/TabContainer' style='width:100%; height: 100%;'>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Generació d'imatges' id='generateImage' >" . $generateImageHtml . "</div>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Càrrega d'algorismes' id='loadAlgorithm'>" . $loadAlgorithmHtml . "</div>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Galeria de imatges' id='galleryImage'>" . $galleryImageHtml . "</div>"
                . "</div>"
                . "</div>"
                . "";
        echo $dojo . $html;
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
        //$changes['@archiveValue@'] = DOKU_JAVA . $this->getConf('appletJarName'); PERQUE AMB RUTA RELATIVA?
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
        
    }

    private function setGalleryImageChanges(& $changes) {
        $changes['@galleryImage@'] = $this->getGalleryImage();
    }

    private function getGalleryImage() {
        global $conf;
        $dir = $conf['mediadir'] . $this->getConf('processingImageRepository');
        $arrayDir = scandir($dir);
        $html = "";
        foreach ($arrayDir as $file) {
            if ($file == '.' | $file == '..') continue;
            $html .="<div><input type='checkbox' name=''/><img style='width:100px;heigth:100px;' src='".$dir.$file."' /></div>";
        }
//        closedir($dir);
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
