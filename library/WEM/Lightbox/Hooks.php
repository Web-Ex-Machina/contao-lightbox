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

use Exception;
use Contao\Controller;
use Contao\ArticleModel;
use Contao\Session;
use Contao\System;
use Contao\RequestToken;
use Haste\Http\Response\HtmlResponse;
use Haste\Input\Input;
use WEM\Lightbox\Controller\Lightbox;

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
				$strReturn = "";
				$arrContent = Input::post('value');
				$strMethod  = Input::post('method');
				$arrParams  = Input::post('params');

				// Build params
				if(is_array($arrParams) && !empty($arrParams))
				{
					foreach($arrParams as $strKey => $strParam)
					{
						$arrParam = explode('=', html_entity_decode($strParam, ENT_NOQUOTES));
						
						if($strMethod == "GET" || $strMethod == "get")
							Input::setGet($arrParam[0], $arrParam[1]);
						else
							Input::setPost($arrParam[0], $arrParam[1]);
					}
				}

				// Fetch the content
				//$objItem = ContentModel::findByPk($arrContent[1]);
				//$strReturn .= Lightbox::fetchContent($objItem);
				//$objSession->set("wem_contao_lightbox_".$arrContent[0], $arrContent[1]);

				// Retrieve Javascript Files used in contents
				/*if(is_array($GLOBALS['TL_JAVASCRIPT']) && !empty($GLOBALS['TL_JAVASCRIPT']))
					foreach($GLOBALS['TL_JAVASCRIPT'] as $strJs)
						$strReturn .= '<script src="'.str_replace("|static", "", $strJs).'"></script>';*/

				if($strReturn == '')
					throw new Exception(sprintf($GLOBALS['TL_LANG']['WEM_CONTAO_LIGHTBOX']['ERR']['noContent'], $arrContent[0], $arrContent[1]));
			}
			catch(Exception $e)
			{
				$strReturn = "Lightbox error : ".$e->getMessage();
				System::log($strReturn, __METHOD__, "WEM_CONTAO_LIGHTBOX");
			}

			echo $strReturn; die;

			// Sent HTML anyway
			$objResponse = new HtmlResponse( $strReturn );
			$objResponse->send();
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
				$objCombiner->add('system/modules/wem-contao-lightbox/assets/wem_contao_lightbox.js', time());
				$objLayout->script .= '<script type="text/javascript">var rt = "'.RequestToken::get().'"</script>';
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
			catch(Exception $e)
			{
				System::log("Lightbox error : ".$e->getMessage(), __METHOD__, "WEM_CONTAO_LIGHTBOX");
			}
		}
	}
}