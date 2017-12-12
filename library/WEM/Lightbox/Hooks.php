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

namespace WEM\Lightbox;

use Contao\Controller;
use Contao\ArticleModel;
use Contao\Session;
use Contao\System;

use Haste\Http\Response\HtmlResponse;
use Haste\Input\Input;

/**
 * Handle hooks for lightbox extension
 *
 * @author Web ex Machina <contact@webexmachina.fr>
 */
class Hooks extends Controller
{
	/**
	 * Handle Custom Lightbox Behaviour
	 * @param Object
	 * @param Object
	 * @param Object
	 */
	public function handle($objPage, $objLayout, $objPageRegular)
	{
		// Get the session
		$objSession = Session::getInstance();

		if(Input::post('TL_LIGHTBOX') && Input::post('value') != '')
		{
			try
			{		
				// Get the session and the params
				$arrContent = Input::post('value');
				$strMethod  = Input::post('method');
				$arrParams  = Input::post('params');

				if(isset($arrParams) && $arrParams != null)
				{
					foreach($arrParams as $strKey => $strParam)
					{
						$arrParam = explode('=', html_entity_decode($strParam, ENT_NOQUOTES));
						if($strMethod == "GET" || $strMethod == "get")
						{
							Input::setGet($arrParam[0], $arrParam[1]);
						}
						else
						{
							Input::setPost($arrParam[0], $arrParam[1]);
						}
					}
				}

				switch($arrContent[0])
				{
					case 'article':
						$objSession->set("cc_lightbox_article", $arrContent[1]);
						$objArticle = ArticleModel::findByPk($arrContent[1]);
						$strReturn = Controller::getArticle($objArticle);
					break;

					case 'form':
						$objSession->set("cc_lightbox_form", $arrContent[1]);
						$strReturn = Controller::getForm($arrContent[1]);
					break;

					case 'module':
						$objSession->set("cc_lightbox_module", $arrContent[1]);
						$strReturn = Controller::getFrontendModule($arrContent[1]);
					break;

					case 'custom':

					break;

					default:
				}

				foreach($GLOBALS['TL_JAVASCRIPT'] as $strJs)
				{
					$strReturn .= '<script src="'.str_replace("|static", "", $strJs).'"></script>';
				}

				// Fallback if we don't have a content - Issue #2 -> http://gitlab.webexmachina.fr/modules-contao/custom-lightbox/issues/2
				if($strReturn == '')
				{
					throw new \Exception(sprintf($GLOBALS['TL_LANG']['CC_LIGHTBOX']['ERR']['noContent'], $arrContent[0], $arrContent[1]));
				}
			}
			catch(\Exception $e)
			{
				$strReturn = $e->getMessage();
				System::log("Lightbox error : ".$e->getMessage(), __METHOD__, "CC_LIGHTBOX");
			}

			// Sent HTML anyway
			if($strReturn)
			{
				$objResponse = new HtmlResponse( $strReturn );
				$objResponse->send();
			}
		}
		else
		{
			try
			{
			    // Add CSS to the page
				$objCombiner = new \Combiner();
				$objCombiner->add('system/modules/wem-contao-lightbox/assets/wem_contao_lightbox.scss', 1);
				$objLayout->head .= '<link rel="stylesheet" href="'.$objCombiner->getCombinedFile().'">';

				// Add JS to the page
				$objCombiner = new \Combiner();
				$objCombiner->add('system/modules/wem-contao-lightbox/assets/wem_contao_lightbox.js', 1);
				$objLayout->script .= '<script type="text/javascript" src="'.$objCombiner->getCombinedFile().'"></script>';
/*
				// Handle lightbox modules loaded
				if($objSession->get("cc_lightbox_module"))
				{
					$idModule = $objSession->get("cc_lightbox_module");
					$objSession->remove("cc_lightbox_module");
					Controller::getFrontendModule($idModule);
				}
				// Handle lightbox articles loaded
				else if($objSession->get("cc_lightbox_article"))
				{
					$idArticle = $objSession->get("cc_lightbox_article");
					$objSession->remove("cc_lightbox_article");
					$objArticle = ArticleModel::findByPk($idArticle);
					Controller::getArticle($objArticle);
				}
				// Handle lightbox forms submits
				else if($objSession->get("cc_lightbox_form"))
				{
					$idForm = $objSession->get("cc_lightbox_form");
					$objSession->remove("cc_lightbox_form");
					Controller::getForm($idForm);
				}*/
			}
			catch(\Exception $e)
			{
				System::log("Lightbox error : ".$e->getMessage(), __METHOD__, "CC_LIGHTBOX");
			}
		}
	}
}