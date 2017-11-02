<?php

/**
 * Module Custom Contao Lightbox for Contao Open Source CMS
 *
 * Copyright (c) 2016-2017 Web ex Machina
 *
 * @author Web ex Machina <http://www.webexmachina.fr>
 */

/**
 * Register hook to add specifics files in the template (only in FE)
 */
if(TL_MODE == "FE")
{
	$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('WEM\CustomLightbox', 'handle');
}
