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
 * Add palettes to tl_news
 */
$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace
(
	'featured;',
	'featured,hide_in_pagination;',
	$GLOBALS['TL_DCA']['tl_news']['palettes']['default']
);


/**
 * Add fields to tl_news
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['hide_in_pagination'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['hide_in_pagination'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 clr'),
	'sql'					  => "char(1) NOT NULL default ''",
);