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


class CatalogReaderPagination extends ModuleCatalog
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'readerpagination_full';


	/**
	 * Initialize
	 */
	public function __construct($objModule)
	{
		if($objModule->type != 'catalogreader' || !is_object($objModule))
		{
			 throw new Exception('illegal call!');
		}
		$this->import('Input');
		$this->import('Database');
		
		// Apply settings
		$this->strTemplate = $objModule->readerpagination_template;
		
		$this->archives = $objModule->news_archives;
		$this->pagination_format = $objModule->readerpagination_format;
		$this->strCatTable = $objModule->strTable;
		$this->catalogTitleField = $objModule->readerpagination_catalogTitleField;
		
		$this->intItem = 1;
		$this->strItem = $this->Input->get('items');
		$this->arrItems = $this->getItems(); // Get catalog entries in scope
		$this->intTotalItems = count($this->arrItems);
		$this->intNumberOfLinks = $objModule->readerpagination_numberOfLinks;
		$this->arrCatalog = $this->getCatalog();
					
		// Initialize default labels
		$this->lblFirst = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['first'];
		$this->lblPrevious = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['previous'];
		$this->lblNext = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['next'];
		$this->lblLast = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['last'];
		$this->lblTotal = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['total'];
		
		// Return if empty
		if(!$this->arrItems)
		{
			return '';
		}
		
		// get current item index
		foreach($this->arrItems as $index => $item)
		{
			if(!$GLOBALS['TL_CONFIG']['disableAlias'])
			{
				if($item['alias'] == $this->strItem) $this->intItem = $index;
			}
			else
			{
				if($item['id'] == $this->strItem) $this->intItem = $index;
			}
		}	
	}
	
	/**
     * generate
     * @return string
     */
	public function generate($strSeparator=' ', $blnShowFirstLast=true)
	{
		$this->strSeperator = $strSeparator;
		$this->blnShowFirstLast = $blnShowFirstLast;
		
		// Return if there is only one page
		if ($this->intTotalItems < 2 || $this->intItem < 1)
		{
			return '';
		}

		if ($this->intItem > $this->intTotalItems)
		{
			$this->intItem = $this->intTotalItems;
		}
		
		// Create new template Object
		$this->Template = new FrontendTemplate($this->strTemplate);
		
		// Buttons	
		$this->Template->hasFirst = $this->hasFirst();
		$this->Template->hasPrevious = $this->hasPrevious();
		$this->Template->hasNext = $this->hasNext();
		$this->Template->hasLast = $this->hasLast();
		
		$this->Template->first = array
		(
		   'link' => $this->lblFirst,
		   'href' => $this->linkToItem(1),
		   'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['readerpaginations']['goTo']), ($this->getItemTitle(1) ) )
		);
		
		$this->Template->previous = array
		(
		   'link' => $this->lblPrevious,
		   'href' => $this->linkToItem($this->intItem - 1),
		   'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['readerpaginations']['goTo']), ($this->getItemTitle($this->intItem - 1) ) )
		);
		
		$this->Template->next = array
		(
		   'link' => $this->lblNext,
		   'href' => $this->linkToItem($this->intItem + 1),
		   'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['readerpaginations']['goTo'] ), ($this->getItemTitle($this->intItem + 1) ) )
		);
		
		$this->Template->last = array
		(
		   'link' => $this->lblLast,
		   'href' => $this->linkToItem($this->intTotalItems),
		   'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['readerpaginations']['goTo'] ), ($this->getItemTitle($this->intTotalItems) ) )
		);

		
		global $objPage;
		$strTagClose = ($objPage->outputFormat == 'xhtml') ? ' />' : '>';

		// Add rel="prev" and rel="next" links
		if ($this->hasPrevious())
		{
			$GLOBALS['TL_HEAD'][] = '<link rel="prev" href="' . $this->linkToItem($this->intPage - 1) . '"' . $strTagClose;
		}
		if ($this->hasNext())
		{
			$GLOBALS['TL_HEAD'][] = '<link rel="next" href="' . $this->linkToItem($this->intPage + 1) . '"' . $strTagClose;
		}
		
		// Template Vars
		$this->Template->items = $this->getItemsAsString($this->strSeperator);
		
		//$this->Template->total = $this->intTotal;
		$this->Template->total = sprintf($this->lblTotal, $this->intItem, $this->intTotalItems);
		
		// #request: parse catalog data to template
		$this->Template->entries = $this->arrCatalog;
		
		return $this->Template->parse();
	}

	/**
     * Generate module
     * @return string
     */
	protected function compile() {	}
	
	/**
	 * Generate all page links separated with the given argument and return them as string
	 * @param string
	 * @return string
	 */
	public function getItemsAsString($strSeparator=' ')
	{
		$arrLinks = array();

		$intNumberOfLinks = floor($this->intNumberOfLinks / 2);
		$intFirstOffset = $this->intItem - $intNumberOfLinks - 1;

		if ($intFirstOffset > 0)
		{
			$intFirstOffset = 0;
		}

		$intLastOffset = $this->intItem + $intNumberOfLinks - $this->intTotalItems;

		if ($intLastOffset < 0)
		{
			$intLastOffset = 0;
		}

		$intFirstLink = $this->intItem - $intNumberOfLinks - $intLastOffset;

		if ($intFirstLink < 1)
		{
			$intFirstLink = 1;
		}

		$intLastLink = $this->intItem + $intNumberOfLinks - $intFirstOffset;

		if ($intLastLink > $this->intTotalItems)
		{
			$intLastLink = $this->intTotalItems;
		}

		for ($i=$intFirstLink; $i<=$intLastLink; $i++)
		{	
			if ($i == $this->intItem)
			{
				$arrLinks[] = sprintf('<li class="active"><span class="current active">%s</span></li>',
									 '<span class="index">' . $i . '</span>' . '<span class="title">' . $this->getItemTitle($i) . '</span>');
				continue;
			}
								
			$arrLinks[] = sprintf('<li><a href="%s" class="link" title="%s">%s</a></li>',
								$this->linkToItem($i),
								sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['readerpaginations']['goTo']), $this->getItemTitle($i)),
							 	 '<span class="index">' . $i . '</span>'  . '<span class="title">' . $this->getItemTitle($i) . '</span>'
								);
		}

		return implode($strSeparator, $arrLinks);
	}
	
	
	/**
	 * Gets the href from the event and returns the URL
	 * @param integer
	 * @return string
	 */
	protected function linkToItem($intItem)
	{
		return $this->arrItems[$intItem]['href'];
	}	
	
	/**
	 * Returns the title of a event
	 * @param integer
	 * @return string
	 */
	protected function getItemTitle($intItem)
	{
		return $this->arrItems[$intItem]['title'];
	}
	
	/**
	 * Return true if the pagination menu has a "<< first" link
	 * @return boolean
	 */
	public function hasFirst()
	{
		return ($this->blnShowFirstLast && $this->intItem > 2) ? true : false;
	}

	/**
	 * Return true if the pagination menu has a "< previous" link
	 * @return boolean
	 */
	public function hasPrevious()
	{
		return ($this->intItem > 1) ? true : false;
	}

	/**
	 * Return true if the pagination menu has a "next >" link
	 * @return boolean
	 */
	public function hasNext()
	{
		return ($this->intItem < $this->intTotalItems) ? true : false;
	}

	/**
	 * Return true if the pagination menu has a "last >>" link
	 * @return boolean
	 */
	public function hasLast()
	{
		return ($this->blnShowFirstLast && $this->intItem < ($this->intTotalItems - 1)) ? true : false;
	}

	
	
	/**
	 * Helper function to generate an array of news articles. Basically does the same as the Newslist module
	 * @return array
	 */
	private function getItems()
	{
		global $objPage;
		
		$arrEntries = $GLOBALS['READERPAGINATION']['catalog']; // created in CatalogReaderPaginationHelper, called from parseCatalog-HOOK
		
		if(count($arrEntries) < 1)
		{
			return '';
		}
		
		// add keys for pagination (title, href)
		foreach($arrEntries as $i => $entry)
		{
			// get alias
       		$strAlias = (!$GLOBALS['TL_CONFIG']['disableAlias'] && $entry['alias'] != '') ? $entry['alias'] : $entry['id'];
 			
 			$arrTmp = array
 			(
 				'href' => ampersand($this->generateFrontendUrl($objPage->row(), ((isset($GLOBALS['TL_CONFIG']['useAutoItem']) && $GLOBALS['TL_CONFIG']['useAutoItem']) ?  '/' : '/items/') . $strAlias)),
                'title' => specialchars($entry[$this->catalogTitleField]),
          	);
 			
 			$arrResult[] = array_merge($arrEntries[$i], $arrTmp);
 			unset($arrTmp);
		}
		$arrEntries = $arrResult;
		unset($arrResult);
				
		// Higher the keys of the array by 1
		$arrTmp = array();
		foreach($arrEntries as $key => $value)
		{
			$arrTmp[$key+1] = $value;
		}
		ksort($arrTmp);
		
		$arrEntries = $arrTmp;
		
		unset($arrTmp);

				
		return $arrEntries;
	}
	
	/**
	 * Get catalog entries as array
	 * @return array
	 */
	private function getCatalog()
	{
		$arrEntries = $GLOBALS['READERPAGINATION']['catalog']; // created in CatalogReaderPaginationHelper, called from parseCatalog-HOOK
		
		if(count($arrEntries) < 1)
		{
			return array();
		}
		
		return $arrEntries;
	}

}

?>