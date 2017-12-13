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
 * Add an onload_callback
 */
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_wclb_content', 'adjustDcaByLightboxType');

/**
 * Add the Lightbox to palettes and subpalettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['wem-contao-lightbox'] = '
	{type_legend},type;
	{wclb_global_legend},wclb_type,wclb_content;
	{wclb_button_legend},wclb_buttonText,wclb_buttonTitle,wclb_buttonCssID,wclb_buttonTemplate;
	{wclb_lightbox_legend},wclb_lightboxCssID,wclb_lightboxTemplate,wclb_lightboxReload,wclb_lightboxNoClose,wclb_lightboxDestroy,wclb_lightboxOpenAuto
';

/**
 * Add the lightbox fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_type'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_type'],
	'default'                 => 'content',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'        		  => array('content', 'form', 'module', 'custom'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_content']['wclb_type'],
	'eval'                    => array('chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w100'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
// Nota : This field will be rebuild by onload_callback, because the UX will be different depends on the type above
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_content'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'               	  => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'tl_class'=>'w50 wizard'),
	'sql'                     => "mediumtext NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_buttonText'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_buttonText'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50', 'mandatory'=>true),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_buttonTitle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_buttonTitle'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_buttonCssID'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_buttonCssID'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_buttonTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_buttonTemplate'],
	'default'                 => 'wclb_button_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_wclb_content', 'getLightboxButtonTemplates'),
	'eval'                    => array('chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_lightboxCssID'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_lightboxCssID'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_lightboxTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_lightboxTemplate'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_wclb_content', 'getLightboxTemplates'),
	'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_lightboxReload'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_lightboxReload'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_lightboxNoClose'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_lightboxNoClose'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_lightboxDestroy'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_lightboxDestroy'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_lightboxOpenAuto'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wclb_lightboxOpenAuto'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Web ex Machina <contact@webexmachina.fr>
 */
class tl_wclb_content extends tl_content
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Format 
	 * @param  [type] $objDc [description]
	 * @return [type]        [description]
	 */
	public function adjustDcaByLightboxType($objDc)
	{
		// First, get the content
		$objItem = \ContentModel::findByPk($objDc->id);

		if($objDc->type != "wem-contao-lightbox")
			return;

		switch($objItem->wclb_type)
		{
			case "form":
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['label'] = $GLOBALS['TL_LANG']['tl_content']['wclb_content_form'];
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['inputType'] = 'select';
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['options_callback'] = array('tl_content', 'getForms');
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['eval'] = array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50 wizard');
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['wizard'] = array(array('tl_content', 'editForm'));
			break;

			case "module":
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['label'] = $GLOBALS['TL_LANG']['tl_content']['wclb_content_module'];
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['inputType'] = 'select';
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['options_callback'] = array('tl_content', 'getModules');
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['eval'] = array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50 wizard');
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['wizard'] = array(array('tl_content', 'editModule'));
			break;

			case "custom":
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['label'] = $GLOBALS['TL_LANG']['tl_content']['wclb_content_custom'];
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['inputType'] = 'textarea';
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['eval'] = array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true);
				$GLOBALS['TL_DCA']['tl_content']['fields']['wclb_content']['explanation'] = 'insertTags';
			break;
		}
	}

	/**
	 * Return all button templates as array
	 *
	 * @return array
	 */
	public function getLightboxButtonTemplates()
	{
		return $this->getTemplateGroup('wclb_button_');
	}

	/**
	 * Return all button templates as array
	 *
	 * @return array
	 */
	public function getLightboxTemplates()
	{
		return $this->getTemplateGroup('wclb_lightbox_');
	}
}