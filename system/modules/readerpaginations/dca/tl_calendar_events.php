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
 * Add palettes to tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace
(
	'noComments;',
	'noComments,hide_in_pagination;',
	$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
);


/**
 * Add fields to tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['hide_in_pagination'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['hide_in_pagination'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 clr'),
	'sql'					  => "char(1) NOT NULL default ''",
);