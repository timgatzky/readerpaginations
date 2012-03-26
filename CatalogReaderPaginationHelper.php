<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Tim Gatzky 2012 
 * @author     Tim Gatzky <info@tim-gatzky.de>
 * @package    readerpaginations
 * @license    LGPL 
 * @filesource
 */


class CatalogReaderPaginationHelper extends Frontend
{
	/**
	 * Get a list of catalog entries for the pagination
	 * Called from parseCatalog-HOOK
	 * @param array
	 * @param object
	 * @param object
	 * @return array
	 */
	public function parseCatalogHook($arrCatalog, $objTemplate, $objModule)
	{
		if($objModule->type != 'catalogreader' || !is_object($objModule))
		{
			 return $arrCatalog;
		}
		global $objPage;
		
		$this->import('Input');
		$this->import('Database');
		
		$this->pagination_format = $objModule->readerpagination_format;
		$this->strTable = $arrCatalog[0]['catalog_name']; // $objModule->strTable; return NULL ?!
				
		$time = time();
		$strBegin;
		$strEnd;
		$arrEntries = array();
		
		// Create a new data Object
		$objDate = new Date();
		
		// Set scope of pagination
		if($this->pagination_format == 'cat_year')
		{
			// Display current year only
			$strBegin = $objDate->__get('yearBegin');
			$strEnd = $objDate->__get('yearEnd');
		}
		elseif ($this->pagination_format == 'cat_month')	
		{
			// Display current month only
			$strBegin = $objDate->__get('monthBegin');
			$strEnd = $objDate->__get('monthEnd');
		}
		else
		{
			// Display all
		}
		
		
		// Check if there is a publish field set
		$objPublishField = $this->Database->prepare("SELECT publishField FROM tl_catalog_types WHERE tableName=?")
					->execute($this->strTable);
		$publishField = $objPublishField->publishField;
		
		// Check if there is a hide in pagination field set
		$objHideInPaginationField = $this->Database->prepare("SELECT hide_in_pagination FROM tl_catalog_types WHERE tableName=?")
					->execute($this->strTable);
		$hideInPaginationField = $objHideInPaginationField->hide_in_pagination;
		
		// collect all entries in scope
		$objCatalogStmt = $this->Database->prepare("
				SELECT 
					*
				FROM 
					" . $this->strTable . " 
				WHERE id>=1 ". ($strBegin ? " AND (tstamp>$strBegin) AND (tstamp<$strEnd)" : "" ) . " 
				" . ($publishField ? " AND $publishField=1 " : "" ) . " 
				" . ($hideInPaginationField ? " AND $hideInPaginationField!=1 " : "" ) . "
				ORDER BY sorting ASC
				");
		$objCatalog = $objCatalogStmt->execute();		
		
		$arrCatalogForPagination = $objCatalog->fetchAllAssoc();
		
		$GLOBALS['READERPAGINATION']['catalog'] = $arrCatalogForPagination;
		
		return $arrCatalog;
	} 


}

?>