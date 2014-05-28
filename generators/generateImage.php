<?php

if(!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");


require_once DOKU_PROCESSING.'abstractGenerateHtml.php';

class generateImage extends abstractGenerateHtml {
    
    function generateImage() {
        $this->setFile(DOKU_PROCESSING.'templates/generateImage.html');
    }
    
    function setChanges($changes) {
        $changes['@appletHeight@'] = '600';
        $changes['@appletWidth@'] = '800';
        $changes['@codeParam@'] = 'ioc.wiki.processingmanager.applet.ImageGeneratorApplet';
        $changes['@archiveParam@'] = './lib/_java/javatest/processingManager.jar';
        $changes['@urlsParam@'] = "http://localhost/dokuwiki/lib/_java/pde/classes/,"
                    ."http://localhost/dokuwiki/lib/_java/lib/core.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/gluegen-rt-natives-linux-i586.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/gluegen-rt.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/itext.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/jogl-all-natives-linux-i586.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/jogl-all.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/pdf.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/commons-codec-1.9.jar,"
                    ."http://localhost/dokuwiki/lib/_java/lib/javax.json-1.0.2.jar";
        //$changes['@CookieParam@'] = ;
        //$changes['@sectokParam@'] = ;
        $changes['@getPdeClassesURLParam@'] = /*DOKU_URL.*/"lib/plugins/ajaxcommand/ajax.php?call=get_pde_classes_info";
        $changes['@getCommandToSaveParam@'] = /*DOKU_URL.*/"lib/plugins/ajaxcommand/ajax.php?call=auth_commandreport";            
        parent::setChanges($changes);
    }
    
}
?>

