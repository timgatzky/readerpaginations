<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Readerpaginations
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'CatalogReaderPagination'       => 'system/modules/readerpaginations/classes/CatalogReaderPagination.php',
	'CatalogReaderPaginationHelper' => 'system/modules/readerpaginations/classes/CatalogReaderPaginationHelper.php',
	'EventReaderPagination'         => 'system/modules/readerpaginations/classes/EventReaderPagination.php',
	'NewsReaderPagination'          => 'system/modules/readerpaginations/classes/NewsReaderPagination.php',
	'TableModuleReaderPaginations'  => 'system/modules/readerpaginations/classes/TableModuleReaderPaginations.php',

));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_event'               => 'system/modules/readerpaginations/templates',
	'mod_newsreader'          => 'system/modules/readerpaginations/templates',
	'readerpagination_full'   => 'system/modules/readerpaginations/templates',
	'readerpagination_simple' => 'system/modules/readerpaginations/templates',
));
