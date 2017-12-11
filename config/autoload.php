<?php

/**
 * Lightbox extension for Contao Open Source CMS
 *
 * Copyright (c) 2015-2018 Web ex Machina
 *
 * @package 	webexmachina\contao-lightbox
 * @link 		https://github.com/webexmachina/contao-lightbox
 * @author 		Web ex Machina <contact@webexmachina.fr>
 */

/**
 * Register namespace if loaded from extension repository
 */
if (class_exists('NamespaceClassLoader')) {
    NamespaceClassLoader::add('WEM', 'system/modules/wem-contao-lightbox/library');
}