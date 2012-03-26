<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['addReaderPagination']						= array('Add a Pagination', 'Add a pagination to walk through items');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_template'] 				= array('Layout-Template', 'Choose your custom pagination template.');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_numberOfLinks'] 			= array('Number of links', 'Choose the number of links.');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'] 				= array('Scope', 'Choose the date format/date scope of the pagination');

/**
 * References
 */
#$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_list']		= 'Pagination Spannweite';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_month']	= 'Current month';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_year']		= 'Current year';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_all']		= 'All events';
#$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_list']	= 'Pagination Spannweite';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_month']	= 'Current month';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_year']	= 'Current year';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_all']		= 'All posts';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cat_month']	= 'Current month';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cat_year']		= 'Current year';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cat_all']		= 'All entries';

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['readerpagination_legend'] 	= 'Paginations-Settings';


?>