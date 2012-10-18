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

// Modify on load
$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = array('tl_module_readerpagination', 'modifyPalette');



/**
 * Selectors
 */
array_insert($GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'],1,array
(
	'addReaderPagination'
));


/**
 * Subpalettes
 */ 
array_insert($GLOBALS['TL_DCA']['tl_module']['subpalettes'],1,array
(
	'addReaderPagination'	=> 'readerpagination_format,readerpagination_numberOfLinks,readerpagination_template,readerpagination_customsql',
));


/**
 * Add palettes to tl_module
 */
// Newsreader
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader'] = str_replace
(
	'{protected_legend:hide}',
	'{readerpagination_legend:hide},addReaderPagination;{protected_legend:hide}',
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']	
);

// Event/Calendar Reader
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader'] = str_replace
(
	'{protected_legend:hide}',
	'{readerpagination_legend:hide},addReaderPagination;{protected_legend:hide}',
	$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader']	
);

// Catalog Reader
$GLOBALS['TL_DCA']['tl_module']['palettes']['catalogreader'] = str_replace
(
	'{protected_legend:hide}',
	'{readerpagination_legend:hide},addReaderPagination;{protected_legend:hide}',
	$GLOBALS['TL_DCA']['tl_module']['palettes']['catalogreader']	
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

#$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_showTitles'] = array
#(
#	'label'				=> &$GLOBALS['TL_LANG']['tl_module']['readerpagination_showTitles'],
#	'exclude'           => true,
#	'inputType'         => 'checkbox',
#	'eval'              => array('tl_class'=>'w50'),
#);

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
	'default'                 => '',
	'exclude'                 => false,
	'inputType'               => 'select',
	'eval'                    => array('tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_catalogTitleField'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_catalogTitleField'],
	'exclude'                 => true,
	'inputType'               => 'radio',
	//'foreignKey'              => 'tl_catalog_types.name',
	'options_callback'        => array('tl_module_catalog', 'getCatalogFields'),
	'eval'                    => array('tl_class'=>'clr', 'mandatory'=> false, 'submitOnChange'=> false)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_customsql'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['catalog_where'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('tl_class'=>'clr')
);

class tl_module_readerpagination extends Backend 
{
	/**
	 * Modify palette depending on module type
	 */
	public function modifyPalette(DataContainer $dc)
	{
		$objModule = $this->Database->execute("SELECT type FROM " . $dc->table . " WHERE id=" . $dc->id);
		if(!$objModule->numRows)
		{
			return $dc;
		}
		
		switch($objModule->type)
		{
			case 'eventreader':
				
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format'] = array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
					'default'                 => 'cal_all',
					'exclude'                 => false,
					'inputType'               => 'select',
					//'options_callback'        => array('tl_module_eventreaderpagination', 'getCalFormats'),
					'options'				  => array('cal_month', 'cal_year', 'cal_all'),
					'reference'               => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
					'eval'                    => array('tl_class'=>'w50'),
				); 
				
				break;
			case 'newsreader':
				
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format'] = array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
					'default'                 => 'news_all',
					'exclude'                 => false,
					'inputType'               => 'select',
					'options'				  => array('news_month', 'news_year', 'news_all'),
					'reference'               => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
					'eval'                    => array('tl_class'=>'w50'),	
				); 

				break;
			case 'catalogreader':
				
				// add catalog title field selection to subpalette
				$GLOBALS['TL_DCA']['tl_module']['subpalettes']['addReaderPagination'] = 'readerpagination_format,readerpagination_numberOfLinks,readerpagination_template,readerpagination_catalogTitleField,readerpagination_customsql';			
				
				$GLOBALS['TL_DCA']['tl_module']['fields']['readerpagination_format'] = array
				(
					'label'                   => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
					'default'                 => 'cat_all',
					'exclude'                 => false,
					'inputType'               => 'select',
					'options'				  => array('cat_month', 'cat_year', 'cat_all'),
					'reference'               => &$GLOBALS['TL_LANG']['tl_module']['readerpagination_format'],
					'eval'                    => array('tl_class'=>'w50'),	
				); 

				break;
			
			
		}
	}
	
	
	/**
	 * Return the calendar formats depending on the module type
	 * @param DataContainer
	 * @return array
	 */
	public function getCalFormats(DataContainer $dc)
	{
		return array
		(
			'cal_list'     => array('cal_month', 'cal_year', 'cal_all'),
//			'cal_upcoming' => array('next_7', 'next_14', 'next_30', 'next_90', 'next_180', 'next_365', 'next_two', 'next_cur_month', 'next_cur_year', 'next_all'),
//			'cal_past'     => array('past_7', 'past_14', 'past_30', 'past_90', 'past_180', 'past_365', 'past_two', 'past_cur_month', 'past_cur_year', 'past_all')
		);
	}

	
}
?>