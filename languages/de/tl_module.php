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
$GLOBALS['TL_LANG']['tl_module']['addReaderPagination']						= array('Pagination hinzufügen', 'Dem Leser-Modul eine Pagination zum Durchblättern hinzufügen');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_template'] 				= array('Layout-Template', 'Hier können Sie das Paginations-Template auswählen.');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_numberOfLinks'] 			= array('Anzahl an Links', 'Legen Sie fest wieviele Links angezeigt werden.');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'] 				= array('Anzeigeformat', 'Hier können Sie das Anzeigeformat/die Spannweite der Pagination auswählen.');
$GLOBALS['TL_LANG']['tl_module']['readerpagination_catalogTitleField'] 		= array('Titelfeld', 'Bitte wählen Sie das Feld, das als Referenz für die Bildung des Link-Titels genutzt werden soll');


/**
 * References
 */
#$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_list']		= 'Pagination Spannweite';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_month']	= 'Aktueller Monat';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_year']		= 'Aktuelles Jahr';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cal_all']		= 'Alle Events';
#$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_list']	= 'Pagination Spannweite';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_month']	= 'Aktueller Monat';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_year']	= 'Aktuelles Jahr';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['news_all']		= 'Alle Beträge';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cat_month']	= 'Aktueller Monat';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cat_year']		= 'Aktuelles Jahr';
$GLOBALS['TL_LANG']['tl_module']['readerpagination_format']['cat_all']		= 'Alle Einträge';

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['readerpagination_legend'] 	= 'Paginations-Einstellungen';


?>