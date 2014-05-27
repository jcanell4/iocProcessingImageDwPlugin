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

require_once DOKU_INC.'inc/common.php';
require_once DOKU_INC.'lib/plugins/processingmanager/generateImage.php';

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
        $generate_html = $generate->getFileString();
        //print $generate_html;
        $params = array("@dani@"=>"dani");
        $generate_html = $generate->remplacements($params, $generate_html);
        $html = "<div style='width: 800; height: 600px'>"
                . "<div id='generate'>".$generate_html."</div>"
                . "</div>";
        echo $html;
    }    
}
