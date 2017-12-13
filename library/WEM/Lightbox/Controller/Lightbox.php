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
					$strContent = parent::getForm($objItem->wclb_content);
				break;

				case 'module':
					$strContent = parent::getFrontendModule($objItem->wclb_content);
				break;

				case 'custom':
					$strContent = parent::replaceInsertTags($objItem->wclb_content);
				break;

				// TODO : Find a way to handle generic DCA Picker tags
				default:
					preg_match_all('/{{(.*)::(.*)}}/', $objItem->wclb_content, $arrTag, PREG_SET_ORDER, 0);
					$arrTag = $arrTag[0];
					
					switch($arrTag[1])
					{
						case 'link_url':
							if(!$objItem = \PageModel::findByPk($arrTag[2]))
								throw new Exception(sprintf("The page item %s doesn't exist", $arrTag[2]));

							$strContent = '<iframe src="'.$objItem->getFrontendUrl().'" width="100%" height="90vh"></iframe>';
						break;

						case 'file':
							if(!$objItem = \FilesModel::findByUuid($arrTag[2]))
								throw new Exception(sprintf("The file item %s doesn't exist", $arrTag[2]));

							$arrMime = explode("/", $GLOBALS['TL_MIME'][$objItem->extension][0]);

							switch($arrMime[0])
							{
								case 'image':
									$strContent	= sprintf('<img src="%s" alt="%s" />', $objItem->path, $objItem->name);
								break;

								case 'video':
									$strContent	= sprintf('<video src="%s" controls>%s</video>', $objItem->path, $objItem->name);
								break;

								case 'text':
									$strContent	= file_get_contents($objItem->path);
								break;

								case 'application':
									$strContent	= '<iframe src="'.$objItem->path.'" width="100%" height="90vh"></iframe>';
								break;

								default:
									throw new Exception(sprintf("The file extension %s (%s) isn't handle yet", $objItem->extension, $arrMime[0]));
							}
							
						break;

						case 'news_url':
							if(!$objItem = \NewsModel::findByPk($arrTag[2]))
								throw new Exception(sprintf("The news item %s doesn't exist", $arrTag[2]));
							
							\Input::setGet('items', $objItem->alias);	

							$objModule = new \ModuleModel();
							$objModule->type = "newsreader";
							$objModule->news_template = "news_full";
							$objModule->news_archives = serialize([0=>$objItem->pid]);
							
							$strContent = parent::getFrontendModule($objModule);
						break;

						case 'event_url':
							if(!$objItem = \CalendarEventsModel::findByPk($arrTag[2]))
								throw new Exception(sprintf("The event item %s doesn't exist", $arrTag[2]));
							
							\Input::setGet('events', $objItem->alias);	

							$objModule = new \ModuleModel();
							$objModule->type = "eventreader";
							$objModule->cal_template = "event_full";
							$objModule->cal_calendar = serialize([0=>$objItem->pid]);
							
							$strContent = parent::getFrontendModule($objModule);
						break;

						case 'faq_url':
							if(!$objItem = \FaqModel::findByPk($arrTag[2]))
								throw new Exception(sprintf("The faq item %s doesn't exist", $arrTag[2]));
							
							\Input::setGet('items', $objItem->alias);	

							$objModule = new \ModuleModel();
							$objModule->type = "faqreader";
							$objModule->faq_categories = serialize([0=>$objItem->pid]);
							
							$strContent = parent::getFrontendModule($objModule);
						break;

						case 'article_url':
							if(!$objItem = \ArticleModel::findByPk($arrTag[2]))
								throw new Exception(sprintf("The article item %s doesn't exist", $arrTag[2]));

							$strContent = parent::getArticle($objItem);
						break;

						default:
							throw new Exception(sprintf("This tag %s isn't known !", $arrTag[1]));
					}
			}

			// Try a generic parse if there is no answer
			if(!$strContent)
				$strContent = parent::replaceInsertTags($objItem->wclb_content);

			// Just in case
			if(!$strContent)
				throw new Exception(sprintf("No content found for Content type %s and Tag/ID %s", $objItem->wclb_type, $objItem->wclb_content));

			return $strContent;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}