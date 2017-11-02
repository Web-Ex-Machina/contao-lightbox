<?php

/**
 * Module Custom Contao Lightbox for Contao Open Source CMS
 *
 * Copyright (c) 2015-2017 Web ex Machina
 *
 * @author Web ex Machina <http://www.webexmachina.fr>
 */


/**
 * Table tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['hyperlink'] = str_replace
(
	',rel', 
	',rel,add_cc_lightbox', 
	$GLOBALS['TL_DCA']['tl_content']['palettes']['hyperlink']
);


$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'add_cc_lightbox';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['add_cc_lightbox_addArticle'] = 'cc_lightbox_article,cc_lightbox_reload';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['add_cc_lightbox_addForm'] = 'cc_lightbox_form,cc_lightbox_reload';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['add_cc_lightbox_addModule'] = 'cc_lightbox_module,cc_lightbox_method,cc_lightbox_listParams,cc_lightbox_reload';


$GLOBALS['TL_DCA']['tl_content']['fields']['add_cc_lightbox'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['add_cc_lightbox'],
	'inputType'               => 'select',
	'options'       		  => array('addArticle', 'addForm', 'addModule'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
	'eval'                    => array('chosen'=>true, 'submitOnChange'=>true, 'includeBlankOption'=>true, 'tl_class'=>'clr w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['cc_lightbox_form'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['form'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content', 'getForms'),
	'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'clr w50'),
	'wizard' => array
	(
		array('tl_content', 'editForm')
	),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['cc_lightbox_article'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['article'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content', 'getArticles'),
	'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'clr w50'),
	'wizard' => array
	(
		array('tl_content', 'editArticle')
	),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);


$GLOBALS['TL_DCA']['tl_content']['fields']['cc_lightbox_module'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['module'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content', 'getModules'),
	'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'clr w50'),
	'wizard' => array
	(
		array('tl_content', 'editModule')
	),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);


$GLOBALS['TL_DCA']['tl_content']['fields']['cc_lightbox_reload'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['cc_lightbox_reload'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>' clr w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);


$GLOBALS['TL_DCA']['tl_content']['fields']['cc_lightbox_method'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['cc_lightbox_method'],
	'inputType'               => 'select',
	'default'				  => 'POST',
	'options'       		  => array('POST', 'GET'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
	'eval'                    => array( 'tl_class'=>' w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['cc_lightbox_listParams'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['cc_lightbox_listParams'],
	'exclude'                 => true,
	'inputType'               => 'multiColumnWizard',
	'eval'                    => array('tl_class'=>'clr', 'columnsCallback'=>array('tl_content_cc_lightbox', 'getColumnsWizard', 'tl_class'=>'m12')),
	'sql'                     => "blob NULL"
);


class tl_content_cc_lightbox extends tl_content
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
	 * Add an error message
	 *
	 * @param string $strMessage The error message
	 *
	 * @deprecated Use Message::addError() instead
	 */
	public function displayDependancy()
	{
		if(!$this->classFileExists('MultiColumnWizard')) 
		{ 
			$this->addErrorMessage("Ce module nécessite l'extension <a href='contao/main.php?do=repository_manager&install=MultiColumnWizard.30030039' style='color: blue'>MultiColumnWizard</a>.");
		}
	}

	/**
	* return an array containing columns for multiColumnWizard field
	*/
	public function getColumnsWizard($dc)
	{
		$column_series = [];
		$column_series['label_param'] = array
		(
			'label' 		=> &$GLOBALS['TL_LANG']['tl_content']['label_param'],
			'inputType' 		=> 'text',
			'eval'                  => array('rgxp'=>'alnum', 'style'=>'width:150px;background:#ddd')
		);
		$column_series['value_param'] = array
		(
			'label' 		=> &$GLOBALS['TL_LANG']['tl_content']['value_param'],
			'inputType' 		=> 'text',
			'eval'                  => array('rgxp'=>'alnum', 'style'=>'width:150px;background:#ddd')
		);
		
		return $column_series;
	}

}