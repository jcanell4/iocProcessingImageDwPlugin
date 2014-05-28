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
if(!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");

require_once DOKU_INC.'inc/common.php';
require_once DOKU_PROCESSING.'generators/generateImage.php';

/**
 * All DokuWiki plugins to extend the admin function
 * need to inherit from this class
 */
class admin_plugin_processingmanager extends DokuWiki_Admin_Plugin {

    /**
     * Constructor
     */
    function admin_plugin_processingmanager(){
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

        return $this->getLang('menu').' '.$this->disabled;
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
        $generate = new generateImage();
        $generate->setChanges(array("@dani@"=>"dani"));
        $generate_html = $generate->getHtml();
        $dojo = 
        "<script type='text/javascript'>"
        ."require(["
        . "'dojo/parser', "
        . "'dijit/layout/TabContainer', "
        . "'dijit/layout/ContentPane'"
        . "]);</script>";
        $html = ""
        ."<div style='width: 800; height: 600px;'>"
            . "<div data-dojo-type='dijit/layout/TabContainer' data-dojo-config=\"local='ca'\" style='width:100%; height: 100%;'>"
                . "<div data-dojo-type='dijit/layout/ContentPane' data-dojo-config=\"local='ca'\" title='Generació de imatges' id='generateImage' >".$generate_html."</div>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Càrrega de algorismes' id='loadAlgorithm'>".$generate_html."</div>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Galeria de imatges' id='galleryImage'>".$generate_html."</div>"
            . "</div>"
        . "</div>"
        . "";
        echo $dojo . $html;
    }    
}
