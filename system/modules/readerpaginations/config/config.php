<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2016
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		readerpaginations
 * @link		http://contao.org
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Initialization, Globals
 */
$GLOBALS['READERPAGINATION'] = array();

/**
 * Helper function to switch between pagination classes 
 * @param object
 * @return string
 * @throw string
 */
function addReaderPagination($objModule)
{
	if(!is_object($objModule))
	{
		throw new Exception('illegal call!');
	}
	
	$strBuffer = '';
	switch($objModule->type)
	{
		case 'eventreader':
			
			$objPagination = new \EventReaderPagination($objModule); 
			$strBuffer = $objPagination->generate();
			
			break;
		case 'newsreader':
			
			$objPagination = new \NewsReaderPagination($objModule); 
			$strBuffer = $objPagination->generate();
			
			break;
		default: $strBuffer = '';
			break;
	}
	
	return $strBuffer;
}



?>