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
 * Add Lightbox to Content Elements
 */
$GLOBALS['TL_CTE']['includes']['wem-contao-lightbox'] = "\WEM\Lightbox\Element\Lightbox";

if(TL_MODE == "FE")
	$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('WEM\Lightbox\Hooks', 'handle');