<?php

/**
 * Module Custom Contao Lightbox for Contao Open Source CMS
 *
 * Copyright (c) 2015-2017 Web ex Machina
 *
 * @author Web ex Machina <http://www.webexmachina.fr>
 */

/**
 * Add new Namespace
 */
ClassLoader::addNamespace('WEM');

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'WEM\CustomLightbox'			=> 'system/modules/wem-custom-lightbox/classes/CustomLightbox.php',

	// Elements
	'WEM\ContentHyperlink'			=> 'system/modules/wem-custom-lightbox/elements/ContentHyperlink.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_hyperlink'			    => 'system/modules/wem-custom-lightbox/templates/elements/',
));
