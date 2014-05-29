<?php
/**
 * Default settings for the processingmanager plugin
 *
 * @author Daniel Criado Casas <dani.criado.casas@gmail.com> 
 */

//PARAMS
$conf['urlsParam']                      = 'urls';
$conf['CookieParam']                    = 'Cookie';
$conf['sectokParam']                    = 'sectok';
$conf['getPdeClassesURLParam']          = 'getPdeClassesURL';
//VALUES
$conf['appletHeight']                   = '600';
$conf['appletWidth']                    = '800';
$conf['codeValue']                      = 'ioc.wiki.processingmanager.applet.ImageGeneratorApplet';
$conf['appletJarName']                  = 'lib/_java/processingManager.jar';//Perque amb ruta relativa?
$conf['getPdeClassesInfoCommand']       = 'get_pde_classes_info';
$conf['fileSenderCommand']              = 'save_unlinked_image&do=saveImage';
$conf['nameSenderCommand']              = 'save_unlinked_image&do=existsImage';
