<?php
/**
 * Default settings for the processingmanager plugin
 *
 * @author Daniel Criado Casas <dani.criado.casas@gmail.com> 
 */

//////APPLET
//com que tb s'utilitza en el ajaxcommand, s'hauria de possar en un conf mes general?
$conf['processingImageRepository']      = '/repository/pde/';  // repository for generated images using pde algorithms
//PARAMS
$conf['urlsParam']                      = 'urls';
$conf['CookieParam']                    = 'Cookie';
$conf['sectokParam']                    = 'sectok';
$conf['getPdeClassesURLParam']          = 'getPdeClassesURL';
$conf['nameSenderURLParam']             = 'nameSenderURL';
$conf['fileSenderURLParam']             = 'fileSenderURL';
//VALUES
$conf['appletHeight']                   = '600';
$conf['appletWidth']                    = '1000';
$conf['codeValue']                      = 'ioc.wiki.processingmanager.applet.ImageGeneratorApplet';
$conf['appletJarName']                  = 'lib/_java/processingManager.jar';//Perque amb ruta relativa?
$conf['getPdeClassesInfoCommand']       = 'get_pde_classes_info';
$conf['fileSenderCommand']              = 'save_unlinked_image&do=saveImage';
$conf['nameSenderCommand']              = 'save_unlinked_image&do=existsImage';
$conf['imageNameOption']                = 'imageName';
$conf['imageNameOptionValue']           = 'imageName';
$conf['nameOfFileOption']               = 'file';
$conf['urls']                           = 'lib/_java/pde/classes/';


//////IMPORTAR
$conf['appendAlgorithmParam']           = 'appendAlgorithm';
$conf['modifyAlgorithmParam']           = 'modifyAlgorithm';
$conf['existsAlgorithmParam']           = 'existsAlgorithm';
$conf['savePdeAlgorithmCommand']        = 'save_pde_algorithm';
$conf['pdeExtension']                   = '.pde';
