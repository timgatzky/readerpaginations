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
 * @copyright  Tim Gatzky 2011 
 * @author     Tim Gatzky <info@tim-gatzky.de>
 * @package    eventreaderpagination
 * @license    LGPL 
 * @filesource
 */



/**
 * Add palettes to tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_catalog_types']['palettes']['default'] = str_replace
(
	'publishField,allowManualSort',
	'publishField,allowManualSort,hide_in_pagination,',
	$GLOBALS['TL_DCA']['tl_catalog_types']['palettes']['default']
);



/**
 * Add fields to tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_catalog_types']['fields']['hide_in_pagination'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_catalog_types']['hide_in_pagination'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_catalog_types', 'getCheckBoxFields'),
	'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
	'doNotCopy'               => true,
);

?>