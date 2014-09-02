<?php

if(!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
if (!defined('DOKU_PROCESSING'))
    define('DOKU_PROCESSING', DOKU_PLUGIN . "processingmanager/");


require_once DOKU_PROCESSING.'abstractGenerateHtml.php';

class galleryImage extends abstractGenerateHtml {
    
    function galleryImage($adminPlugin) {
        $this->setFile(DOKU_PROCESSING.'templates/galleryImage.html');
        $this->adminPlugin = $adminPlugin;
    }
    
    
    function initChanges(& $changes) {
        $changes['@galleryImage@'] = $this->getGalleryImage();
        $changes['@unexpectedError@'] = $this->adminPlugin->getLang('unexpectedError');
        $changes['@emptyNameImageError@'] = $this->adminPlugin->getLang('emptyNameImageError');
        $changes['@noImageSelectError@'] = $this->adminPlugin->getLang('noImageSelectError');
        $changes['@submitTitle@'] = $this->adminPlugin->getLang('submitTitle');
        $changes['@copyImage@'] = $this->adminPlugin->getLang('copyImage');
        return $changes;
    }
    
    private function getGalleryImage() {
        $ns = str_replace("/", ":", $this->adminPlugin->getConf('processingImageRepository'));
        $dir = mediaFN($ns);
        $arrayDir = scandir($dir);
        $html = "";
        if (count($arrayDir) > 2) {
            unset($arrayDir[0]);
            unset($arrayDir[1]);
            $arrayDir = array_values($arrayDir);
            foreach ($arrayDir as $file) {
                $name = substr($file, 0, -4); //Li treu la extensio .pde
                $url = $this->getMediaUrl($ns . $file );
                $html .="<div class='iGallery'>"
                        . "<div class='iCheckbox'><input type='radio' name='checkImage' value='" . $file . "'/></div>"
                        . "<img src='" . $url. "' />"
                        . "<div class='iLink'><a href='" . $url . "' target='_blank' title='Veure original'>$name</a></div></div>";
            }
        } else {
            $html = "<div>@noPdeImage@</div>";
        }
        return $html;
    }
    
    public function getMediaUrl($id){
        $size = media_image_preview_size($id, false, false);
        if ($size) {
            $more = array();
            $more['t'] = @filemtime(mediaFN($id));
            $more['w'] = $size[0];
            $more['h'] = $size[1];
            $src = ml($id, $more);
        }else{
            $src = ml($id,"",true);
        }
        return $src;
    }
}
?>

