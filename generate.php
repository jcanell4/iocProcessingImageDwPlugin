<?php
global $conf;
global $_COOKIE;
echo $conf['plugin']['processingmanager']['typeAttr'];//No funciona
echo getSecurityToken();//No funciona
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

$urls= "http://localhost/dokuwiki/lib/_java/pde/classes/,"
        ."http://localhost/dokuwiki/lib/_java/lib/core.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/gluegen-rt-natives-linux-i586.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/gluegen-rt.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/itext.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/jogl-all-natives-linux-i586.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/jogl-all.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/pdf.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/commons-codec-1.9.jar,"
        ."http://localhost/dokuwiki/lib/_java/lib/javax.json-1.0.2.jar";

$applet = "
    <div id='generate'>
<object type='application/x-java-applet' height='600' width='1000'>"
  ."<param name='code' value='ioc.wiki.processingmanager.applet.ImageGeneratorApplet' />"
  ."<param name='archive' value='./lib/_java/javatest/processingManager.jar' />"
  ."<param name='urls' value='".$urls."'"
  . "<param name='Cookie' value='".$strCookie."' />"
//  . "<param name='sectok' value='". getSecurityToken() . "' />"
  . "<param name='getPdeClassesURL' value='".DOKU_URL."lib/plugins/ajaxcommand/ajax.php?call=get_pde_classes_info'/>"
  
//  <param name='commandToSave' value='".DOKU_URL."lib/plugins/ajaxcommand/ajax.php?call=auth_commandreport'/>"

        . "</object></div>";
echo $applet;
?>
