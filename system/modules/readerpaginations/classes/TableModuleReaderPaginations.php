<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2013
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class file for tl_module
 * TableModule
 */
class TableModuleReaderPaginations extends \Backend 
{
	/**
	 * Modify palette depending on module type
	 * @param object DataContainer
	 * @return object DataContainer
	 */
	public function modifyPalette(\DataContainer $objDC)
	{
		$objDatabase = \Database::getInstance();
		$objActiveRecord = $objDatabase->prepare("SELECT * FROM ".$objDC->table." WHERE id=?")->limit(1)->execute($objDC->id);
		
		switch($objActiveRecord->type)
		{
			case 'eventreader':
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format']['default'] = 'cal_all';
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format']['options'] = array('cal_month', 'cal_year', 'cal_all');
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format']['reference'] = &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'];
				break;
			case 'newsreader':
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format']['default'] = 'news_all';
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format']['options'] = array('news_month', 'news_year', 'news_all');
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format']['reference'] = &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'];
				break;
			default: break;
		}
		
		return $objDC;
	}
}