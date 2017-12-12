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

namespace WEM\Lightbox\Controller;

use Exception;
use Contao\Controller;
use Contao\ContentModel;
use Haste\Util\StringUtil;

/**
 * Handle generic functions for lightbox extension
 *
 * @author Web ex Machina <contact@webexmachina.fr>
 */
class Lightbox extends Controller
{
	
	public static function fetchContent(ContentModel $objItem)
	{
		try
		{
			// Check if ContentModel is a lightbox
			if($objItem->type != "wem-contao-lightbox")
				throw new Exception("Must be wem-contao-lightbox Contao Element");

			// Depends on the content type
			switch($objItem->wclb_type)
			{
				case 'form':
					$strContent = Controller::getForm($objItem->wclb_content);
				break;

				case 'module':
					$strContent = Controller::getFrontendModule($objItem->wclb_content);
				break;

				case 'custom':
					$strContent = StringUtil::recursiveReplaceTokensAndTags($objItem->wclb_content);
				break;

				default:
					$strContent = StringUtil::recursiveReplaceTokensAndTags($objItem->wclb_content);
			}

			// Just in case
			if(!$strContent)
				throw new Exception("No content found for the Contao Element ID ".$objItem->id);

			return $strContent;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}