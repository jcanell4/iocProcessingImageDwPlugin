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

require_once DOKU_INC.'inc/common.php';
require_once DOKU_INC.'inc/pageutils.php';
require_once DOKU_INC.'inc/media.php';
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
        $generateImageGenerator = new generateImage($this);
        $loadAlgorithmGenerator = new loadAlgorithm($this);
        $galleryImageGenerator = new galleryImage($this);

        $changesGenerateImage = array();
        $generateImageGenerator->initChanges($changesGenerateImage);
        $generateImageGenerator->setChanges($changesGenerateImage);
        $generateImageHtml = $generateImageGenerator->getHtml();

        $changesLoadAlgorithm = array();
        $loadAlgorithmGenerator->initChanges($changesLoadAlgorithm);
        $loadAlgorithmGenerator->setChanges($changesLoadAlgorithm);
        $loadAlgorithmHtml = $loadAlgorithmGenerator->getHtml();

        $changesGalleryImage = array();
        $galleryImageGenerator->initChanges($changesGalleryImage);
        $galleryImageGenerator->setChanges($changesGalleryImage);
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

}
