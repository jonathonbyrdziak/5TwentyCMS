<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Five Twenty CMS
 * @SubPackage PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Function is responsible for validating a safe file and saving it to the 
 * associated directory, then returning the target path
 * 
 * @param array $fileArray
 * @param string $type
 * @param string $directory
 * @return string
 */
function save_file( $fileArray, $type = null, $directory = '' )
{
	//reasons to fail
	if (!BRequest::get('files',false) || !is_array($fileArray)) return false;
	if (!file_is_safe($fileArray, $type)) return false;
	
	//initializing
	$parts = pathinfo($fileArray['name']);
	$original_name = $parts['basename'];
	
	$target_path = FivePath::clean( 
		strtolower(UPLOADS.DS.$directory.DS.create_guid().'.'.$parts['extension'])
	);
	
	if ( !move_uploaded_file($fileArray['tmp_name'], $target_path) ) return false;
	
	//success
	return str_replace(ABSPATH, '', $target_path);
}

/**
 * Function is responsible for checking to see if the file is safe to
 * save to the system.
 *
 * @param string $file : location of the file on the server
 * @param string|array $types : (optional)accepted file types
 */
function file_is_safe( $fileArray, $ftype =  null )
{
	//reasosn to fail
	if (!is_array($fileArray)) return false;
	if ($fileArray['error'] > 0) return false;
	
	//checking the file size
	if ( $fileArray['size'] < 1 ) return false;
	
	//checking the file type
	extract(check_filetype($fileArray['name']));
	if (!$ext || !$type) return false;
	
	if (!is_null($ftype) && strpos($type, $ftype) === false ) return false;
	
	//additional checks if this is an image
	if ($ftype == 'image' && !getimagesize($fileArray['tmp_name'])) return false;
	
	//this is safe
	return true;
}

/**
 * Retrieve the file type from the file name.
 *
 * You can optionally define the mime array, if needed.
 *
 * @since 2.0.4
 *
 * @param string $filename File name or path.
 * @param array $mimes Optional. Key is the file extension with value as the mime type.
 * @return array Values with extension first and mime type.
 */
function check_filetype( $filename, $mimes = null ) {
	if ( empty($mimes) )
		$mimes = get_allowed_mime_types();
	$type = false;
	$ext = false;

	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext = $ext_matches[1];
			break;
		}
	}

	return compact( 'ext', 'type' );
}

/**
 * Retrieve list of allowed mime types and file extensions.
 *
 * @since 2.8.6
 *
 * @return array Array of mime types keyed by the file extension regex corresponding to those types.
 */
function get_allowed_mime_types() {
	static $mimes = false;

	if ( !$mimes ) {
		// Accepted MIME types are set here as PCRE unless provided.
		$mimes = apply_filters( 'upload_mimes', array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp',
		'tif|tiff' => 'image/tiff',
		'ico' => 'image/x-icon',
		'asf|asx|wax|wmv|wmx' => 'video/asf',
		'avi' => 'video/avi',
		'divx' => 'video/divx',
		'flv' => 'video/x-flv',
		'mov|qt' => 'video/quicktime',
		'mpeg|mpg|mpe' => 'video/mpeg',
		'txt|asc|c|cc|h' => 'text/plain',
		'csv' => 'text/csv',
		'tsv' => 'text/tab-separated-values',
		'rtx' => 'text/richtext',
		'css' => 'text/css',
		'htm|html' => 'text/html',
		'mp3|m4a|m4b' => 'audio/mpeg',
		'mp4|m4v' => 'video/mp4',
		'ra|ram' => 'audio/x-realaudio',
		'wav' => 'audio/wav',
		'ogg|oga' => 'audio/ogg',
		'ogv' => 'video/ogg',
		'mid|midi' => 'audio/midi',
		'wma' => 'audio/wma',
		'mka' => 'audio/x-matroska',
		'mkv' => 'video/x-matroska',
		'rtf' => 'application/rtf',
		'js' => 'application/javascript',
		'pdf' => 'application/pdf',
		'doc|docx' => 'application/msword',
		'pot|pps|ppt|pptx|ppam|pptm|sldm|ppsm|potm' => 'application/vnd.ms-powerpoint',
		'wri' => 'application/vnd.ms-write',
		'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm' => 'application/vnd.ms-excel',
		'mdb' => 'application/vnd.ms-access',
		'mpp' => 'application/vnd.ms-project',
		'docm|dotm' => 'application/vnd.ms-word',
		'pptx|sldx|ppsx|potx' => 'application/vnd.openxmlformats-officedocument.presentationml',
		'xlsx|xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml',
		'docx|dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml',
		'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
		'swf' => 'application/x-shockwave-flash',
		'class' => 'application/java',
		'tar' => 'application/x-tar',
		'zip' => 'application/zip',
		'gz|gzip' => 'application/x-gzip',
		'exe' => 'application/x-msdownload',
		// openoffice formats
		'odt' => 'application/vnd.oasis.opendocument.text',
		'odp' => 'application/vnd.oasis.opendocument.presentation',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		'odg' => 'application/vnd.oasis.opendocument.graphics',
		'odc' => 'application/vnd.oasis.opendocument.chart',
		'odb' => 'application/vnd.oasis.opendocument.database',
		'odf' => 'application/vnd.oasis.opendocument.formula',
		// wordperfect formats
		'wp|wpd' => 'application/wordperfect',
		) );
	}

	return $mimes;
}