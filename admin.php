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
        echo("<script>console.log('PHP: HTML');</script>");
        $changes = array("dani"=>"dani");
        echo("<script>console.log('PHP: HTML1');</script>");
        $this->setParamsChanges($changes);
        echo("<script>console.log('PHP: HTML2');</script>");
        $generateImageGenerator = new generateImage();
        $loadAlgorithmGenerator = new loadAlgorithm();


        $generateImageGenerator->setChanges($changes);
        $generateImageHtml = $generateImageGenerator->getHtml();


        $loadAlgorithmGenerator->setChanges($changes);
        $loadAlgorithmHtml = $loadAlgorithmGenerator->getHtml();
        
        echo("<script>console.log('PHP: ".$generateImageHtml."');</script>");
//        echo("<script>console.log('PHP: ".$loadAlgorithmHtml."');</script>");



        $dojo = "<script type='text/javascript'>"
                . "require(["
                . "'dojo/parser', "
                . "'dijit/layout/TabContainer', "
                . "'dijit/layout/ContentPane'"
                . "]);</script>";
        $html = ""
                . "<div style='width: 800; height: 650px;'>"
                . "<div data-dojo-type='dijit/layout/TabContainer' style='width:100%; height: 100%;'>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Generacio de imatges' id='generateImage' >" . $generateImageHtml . "</div>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Carrega de algorismes' id='loadAlgorithm'>" . $loadAlgorithmHtml . "</div>"
//                . "<div data-dojo-type='dijit/layout/ContentPane' title='Galeria de imatges' id='galleryImage'>".$generate_html."</div>"
                . "</div>"
                . "</div>"
                . "";
        echo $dojo . $html;
    }

    private function setParamsChanges(& $changes) {
        echo("<script>console.log('PHP: HTML3');</script>");
        $this->setGenerateImageChanges($changes);
        echo("<script>console.log('PHP: HTML4');</script>");
        $this->setLoadAlgorithmChanges($changes);
        echo("<script>console.log('PHP: HTML5');</script>");
        $this->setGalleryImageChanges($changes);
        echo("<script>console.log('PHP: HTML6');</script>");
    }

    private function setGenerateImageChanges(& $changes) {
        //PARAMS
        $changes['@urlsParam@'] = $this->getConf('urlsParam');
        $changes['@CookieParam@'] = $this->getConf('CookieParam');
        $changes['@sectokParam@'] = $this->getConf('sectokParam');
        $changes['@getPdeClassesURLParam@'] = $this->getConf('getPdeClassesURLParam');

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
    }

    private function setLoadAlgorithmChanges(& $changes) {
        
    }

    private function setGalleryImageChanges(& $changes) {
        
    }

    private function getUrlsValue() {
        $urls = $this->getConf('urls');
        $arrayUrls = split(',', $urls);
        $urlsValue = "";
        foreach ($arrayUrls as $key => $value) {
            $urlsValue .= DOKU_URL."lib/_java/pde/classes/".$value.self::$COMMA; 
        }
        $urlsValue = substr($urlsValue, 0,-1);//Treure la ultima comma.
//        $urlsValue = DOKU_JAVA_PDE_CLASSES . self::$COMMA
//                . DOKU_JAVA_LIB . "core.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "gluegen-rt-natives-linux-i586.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "gluegen-rt.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "itext.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "jogl-all-natives-linux-i586.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "jogl-all.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "pdf.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "commons-codec-1.9.jar" . self::$COMMA
//                . DOKU_JAVA_LIB . "javax.json-1.0.2.jar";
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
