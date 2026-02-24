<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * WordPress eXtended RSS file parser implementations
 *
 * @package WordPress
 * @subpackage Importer
 */

_deprecated_file( basename( __FILE__ ), '0.7.0' );

/** Solace_Extra_WXR_Parser class */
require_once __DIR__ . '/parsers/class-wxr-parser.php';

/** Solace_Extra_WXR_Parser_SimpleXML class */
require_once __DIR__ . '/parsers/class-wxr-parser-simplexml.php';

/** Solace_Extra_WXR_Parser_XML class */
require_once __DIR__ . '/parsers/class-wxr-parser-xml.php';

/** Solace_Extra_WXR_Parser_Regex class */
require_once __DIR__ . '/parsers/class-wxr-parser-regex.php';
