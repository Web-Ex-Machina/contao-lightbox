<?php

/**
 * Module Custom Contao Lightbox for Contao Open Source CMS
 *
 * Copyright (c) 2015-2017 Web ex Machina
 *
 * @author Web ex Machina <http://www.webexmachina.fr>
 */

namespace WEM;


/**
 * Front end content element "hyperlink".
 *
 * @author Web ex Machina <http://www.webexmachina.fr>
 */
class ContentHyperlink extends \Contao\ContentHyperlink
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_hyperlink';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		if($this->add_cc_lightbox)
		{
			switch($this->add_cc_lightbox)
			{
				case 'addArticle':
					// $objArticle = \ArticleModel::findByPk($this->cc_lightbox_article);
					// $contentTag = $this->getArticle($objArticle);
					$dataTag = 'data-content="article-'.$this->cc_lightbox_article.'"';
				break;
				case 'addForm': 
					// $contentTag = $this->getForm($this->cc_lightbox_form);
					$dataTag = 'data-content="form-'.$this->cc_lightbox_form.'"';
				break;
				case 'addModule': 
					// $contentTag = $this->getModule($this->cc_lightbox_module);
					$dataTag = 'data-content="module-'.$this->cc_lightbox_module.'"';
				break;
				case 'addFile': 
					// $contentTag = $this->getFile($this->cc_lightbox_file);
					$dataTag = 'data-content="file-'.$this->cc_lightbox_file.'"';
				break;
				default: 
					$dataTag = '';
				break;
			}
			if($this->cc_lightbox_method)
			{
				$dataTag = $dataTag.' data-method="'.$this->cc_lightbox_method.'"';
			}
			if($this->cc_lightbox_reload)
			{
				$dataTag = $dataTag.' data-reload="true"';
			}
			if($this->cc_lightbox_listParams)
			{
				$array_params = unserialize($this->cc_lightbox_listParams);
				$params = ' data-params="';
				foreach ($array_params as $key => $value) {
					$params = $params.$value['label_param'].'='.$value['value_param'].',';
				}
				$params = rtrim($params, ",");
				$dataTag = $dataTag.$params.'"';
			}
			$this->Template->add_cc_lightbox = true;
			$this->Template->dataTag = $dataTag;
			$this->Template->contentTag = $contentTag;
		}

		return parent::compile();
	}
}
