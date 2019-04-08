<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\files\components;

// Yii Imports
use Yii;
use yii\base\Component;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;
use cmsgears\files\config\FileProperties;

use cmsgears\files\utilities\ImageUtil;

/**
 * The file manager accepts single file at a time uploaded by either XHR or file data
 * using AJAX post. It also accept file data sent via Post using forms. It need PHP5 and
 * GD library of PHP for image processing.
 *
 * @since 1.0.0
 */
class FileManager extends Component {

	const FILE_TYPE_IMAGE		= 'image';
	const FILE_TYPE_VIDEO		= 'video';
	const FILE_TYPE_AUDIO		= 'audio';
	const FILE_TYPE_DOCUMENT	= 'document';
	const FILE_TYPE_COMPRESSED	= 'compressed';
	const FILE_TYPE_MIXED		= 'mixed';

	public $ignoreDbConfig = false;

	// The extensions allowed by this file uploader.
	public $imageExtensions		 = [ 'png', 'jpg', 'jpeg', 'gif' ];
	public $videoExtensions		 = [ 'mp4', 'flv', 'ogv', 'avi' ];
	public $audioExtensions		 = [ 'mp3', 'm4a', 'wav' ];
	public $documentExtensions	 = [ 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt' ];
	public $compressedExtensions = [ 'rar', 'zip' ];
	public $mixedExtensions		= [ 'png', 'jpg', 'jpeg', 'gif', 'mp4', 'flv', 'ogv', 'avi', 'mp3', 'm4a', 'wav', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'rar', 'zip' ];

	public $typeMap = [ 0 => 'Select File Type', self::FILE_TYPE_IMAGE => self::FILE_TYPE_IMAGE, self::FILE_TYPE_VIDEO => self::FILE_TYPE_VIDEO, self::FILE_TYPE_AUDIO => self::FILE_TYPE_AUDIO, self::FILE_TYPE_DOCUMENT => self::FILE_TYPE_DOCUMENT, self::FILE_TYPE_COMPRESSED => self::FILE_TYPE_COMPRESSED ];

	// The quality of image in percentage
	public $imageQuality = 80;

	// Either of these must be set to true. Generate Name generate a unique name using Yii Security Component whereas pretty names use the file name provided by user and replace space by hyphen(-).
	public $generateName = true;

	// TODO - Check for existing file having same name
	public $prettyNames = false;

	public $maxSize			 = 5; // In MB
	public $maxResolution	 = 10000; // Maximum pixels either horizontally or vertically.

	// Image Medium Generation - 75% by default
	public $generateImageMedium	= true;
	public $mediumPercent		= 75;
	public $mediumWidth			= 0;
	public $mediumHeight		= 0;

	// Image Small Generation - 50% by default
	public $generateImageSmall	= true;
	public $smallPercent		= 50;
	public $smallWidth			= 0;
	public $smallHeight			= 0;

	// Image Thumb Generation
	public $generateImageThumb	 = true;
	public $thumbWidth			 = 120;
	public $thumbHeight			 = 120;

	// Image Placeholder Generation
	public $generateImagePl = true;
	public $plQuality		= 30; // Quality in percent

	// These must be set to allow file manager to work.
	public $uploads = true;

	// Magic Dir
	public $_uploadDir = null;

	// Magic Url
	public $_uploadUrl = null;

	public $tempUploadDir	 = null;
	public $tempUploadUrl	 = null;

	// Blur iterations
	public $blurRange = 5;

	// Constructor and Initialisation ------------------------------

	public function __construct( $config = [] ) {

		if( !empty( $config ) ) {

			Yii::configure( $this, $config );
		}

		if( !$this->ignoreDbConfig ) {

			$properties = FileProperties::getInstance();

			// Use properties configured in DB on priority, else fallback to the one defined in this class.
			$this->imageExtensions		= $properties->getImageExtensions( $this->imageExtensions );
			$this->videoExtensions		= $properties->getVideoExtensions( $this->videoExtensions );
			$this->audioExtensions		= $properties->getAudioExtensions( $this->audioExtensions );
			$this->documentExtensions	= $properties->getDocumentExtensions( $this->documentExtensions );
			$this->compressedExtensions	= $properties->getCompressedExtensions( $this->compressedExtensions );
			$this->imageQuality			= $properties->getImageQuality( $this->imageQuality );
			$this->generateName			= $properties->isGenerateName( $this->generateName );
			$this->prettyNames			= $properties->isPrettyName( $this->prettyNames );
			$this->maxSize				= $properties->getMaxSize( $this->maxSize );
			$this->maxResolution		= $properties->getMaxResolution( $this->maxResolution );
			$this->generateImageMedium	= $properties->isGenerateMedium( $this->generateImageMedium );
			$this->mediumWidth			= $properties->getMediumWidth( $this->mediumWidth );
			$this->mediumHeight			= $properties->getMediumHeight( $this->mediumHeight );
			$this->generateImageSmall	= $properties->isGenerateSmall( $this->generateImageSmall );
			$this->smallWidth			= $properties->getMediumWidth( $this->smallWidth );
			$this->smallHeight			= $properties->getMediumHeight( $this->smallHeight );
			$this->generateImageThumb	= $properties->isGenerateThumb( $this->generateImageThumb );
			$this->generateImagePl		= $properties->isGeneratePlaceholder( $this->generateImagePl );
			$this->thumbWidth			= $properties->getThumbWidth( $this->thumbWidth );
			$this->thumbHeight			= $properties->getThumbHeight( $this->thumbHeight );
			$this->uploads				= $properties->isUpload( $this->uploads );

			$this->_uploadDir	= Yii::getAlias( '@uploads' );
			$this->_uploadDir	= $properties->getUploadDir( $this->_uploadDir );
			$this->_uploadUrl	= $properties->getUploadUrl( $this->_uploadUrl );
		}

		$this->init();
	}

	public function getTypeMap( $exclude = [] ) {

		$typeMap = $this->typeMap;

		foreach( $exclude as $type ) {

			unset( $typeMap[ $type ] );
		}

		return $typeMap;
	}

	public function getUploadDir() {

		return FileHelper::normalizePath( $this->_uploadDir );
	}

	public function getUploadUrl() {

		if( YII_ENV_PROD && in_array( Yii::$app->id, Yii::$app->core->getCdnApps() ) ) {

			$resource = CoreProperties::getInstance()->getResourceUrl();

			$core	= parse_url( $resource );
			$file	= parse_url( $this->_uploadUrl );

			return $core[ 'scheme' ] . "://" . $core[ 'host' ] . $file[ 'path' ];
		}

		return $this->_uploadUrl;
	}

	// File Uploading ----------------------------------------------

	public function handleFileUpload( $directory, $type, $gen ) {

		return $this->processFileUpload( $directory, $type, $gen );
	}

	private function processFileUpload( $directory, $type, $gen ) {

		// Get the filename submitted by user
		$filename = ( isset( $_SERVER[ 'HTTP_X_FILENAME' ] ) ? $_SERVER[ 'HTTP_X_FILENAME' ] : false );

		// Reject File
		if( !$this->uploads ) {

			return [ 'error' => 'File upload is disabled by the site admin.' ];
		}

		// Modern Style using Xhr
		if( $filename ) {

			$extension = pathinfo( $filename, PATHINFO_EXTENSION );

			// Detect file type
			if( empty( $type ) || $type == 'undefined' ) {

				$type = $this->detectType( $extension );
			}

			$allowedExtensions = $this->getAllowedExtensions( $type );

			// check allowed extensions
			if( in_array( strtolower( $extension ), $allowedExtensions ) ) {

				return $this->saveTempFile( file_get_contents( 'php://input' ), $directory, $type, $filename, $extension, $gen );
			}
			else {

				return [ 'error' => 'The choosen file extension is not allowed.' ];
			}
		}
		// Modern Style using File Data
		else if( isset( $_POST[ 'fileData' ] ) && $_POST[ 'fileData' ] ) {

			$filename = $_POST[ 'fileName' ];

			if( $filename ) {

				$extension = $_POST[ 'fileExtension' ];

				// Detect file type
				if( empty( $type ) || $type == 'undefined' ) {

					$type = $this->detectType( $extension );
				}

				$allowedExtensions = $this->getAllowedExtensions( $type );

				// check allowed extensions
				if( in_array( strtolower( $extension ), $allowedExtensions ) ) {

					// Decoding file data
					$file	= $_POST[ 'file' ];
					$file	= str_replace( ' ', '+', $file );
					$file	= base64_decode( $file );

					return $this->saveTempFile( $file, $directory, $type, $filename, $extension, $gen );
				}
				else {

					return [ 'error' => 'The choosen file extension is not allowed.' ];
				}
			}
		}
		// Legacy System using file
		else {

			if( isset( $_FILES[ 'file' ] ) ) {

				if( $_FILES[ 'file' ][ 'error' ] > 0 ) {

					if( $_FILES[ 'file' ][ 'error' ] == UPLOAD_ERR_INI_SIZE || $_FILES[ 'file' ][ 'error' ] == UPLOAD_ERR_FORM_SIZE ) {

						return [ 'error' => "You have exceeded the allowed file size limit of $this->maxSize MB." ];
					}
					else if( $_FILES[ 'file' ][ 'error' ] == UPLOAD_ERR_CANT_WRITE ) {

						return [ 'error' => "Please update admin to provide write permissions to save uploaded file." ];
					}
				}
				else {

					$filename = $_FILES[ 'file' ][ 'name' ];

					if( $filename ) {

						$extension = pathinfo( $filename, PATHINFO_EXTENSION );

						// Detect file type
						if( empty( $type ) || $type == 'undefined' ) {

							$type = $this->detectType( $extension );
						}

						$allowedExtensions = $this->getAllowedExtensions( $type );

						// check allowed extensions
						if( in_array( strtolower( $extension ), $allowedExtensions ) ) {

							return $this->saveTempFile( file_get_contents( $_FILES[ 'file' ][ 'tmp_name' ] ), $directory, $type, $filename, $extension, $gen );
						}
						else {

							return [ 'error' => 'The choosen file extension is not allowed.' ];
						}
					}
				}
			}
		}

		return [ 'error' => 'File upload failed.' ];
	}

	public function saveTempFile( $file_contents, $directory, $type, $filename, $extension, $gen ) {

		// Check allowed file size
		$sizeInMb = number_format( strlen( $file_contents ) / 1048576, 8 );

		if( $sizeInMb > $this->maxSize ) {

			return [ 'error' => "You have exceeded the allowed file size limit of $this->maxSize MB." ];
		}

		// Create Directory if not exist
		$tempUrl	= "temp/$directory";
		$uploadDir	= "$this->uploadDir/$tempUrl";

		if( isset( $this->tempUploadDir ) ) {

			$uploadDir = "$this->tempUploadDir/$tempUrl";
		}

		$uploadUrl = $this->uploadUrl;

		if( isset( $this->tempUploadUrl ) ) {

			$uploadUrl = $this->tempUploadUrl;
		}

		if( !file_exists( $uploadDir ) ) {

			mkdir( $uploadDir, 0777, true );
		}

		// Generate File Name
		$name = pathinfo( $filename, PATHINFO_FILENAME );

		if( $gen || $this->generateName ) {

			$name = Yii::$app->security->generateRandomString();
		}
		// TODO: Check for existing name and add index at the end to distinguish from existing file
		else if( $this->prettyNames ) {

			$name = Inflector::slug( $name );
		}

		$upname		= $name . "." . $extension;
		$filePath	= "$uploadDir/$upname";

		// Name generation is disabled and pretty name required
		if( !$this->generateName && $this->prettyNames ) {

			$dateDir = date( 'Y-m-d' );

			$exist	= true;
			$count	= 1;
			$limit	= 25; // Max 25 files with same name in a folder

			while( $exist ) {

				if( file_exists( "$this->uploadDir/$dateDir/$directory/$upname" ) ) {

					if( $count > $limit ) {

						return [ 'error' => 'File upload failed. Please upload a file with different name.' ];
					}

					$upname		= "$name-$count." . $extension;
					$filePath	= "$uploadDir/$upname";

					$count++;
				}
				else {

					$exist	= false;
					$name	= $count > 1 ? "$name-" . ( $count - 1) : $name;

					break;
				}
			}
		}

		// Save File
		if( file_put_contents( "$uploadDir/$upname", $file_contents ) ) {

			$result = [];

			$result[ 'name' ]		= $name;
			$result[ 'title' ]		= pathinfo( $filename, PATHINFO_FILENAME );
			$result[ 'type' ]		= $type;
			$result[ 'extension' ]	= $extension;
			$result[ 'size' ]		= $sizeInMb;

			// Special processing for Avatar Uploader
			if( strcmp( $directory, 'avatar' ) == 0 ) {

				// Generate Thumb
				$thumbName	= $name . '-thumb' . "." . $extension;
				$resizeObj	= new ImageUtil( "$uploadDir/$upname" );

				$resizeObj->resizeImage( $this->thumbWidth, $this->thumbHeight, 'crop' );
				$resizeObj->saveImage( "$uploadDir/$thumbName", 100 );

				$result[ 'tempUrl' ] = "$uploadUrl/$tempUrl/$thumbName";
			}
			else {

				$result[ 'tempUrl' ] = "$uploadUrl/$tempUrl/$upname";
			}

			return $result;
		}
		else {

			return [ 'error' => "File save failed or file with same name alrady exist." ];
		}

		return [ 'error' => 'File upload failed.' ];
	}

	private function detectType( $extension ) {

		$type = null;

		if( in_array( $extension, $this->imageExtensions ) ) {

			$type = 'image';
		}
		else if( in_array( $extension, $this->videoExtensions ) ) {

			$type = 'video';
		}
		else if( in_array( $extension, $this->audioExtensions ) ) {

			$type = 'audio';
		}
		else if( in_array( $extension, $this->documentExtensions ) ) {

			$type = 'document';
		}
		else if( in_array( $extension, $this->compressedExtensions ) ) {

			$type = 'compressed';
		}

		return $type;
	}

	private function getAllowedExtensions( $type ) {

		$allowedExtensions = [];

		switch( $type ) {

			case self::FILE_TYPE_IMAGE: {

				$allowedExtensions = $this->imageExtensions;

				break;
			}
			case self::FILE_TYPE_VIDEO: {

				$allowedExtensions = $this->videoExtensions;

				break;
			}
			case self::FILE_TYPE_AUDIO: {

				$allowedExtensions = $this->audioExtensions;

				break;
			}
			case self::FILE_TYPE_DOCUMENT: {

				$allowedExtensions = $this->documentExtensions;

				break;
			}
			case self::FILE_TYPE_COMPRESSED: {

				$allowedExtensions = $this->compressedExtensions;

				break;
			}
			case self::FILE_TYPE_MIXED: {

				$allowedExtensions = $this->mixedExtensions;

				break;
			}
		}

		return $allowedExtensions;
	}

	// File Processing ---------------------------------------------

	public function processFile( $file ) {

		$dateDir	= date( 'Y-m-d' );
		$fileName	= $file->name;
		$fileExt	= $file->extension;
		$fileDir	= $file->directory;

		$uploadDir	= $this->uploadDir;

		$sourceFile	= "$fileDir/$fileName.$fileExt";
		$targetDir	= "$dateDir/$fileDir/";

		$filePath = $targetDir . "$fileName.$fileExt";

		// Update File Size in MB
		$fileContent	= file_get_contents( "$uploadDir/temp/$sourceFile" );
		$file->size		= number_format( strlen( $fileContent ) / 1048576, 8 );

		$this->saveFile( $sourceFile, $targetDir, $filePath );

		// Update URL and Thumb
		$file->url = $filePath;
	}

	public function saveFile( $sourceFile, $targetDir, $filePath ) {

		$sourceFile	= "$this->uploadDir/temp/$sourceFile";
		$targetDir	= "$this->uploadDir/$targetDir";
		$filePath	= "$this->uploadDir/$filePath";

		// create required directories if not exist
		if( !file_exists( $targetDir ) ) {

			mkdir( $targetDir, 0755, true );
		}

		// Move file from temp to destined directory
		rename( $sourceFile, $filePath );
	}

	// Image Processing --------------------------------------------

	// Save Image -------------

	public function processImage( $file, $config = [] ) {

		$config[ 'width' ]		= !empty( $config[ 'width' ] ) ? intval( $config[ 'width' ] ) : 0;
		$config[ 'height' ]		= !empty( $config[ 'height' ] ) ? intval( $config[ 'height' ] ) : 0;
		$config[ 'mwidth' ]		= !empty( $config[ 'mwidth' ] ) ? intval( $config[ 'mwidth' ] ) : 0;
		$config[ 'mheight' ]	= !empty( $config[ 'mheight' ] ) ? intval( $config[ 'mheight' ] ) : 0;
		$config[ 'swidth' ]		= !empty( $config[ 'swidth' ] ) ? intval( $config[ 'swidth' ] ) : 0;
		$config[ 'sheight' ]	= !empty( $config[ 'sheight' ] ) ? intval( $config[ 'sheight' ] ) : 0;
		$config[ 'twidth' ]		= !empty( $config[ 'twidth' ] ) ? intval( $config[ 'twidth' ] ) : 0;
		$config[ 'theight' ]	= !empty( $config[ 'theight' ] ) ? intval( $config[ 'theight' ] ) : 0;

		$dateDir	= date( 'Y-m-d' );
		$imageName	= $file->name;
		$imageExt	= $file->extension;
		$imageDir	= $file->directory;

		$uploadDir	= $this->uploadDir;

		$sourceFile	= "$imageDir/$imageName.$imageExt";
		$targetDir	= "$dateDir/$imageDir/";

		$imageUrl		= $targetDir . "$imageName.$imageExt";
		$imageMediumUrl	= $targetDir . "$imageName-medium.$imageExt";
		$imageSmallUrl	= $targetDir . "$imageName-small.$imageExt";
		$imageThumbUrl	= $targetDir . "$imageName-thumb.$imageExt";
		$imagePlUrl		= $targetDir . "$imageName-pl.$imageExt";
		$imagePlsUrl	= $targetDir . "$imageName-small-pl.$imageExt";

		// Update File Size in MB
		$fileContent	= file_get_contents( "$uploadDir/temp/$sourceFile" );
		$file->size		= number_format( strlen( $fileContent ) / 1048576, 8 );

		// Save Image
		$this->saveImage( $sourceFile, $targetDir, $imageUrl, $imageMediumUrl, $imageSmallUrl, $imageThumbUrl, $imagePlUrl, $imagePlsUrl, $config );

		// Update URL and Thumb
		$file->url = $imageUrl;

		if( $this->generateImageMedium ) {

			$file->medium = $imageMediumUrl;
		}

		if( $this->generateImageSmall ) {

			$file->small = $imageSmallUrl;
		}

		if( $this->generateImageThumb ) {

			$file->thumb = $imageThumbUrl;
		}

		if( $this->generateImagePl ) {

			$file->placeholder = $imagePlUrl;

			$file->smallPlaceholder = $imagePlsUrl;
		}
	}

	public function saveImage( $sourceFile, $targetDir, $filePath, $mediumPath, $smallPath, $thumbPath, $plPath, $plsPath, $config = [] ) {

		$uploadDir = $this->uploadDir;

		if( isset( $this->tempUploadDir ) ) {

			$uploadDir = $this->tempUploadDir;
		}

		$sourceFile	= "$uploadDir/temp/$sourceFile";
		$targetDir	= "$this->uploadDir/$targetDir";
		$filePath	= "$this->uploadDir/$filePath";
		$mediumPath	= "$this->uploadDir/$mediumPath";
		$smallPath	= "$this->uploadDir/$smallPath";
		$thumbPath	= "$this->uploadDir/$thumbPath";
		$plPath		= "$this->uploadDir/$plPath";
		$plsPath	= "$this->uploadDir/$plsPath";

		// create required directories if not exist
		if( !file_exists( $targetDir ) ) {

			mkdir( $targetDir, 0777, true );
		}

		// Move file from temp to destined directory
		rename( $sourceFile, $filePath );

		// Original width & height
		list( $width, $height ) = getimagesize( $filePath );

		$swidth		= $width;
		$sheight	= $height;

		$iwidth	 = $config[ 'width' ];
		$iheight = $config[ 'height' ];

		// Resize Image - Exact
		if( $iwidth > 1 && $iheight > 1 ) {

			// TODO: Add options to use max resolution for total resolution
			// TODO: Maintain actual ratio of width and height
			$width	 = $iwidth > $this->maxResolution ? $this->maxResolution : $iwidth;
			$height	 = $iheight > $this->maxResolution ? $this->maxResolution : $iheight;

			$resizeObj = new ImageUtil( $filePath );

			$resizeObj->resizeImage( $width, $height, 'exact' );
			$resizeObj->saveImage( $filePath, $this->imageQuality );
		}

		// Save Medium
		if( $this->generateImageMedium && isset( $mediumPath ) ) {

			$resizeObj = new ImageUtil( $filePath );

			$mwidth	 = $config[ 'mwidth' ];
			$mheight = $config[ 'mheight' ];

			// Resize Image - Exact
			if( $mwidth > 1 && $mheight > 1 ) {

				$mwidth	 = $mwidth > $this->maxResolution ? $this->maxResolution : $mwidth;
				$mheight = $mheight > $this->maxResolution ? $this->maxResolution : $mheight;

				$resizeObj->resizeImage( $mwidth, $mheight, 'exact' );
			}
			// Resize Image - Ratio - Crop
			else if( $this->mediumWidth > 0 && $this->mediumHeight > 0 ) {

				$resizeObj->resizeImage( $this->mediumWidth, $this->mediumHeight, 'crop' );
			}
			// Resize Image - Percent
			else {

				$mwidth	 = $width > 0 ? ( $width * $this->mediumPercent ) / 100 : $width;
				$mheight = $height > 0 ? ( $height * $this->mediumPercent ) / 100 : $height;

				$resizeObj->resizeImage( $mwidth, $mheight, 'exact' );
			}

			$resizeObj->saveImage( $mediumPath, $this->imageQuality );
		}

		// Save Small
		if( $this->generateImageSmall && isset( $smallPath ) ) {

			$resizeObj = new ImageUtil( $filePath );

			$swidth	 = $config[ 'swidth' ];
			$sheight = $config[ 'sheight' ];

			// Resize Image - Exact
			if( $swidth > 1 && $sheight > 1 ) {

				$swidth	 = $swidth > $this->maxResolution ? $this->maxResolution : $swidth;
				$sheight = $sheight > $this->maxResolution ? $this->maxResolution : $sheight;

				$resizeObj->resizeImage( $swidth, $sheight, 'exact' );
			}
			// Resize Image - Ratio - Crop
			else if( $this->smallWidth > 0 && $this->smallHeight > 0 ) {

				$resizeObj->resizeImage( $this->smallWidth, $this->smallHeight, 'crop' );
			}
			// Resize Image - Percent
			else {

				$swidth	 = $width > 0 ? ( $width * $this->smallPercent ) / 100 : $width;
				$sheight = $height > 0 ? ( $height * $this->smallPercent ) / 100 : $height;

				$resizeObj->resizeImage( $swidth, $sheight, 'exact' );
			}

			$resizeObj->saveImage( $smallPath, $this->imageQuality );
		}

		// Save Thumb
		if( $this->generateImageThumb && isset( $thumbPath ) ) {

			$resizeObj = new ImageUtil( $filePath );

			$twidth	 = $config[ 'twidth' ];
			$theight = $config[ 'theight' ];

			// Resize Image - Exact
			if( $twidth > 1 && $theight > 1 ) {

				$twidth	 = $twidth > $this->maxResolution ? $this->maxResolution : $twidth;
				$theight = $theight > $this->maxResolution ? $this->maxResolution : $theight;

				$resizeObj->resizeImage( $twidth, $theight, 'crop' );
			}
			// Resize Image - Ratio - Crop
			else if( $this->thumbWidth > 0 && $this->thumbHeight > 0 ) {

				$resizeObj->resizeImage( $this->thumbWidth, $this->thumbHeight, 'crop' );
			}

			$resizeObj->saveImage( $thumbPath, $this->imageQuality );
		}

		// Save Placeholder
		if( $this->generateImagePl && isset( $plPath ) ) {

			$imgObj = new ImageUtil( $filePath );

			$imgObj->resizeImage( $width, $height, 'exact' );
			$imgObj->applyGaussionBlurFilter( $this->blurRange );

			$imgObj->saveImage( $plPath, $this->plQuality );

			$imgObj = new ImageUtil( $filePath );

			$imgObj->resizeImage( $swidth, $sheight, 'exact' );
			$imgObj->applyGaussionBlurFilter( $this->blurRange );

			$imgObj->saveImage( $plsPath, $this->plQuality );
		}
	}

	// Convert to target DPI --

	private function convertToDPI( $jpgPath, $dpi ) {

		$filename	= $jpgPath;
		$input		= imagecreatefromjpeg( $filename );

		$width	= imagesx( $input );
		$height	= imagesy( $input );

		$output	= imagecreatetruecolor( $width, $height );
		$white	= imagecolorallocate( $output, 255, 255, 255 );

		imagefilledrectangle( $output, 0, 0, $width, $height, $white );
		imagecopy( $output, $input, 0, 0, 0, 0, $width, $height );

		ob_start();

		imagejpeg( $output );

		$contents = ob_get_contents();

		//Converting Image DPI to $dpi
		$contents = substr_replace( $contents, pack( "cnn", 1, $dpi, $dpi ), 13, 5 );

		ob_end_clean();

		file_put_contents( $filename, $contents );
	}

}
