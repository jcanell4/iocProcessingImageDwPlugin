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
        $changes = array();
        $this->setParamsChanges($changes);
        $generateImageGenerator = new generateImage();
        $loadAlgorithmGenerator = new loadAlgorithm();


        $generateImageGenerator->setChanges($changes);
        $generateImageHtml = $generateImageGenerator->getHtml();


        $loadAlgorithmGenerator->setChanges($changes);
        $loadAlgorithmHtml = $loadAlgorithmGenerator->getHtml();
        
        $dojo = "<script>
    require([     'dojo/json'
                , 'dojo/_base/connect'
                , 'dojo/dom'
                , 'dojo/dom-construct'
                , 'dijit/registry'
                , 'dojo/parser'
                , 'dojo/domReady!'
                , 'dijit/form/Button'
                , 'dojox/form/Uploader'
                , 'dijit/layout/TabContainer'
                , 'dijit/layout/ContentPane'
                , 'dojo/domReady!'
    ], function(JSON, cn, dom, domConst, registry, parser) {
        parser.parse();

        var uploader = registry.byId('uploader');

        var handleUpload = function(upl, node) {

        };
        var handleDnD = function(domnode, uploader) {
            if (uploader.addDropTarget && uploader.uploadType == 'html5') {
                domConst.create('div', {innerHTML: 'Drag and Drop files to this fieldset'}, domnode, 'last');
                uploader.addDropTarget(domnode);
            }
        };

        cn.connect(registry.byId('remBtn'), 'onClick', uploader, 'reset');
        cn.connect(dojo.byId('f1'), 'submit', function(event) {
//            alert(uploader.getFileList()[0].name);
            var xhrArgs = {
                url: '/dokuwiki/lib/plugins/ajaxcommand/ajax.php',
                handleAs: 'json',
                content: {
                    call: 'save_pde_algorithm',
                    do: 'existsAlgorithm',
                    nameAlgorithm: uploader.getFileList()[0].name
                },
                load: function(data) {
                    alert(data[0].type);
                    alert(JSON.stringify(data[0].value))
                },
                error: function(error) {
                    alert(error);
                }
            }

            dojo.xhrGet(xhrArgs);
            cn.connect(uploader, 'onComplete', function(data) {
                //var json = JSON.parse(data, false);
                alert('complete');
                var div = domConst.create('div', {className: 'thumb'});
                var span = domConst.create('span', {className: 'thumbbk'}, div);
                span.innerHTML = '<p> type: ' + data[0].type + '</p>'
                        + '<p> value: ' + JSON.stringify(data[0].value) + '</p>'

                dom.byId('response').appendChild(div);
            });
        });
//        cn.connect(registry.byId('submit'), 'onClick', uploader, 'submit');
        handleUpload(uploader, dom.byId('response'));
        if (require.has('file-multiple')) {
            handleDnD(uploader.domNode.parentNode, uploader);
        }
    });
</script>  ";
        
        
        
        $html = ""
                . "<div style='width: 800; height: 650px;'>"
                . "<div data-dojo-type='dijit/layout/TabContainer' style='width:100%; height: 100%;'>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Generacio de imatges'  >Hola k ase</div>"
                . "<div data-dojo-type='dijit/layout/ContentPane' title='Carrega de algorismes' >" . $loadAlgorithmHtml . "</div>"
                

//                . "<div data-dojo-type='dijit/layout/ContentPane' title='Galeria de imatges' id='galleryImage'>".$generate_html."</div>"
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
        $urlsValue = DOKU_URL."lib/_java/pde/classes/";
        foreach ($arrayUrls as $key => $value) {
            $urlsValue .= self::$COMMA.DOKU_URL."lib/_java/lib/".$value; 
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
