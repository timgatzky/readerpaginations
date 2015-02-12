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
 * EventReaderPagination
 */
class EventReaderPagination extends \Events
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
		if($objModule->type != 'eventreader' || !is_object($objModule))
		{
			 throw new \Exception('Object is not type of: eventreader');
		}
		
		$objInput = \Input::getInstance();
		
		// Apply settings
		$this->strTemplate = $objModule->readerpagination_template;
		$this->showTitles = $objModule->readerpagination_showTitles;		
		
		$this->archives = $objModule->cal_calendar;
		$this->cal_order = $objModule->cal_order;
		$this->pagination_format = $objModule->readerpagination_format;
		
		$this->intItem = 1;
		$this->strItem = $objInput->get('events');
		$this->arrItems = $this->getItems(); // Get events in scope
		$this->intTotalItems = count($this->arrItems);
		$this->intNumberOfLinks = $objModule->readerpagination_numberOfLinks;
						
		// Initialize default labels
		$this->lblFirst = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['first'];
		$this->lblPrevious = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['previous'];
		$this->lblNext = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['next'];
		$this->lblLast = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['last'];
		$this->lblTotal = $GLOBALS['TL_LANG']['MSC']['readerpaginations']['total'];
		
		// get current event index
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
				$arrLinks[] = sprintf('<li class="active"><span class="current active">%s</span></li>', '<span class="index">' . $i . '</span>' . '<span class="title">' . $this->getItemTitle($i) . '</span>');
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
	 * Helper function to generate an array of events. Basically does the same as the Eventlist module
	 * @return array
	 */
	private function getItems()
	{
		global $objPage;
		$objInput = \Input::getInstance();
		
		$blnClearInput = false;

		// Jump to the current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
		{
			switch ($this->pagination_format)
			{
				case 'cal_year':
					$objInput->setGet('year', date('Y'));
					break;

				case 'cal_month':
					$objInput->setGet('month', date('Ym'));
					break;

				case 'cal_day':
					$objInput->setGet('day', date('Ymd'));
					break;
			}

			$blnClearInput = true;
		}
		
		$blnDynamicFormat = (!$this->cal_ignoreDynamic && in_array($this->pagination_format, array('cal_day', 'cal_month', 'cal_year')));

		// Display year
		if ($blnDynamicFormat && $objInput->get('year'))
		{
			$this->Date = new \Date($objInput->get('year'), 'Y');
			$this->pagination_format = 'cal_year';
			$this->headline .= ' ' . date('Y', $this->Date->tstamp);
		}

		// Display month
		elseif ($blnDynamicFormat && $this->Input->get('month'))
		{
			$this->Date = new \Date($this->Input->get('month'), 'Ym');
			$this->pagination_format = 'cal_month';
			$this->headline .= ' ' . $this->parseDate('F Y', $this->Date->tstamp);
		}

		// Display day
		elseif ($blnDynamicFormat && $objInput->get('day'))
		{
			$this->Date = new \Date($objInput->get('day'), 'Ymd');
			$this->pagination_format = 'cal_day';
			$this->headline .= ' ' . $this->parseDate($objPage->dateFormat, $this->Date->tstamp);
		}

		// Display all events or upcoming/past events
		else
		{
			$this->Date = new \Date();
		}
		
		list($strBegin, $strEnd, $strEmpty) = $this->getDatesFromFormat($this->Date, $this->pagination_format);

		// Get all events
		$arrAllEvents = $this->getAllEvents($this->archives, $strBegin, $strEnd);
		
		// HOOK: allow other extensions to modify the items
		if (isset($GLOBALS['TL_HOOKS']['readerpagination']['getItems']) && count($GLOBALS['TL_HOOKS']['readerpagination']['getItems']) > 0)
		{
			foreach($GLOBALS['TL_HOOKS']['readerpagination']['getItems'] as $callback)
			{
				$this->import($callback[0]);
				$arrAllEvents = $this->$callback[0]->$callback[1]('events',$arrAllEvents,$this);
			}
		}
		
		if(count($arrAllEvents) < 1)
		{
			return array();
		}
		
		$sort = ($this->cal_order == 'descending') ? 'krsort' : 'ksort'; 
		
		// Sort the days
		$sort($arrAllEvents);
		// Sort the events
		foreach (array_keys($arrAllEvents) as $key)
		{
			$sort($arrAllEvents[$key]);
		}

		$dateBegin = date('Ymd', $strBegin);
		$dateEnd = date('Ymd', $strEnd);

		// Remove events outside the scope AND events that should not be listet in the pagination
		$arrTmp = array();
		foreach ($arrAllEvents as $key=>$days)
		{
			if ($key < $dateBegin || $key > $dateEnd)
			{
				continue;
			}
			
			foreach ($days as $day=>$events)
			{
				foreach ($events as $event)
				{
					$id = $event['id'];
					$pid = $event['pid'];
					
					$event['firstDay'] = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];
					$event['firstDate'] = $this->parseDate($objPage->dateFormat, $day);
					$event['datetime'] = date($GLOBALS['TL_CONFIG']['dateFormat'], $day);
					
					if(!$event['hide_in_pagination'])
					{
						// Short view fix: ignore duplicate events. (recurring events)
						$arrTmp[$pid][$id] = $event;
					}
					
				}
			}
		}
		unset($arrAllEvents);
		
		
		if(count($arrTmp) < 1)
		{
			return array();
		}
		
		$arrEvents = array();
		// Short view fix: ignore duplicate events. (recurring events)
		foreach($arrTmp as $pid)
		{
			foreach($pid as $event)
			{
				$arrEvents[] = $event;
			}
		}			
		
		// Higher the keys of the array by 1
		$tmpArray = array();
		foreach($arrEvents as $key => $event)
		{
			$tmpArray[$key+1] = $event;
		}
		ksort($tmpArray);
		
		$arrEvents = $tmpArray;
		
		unset($tmpArray);
		
		return $arrEvents;
	}
	
	
	

}

?>