<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2013
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		readerpaginations
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Class file
 * NewsReaderPagination
 */
class NewsReaderPagination extends \ModuleNewsList
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
		if($objModule->type != 'newsreader' || !is_object($objModule))
		{
			 throw new \Exception('Object is not type of: newsreader');
		}
		
		$objInput = \Input::getInstance();
		
		// Apply settings
		$this->strTemplate = $objModule->readerpagination_template;
		$this->showTitles = $objModule->readerpagination_showTitles;		
		
		$this->archives = $objModule->news_archives;
		$this->pagination_format = $objModule->readerpagination_format;
		
		$this->intItem = 1;
		$this->strItem = $objInput->get('items');
		$this->arrItems = $this->getItems(); // Get news in scope
		$this->intTotalItems = count($this->arrItems);
		$this->intNumberOfLinks = $objModule->readerpagination_numberOfLinks;
					
		// Initialize default labels
		$this->lblFirst = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['first'];
		$this->lblPrevious = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['previous'];
		$this->lblNext = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['next'];
		$this->lblLast = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['last'];
		$this->lblTotal = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['total'];
		
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
		$this->Template = new \FrontendTemplate($this->strTemplate);
		
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
		
		$this->Template->raw = $this;
		$this->Template->entries = $this->arrItems;
		
		return $this->Template->parse();
	}

	/**
	 * Generate the module
	 */
	public function compile() {}

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
	 * Helper function to generate an array of news articles.
	 * @return array
	 */
	private function getItems()
	{
		global $objPage;
		$objDatabase = \Database::getInstance();
		
		$time = time();
		$strBegin;
		$strEnd;
		$arrArticles = array();
		
		// Create a new data Object
		$objDate = new \Date();
		
		// Set scope of pagination
		if($this->pagination_format == 'news_year')
		{
			// Display current year only
			$strBegin = $objDate->__get('yearBegin');
			$strEnd = $objDate->__get('yearEnd');
		}
		elseif ($this->pagination_format == 'news_month')	
		{
			// Display current month only
			$strBegin = $objDate->__get('monthBegin');
			$strEnd = $objDate->__get('monthEnd');
		}
		else
		{
			// Display all
		}
		
		$strCustomWhere = '';
		// HOOK: allow other extensions to modify the sql WHERE clause
		if (isset($GLOBALS['TL_HOOKS']['readerpagination']['customsql_where']) && count($GLOBALS['TL_HOOKS']['readerpagination']['customsql_where']) > 0)
		{
			foreach($GLOBALS['TL_HOOKS']['readerpagination']['customsql_where'] as $callback)
			{
				$this->import($callback[0]);
				$strCustomWhere = $this->$callback[0]->$callback[1]('news',$this);
			}
		}
		
		// Fetch all news that fit in the scope
		$objArticlesStmt = $objDatabase->prepare("
        	SELECT * FROM tl_news
        	WHERE 
        		pid IN(" . implode(',', $this->archives) . ") AND published=1 AND hide_in_pagination!=1
        		" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time)" : "") . "
        		" . ($strBegin ? " AND (date>$strBegin) AND (date<$strEnd)" : "" ) ." ".$strCustomWhere. "
        	ORDER BY date DESC");
	   
		$objArticles = $objArticlesStmt->execute();
		
		if ($objArticles->numRows < 1)
		{
			return array();
		}
		
		// get all articles
		$arrArticles = $objArticles->fetchAllAssoc();
		
		// HOOK: allow other extensions to modify the items
		if (isset($GLOBALS['TL_HOOKS']['readerpagination']['getItems']) && count($GLOBALS['TL_HOOKS']['readerpagination']['getItems']) > 0)
		{
			foreach($GLOBALS['TL_HOOKS']['readerpagination']['getItems'] as $callback)
			{
				$this->import($callback[0]);
				$arrArticles = $this->$callback[0]->$callback[1]('news',$arrArticles,$this);
			}
		}
		
		if(count($arrArticles) < 1)
		{
			return array();
		}
		
		// add keys for pagination (title, href)
		foreach($arrArticles as $i => $article)
		{
			// get alias
       		$strAlias = (!$GLOBALS['TL_CONFIG']['disableAlias'] && $article['alias'] != '') ? $article['alias'] : $article['id'];
 			
 			$arrTmp = array
 			(
 				'href' => ampersand($this->generateFrontendUrl($objPage->row(), ((isset($GLOBALS['TL_CONFIG']['useAutoItem']) && $GLOBALS['TL_CONFIG']['useAutoItem']) ?  '/' : '/items/') . $strAlias)),
                'title' => specialchars($article['headline']),
          	);
 			
 			$arrResult[] = array_merge($arrArticles[$i], $arrTmp);
 			unset($arrTmp);
		}
		$arrArticles = $arrResult;
		unset($arrResult);
		
		if(count($arrArticles) < 1)
		{
			return array();
		}
		
		// Higher the keys of the array by 1
		$arrTmp = array();
		foreach($arrArticles as $key => $value)
		{
			$arrTmp[$key+1] = $value;
		}
		ksort($arrTmp);
		
		$arrArticles = $arrTmp;
		unset($arrTmp);
		
		return $arrArticles;
	}
	
	
	

}

?>