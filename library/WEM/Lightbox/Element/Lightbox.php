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

namespace WEM\Lightbox\Element;

use Contao\ContentElement;
use Contao\FrontendTemplate;

use WEM\Lightbox\Controller\Lightbox as LightboxController;

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
	protected $strTemplate = 'wclb_button_default';


	/**
	 * Show the raw code in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$return  = '## LIGHTBOX - '. $GLOBALS['TL_LANG']['tl_content']['wclb_type'][$this->wclb_type] .' ##';
			$return .= '<br />'.LightboxController::fetchContent(\ContentModel::findByPk($this->id));
			return $return;
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		try
		{
			// Override default template
			if($this->wclb_buttonTemplate && $this->wclb_buttonTemplate != $this->strTemplate)
				$this->Template = new FrontendTemplate($this->wclb_buttonTemplate);

			// Parse ID/CSS field
			$this->Template->strCSS = 'link_custom_lightbox';
			if(is_array(deserialize($this->wclb_buttonCssID)))
			{
				$arrCssID = deserialize($this->wclb_buttonCssID);

				// Send the button ID to Template
				if($arrCssID[0])
					$this->Template->strID = $arrCssID[0];

				if($arrCssID[1])
					$this->Template->strCSS .= ' '.trim($arrCssID[1]);
			}

			// Parse lightbox attributes
			if(!$this->wclb_method)
				$this->wclb_method = "POST";
			
			$arrParams[] = sprintf('data-method="%s"', $this->wclb_method);

			// Use the content ID, because tags will be parsed in the attributes, and we don't want that, we will parse them only if the lightbox is called
			$arrParams[] = sprintf('data-content="%s-%s"', $this->wclb_type, $this->id);

			// Handle lightbox settings
			if($this->wclb_lightboxReload)
				$arrParams[] = 'data-reload="true"';
			if($this->wclb_lightboxNoClose)
				$arrParams[] = 'data-noclose="true"';
			if($this->wclb_lightboxDestroy)
				$arrParams[] = 'data-destroy="true"';
			if($this->wclb_lightboxOpenAuto)
				$arrParams[] = 'data-openauto="true"';

			// Parse and send lightbox attributes to template
			$this->Template->text = $this->wclb_buttonText;
			$this->Template->title = $this->wclb_buttonTitle;
			$this->Template->attributes = implode(' ', $arrParams);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}