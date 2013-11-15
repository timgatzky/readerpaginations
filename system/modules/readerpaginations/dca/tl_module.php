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

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = array('TableModuleReaderPaginations', 'modifyPalette');

/**
 * Selectors
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'addReaderPagination';

/**
 * Subpalettes
 */ 
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['addReaderPagination']	= 'readerpagination_format,readerpagination_numberOfLinks,readerpagination_template';

/**
 * Add palettes to tl_module
 */
// News
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader'] = str_replace
(
	'{protected_legend:hide}',
	'{readerpagination_legend:hide},addReaderPagination;{protected_legend:hide}',
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']	
);

// Calendar Reader
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader'] = str_replace
(
	'{protected_legend:hide}',
	'{readerpagination_legend:hide},addReaderPagination;{protected_legend:hide}',
	$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader']	
);
	
/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['addReaderPagination'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_module']['addReaderPagination'],
	'exclude'           => true,
	'inputType'         => 'checkbox',
	'eval'              => array('submitOnChange'=>true, 'tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_numberOfLinks'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_module']['readerpagination_numberOfLinks'],
	'exclude'           => true,
	'inputType'         => 'text',
	'eval'              => array('rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_template'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_module']['readerpagination_template'],
	'default'           => 'readerpagination_full',
	'exclude'           => true,
	'inputType'         => 'select',
	'options'           => $this->getTemplateGroup('readerpagination_'),
	'eval'              => array('includeBlankOption'=>false ,'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'reference'               => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
	'eval'                    => array('tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_customsql'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_where'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('tl_class'=>'clr')
);
