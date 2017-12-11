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

namespace WEM\Lightbox\Elements;

use Contao\ContentElement;

/**
 * Front end content element "lightbox".
 *
 * @author Web ex Machina <contact@webexmachina.fr>
 */
class Lightbox extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'wem_ce_lightbox';


	/**
	 * Show the raw code in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$return = '## LIGHTBOX - '. $GLOBALS['TL_LANG']['tl_content']['wclb_type'][$this->wclb_type] .'';

			return $return;
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if ($this->highlight == 'C#')
		{
			$this->highlight = 'csharp';
		}
		elseif ($this->highlight == 'C++')
		{
			$this->highlight = 'cpp';
		}

		$this->Template->code = htmlspecialchars($this->code);
		$this->Template->cssClass = strtolower($this->highlight) ?: 'nohighlight';
	}
}