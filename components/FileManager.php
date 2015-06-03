<?php
namespace cmsgears\files\components;

// Yii Imports
use \Yii;
use yii\base\Component;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\files\utilities\ImageResize;

/**
 * The file manager accepts single file at a time uploaded by either xhr or file data using ajax post.
 * It also accept file data sent via Post using forms.
 */
class FileManager extends Component {

	// The extensions allowed by this file uploader.
	public $allowedExtensions 	= [ 'png', 'jpg', 'jpeg', 'gif', 'zip' , 'pdf' ];

	// Either of these must be set to true. Generate Name generate a unique name using Yii Security Component whereas pretty names use the file name provided by user and replace space by hyphen(-).
	public $generateName		= true;
	public $prettyNames			= false;

	// TODO - Check max size for the file uploaded by user
	public $maxSize				= 5; // In MB

	// Image Thumb Generation
	public $generateImageThumb	= true;
	public $thumbWidth			= 120;
	public $thumbHeight			= 120;

	// These must be set to allow file manager to work. The default values are set in constructor.
	public $uploadDir			= null;
	public $uploadUrl			= null;

	public function __construct() {

		$this->uploadDir	= Yii::getAlias( "@uploads" ) . "/";
		$this->uploadUrl 	= Url::to( Yii::getAlias( "@web" ). "/uploads/", true );
	}

	// File Uploading -------------------------------------------------------------------

	public function handleFileUpload( $selector ) {

		return $this->processFileUpload( $selector );
	}

	private function processFileUpload( $selector ) {

		// Get the filename submitted by user
		$filename = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : false );

		// Modern Style using Xhr
		if( $filename ) {

			$extension 	= pathinfo( $filename, PATHINFO_EXTENSION );
			
			// check allowed extensions
			if( in_array( strtolower( $extension ), $this->allowedExtensions ) ) {

				return $this->saveTempFile( file_get_contents( 'php://input' ), $selector, $filename, $extension );
			}
			else {

				echo "Error: extension not allowed.";
			}
		}
		// Modern Style using File Data
		else if( isset( $_POST[ 'fileData' ] ) && $_POST[ 'fileData' ] ) {

	        $filename = $_POST[ 'fileName' ];

			if( $filename ) {

				$extension 	= $_POST[ 'fileExtension' ];
				
				// check allowed extensions
				if( in_array( strtolower($extension), $this->allowedExtensions ) ) {

					// Decoding file data
					$file 	= $_POST[ 'file' ];
    				$file 	= str_replace( ' ', '+', $file );
    				$file 	= base64_decode( $file );

					return $this->saveTempFile( $file, $selector, $filename, $extension );
				}
				else {

					echo "Error: extension not allowed.";
				}
			}
		}
		// Legacy System using file
		else {

			if( isset( $_FILES['file'] ) ) {

			    if( $_FILES['file']['error'] > 0 ) {

			        echo 'Error: ' . $_FILES['file']['error'];
			    }
			    else {

			        $filename = $_FILES['file']['name'];

					if( $filename ) {

						$extension 	= pathinfo( $filename, PATHINFO_EXTENSION );

						// check allowed extensions
						if( in_array( strtolower($extension), $this->allowedExtensions ) ) {
							
							return $this->saveTempFile( file_get_contents( $_FILES['file']['name'] ), $selector, $filename, $extension );
						}
						else {

							echo "Error: extension not allowed.";
						}
					}
			    }
			}
		}

		return false;
	}

	private function saveTempFile( $file_contents, $selector, $filename, $extension ) {
		
		// Check allowed file size
		$sizeInMb = number_format( $file_contents / 1048576, 2 );
		
		if( $sizeInMb > $this->maxSize ) {
			
			echo "Error: Max size reached.";
			
			return false;			
		}

		// Create Directory if not exist
		$tempUrl		 = "temp/$selector/";
		$uploadDir = $this->uploadDir . $tempUrl;

		if( !file_exists( $uploadDir ) ) {

			mkdir( $uploadDir , 0777, true );
		}

		// Generate File Name
		$name		= $filename;

		if( $this->generateName ) {

			$name	= md5( date( 'Y-m-d H:i:s:u' ) );
		}
		else if( $this->prettyNames ) {

			$name	= str_replace( " ", "-", $filename );
		}

		$filename	= $name . "." . $extension;

		// Save File
		if( file_put_contents( $uploadDir . $filename, $file_contents ) ) {

			$result	= array();

			$result['name'] 		= $name;
			$result['extension'] 	= $extension;
			$result['tempUrl'] 		= $this->uploadUrl . $tempUrl . $filename;

			return $result;
		}
		else {

			echo "Error: File save failed or file with same name alrady exist.";	
		}

		return false;
	}

	// File Processing ------------------------------------------------------------------

	public function processFile( $file ) {

		$dateDir	= date( 'Y-m-d' );
		$fileName	= $file->name;
		$fileExt	= $file->extension;
		$fileDir	= $file->directory;

		$uploadUrl	= $this->uploadUrl;

		$sourceFile		= $fileDir . '/' . $fileName . '.' . $fileExt;
		$fileDir		= $dateDir . '/' . $fileDir . '/';
		$fileUrl		= $fileDir . $fileName . '.' . $fileExt;

		$this->saveFile( $sourceFile, $fileDir, $fileUrl );

		// Save Image File
		$file->directory	= $fileDir;
		$file->createdAt	= $date;
		$file->url			= $fileUrl;
	}

	public function saveFile( $sourceFile, $targetDir, $filePath ) {

		$sourceFile	= $this->uploadDir . "temp/" . $sourceFile;
		$targetDir	= $this->uploadDir . $targetDir;
		$filePath	= $this->uploadDir . $filePath;

		// create required directories if not exist
		if( !file_exists( $targetDir ) ) {

			mkdir( $targetDir , 0777, true );
		}

		// Move file from temp to destined directory
		rename( $sourceFile, $filePath );
	}	

	// Image Processing -----------------------------------------------------------------
	
	// Save Image -------------

	public function processImage( $file, $width = null, $height = null, $twidth = null, $theight = null ) {

		$dateDir	= date( 'Y-m-d' );
		$imageName	= $file->name;
		$imageExt	= $file->extension;
		$imageDir	= $file->directory;

		$uploadUrl	= $this->uploadUrl;

		$sourceFile		= $imageDir . '/' . $imageName . '.' . $imageExt;
		$targetDir		= $dateDir . '/' . $imageDir . '/';
		$imageUrl		= $targetDir . $imageName . '.' . $imageExt;
		$imageThumbUrl	= $targetDir . $imageName . '-thumb.' . $imageExt;
		
		// Save Image and Thumb
		$this->saveImageAndThumb( $sourceFile, $targetDir, $imageUrl, $imageThumbUrl, $width, $height, $twidth = null, $theight = null );
		
		// Update URL and Thumb
		$file->url			= $imageUrl;

		if( $this->generateImageThumb ) {

			$file->thumb	= $imageThumbUrl;
		}
	}

	public function saveImageAndThumb( $sourceFile, $targetDir, $filePath, $thumbPath, $width = null, $height = null, $twidth = null, $theight = null ) {

		$sourceFile	= $this->uploadDir . "temp/" . $sourceFile;
		$targetDir	= $this->uploadDir . $targetDir;
		$filePath	= $this->uploadDir . $filePath;
		$thumbPath	= $this->uploadDir . $thumbPath;

		// create required directories if not exist
		if( !file_exists( $targetDir ) ) {

			mkdir( $targetDir , 0777, true );
		}

		// Move file from temp to destined directory
		rename( $sourceFile, $filePath );

		// Resize Image
		if( isset( $width ) && isset( $height ) && intval( $width ) > 0 && intval( $height ) > 0 ) {

			$resizeObj = new ImageResize( $filePath );
			$resizeObj->resizeImage( $width, $height, 'exact' );
			$resizeObj->saveImage( $filePath, 100 );
		}

		// Save Thumb
		if( $this->generateImageThumb && isset( $thumbPath ) ) {

			$resizeObj = new ImageResize( $filePath );
			
			if( isset( $twidth ) && isset( $theight ) && intval( $twidth ) > 0 && intval( $theight ) > 0 ) {

				$resizeObj->resizeImage( $twidth, $theight, 'crop' );
			}
			else {
				
				$resizeObj->resizeImage( $this->thumbWidth, $this->thumbHeight, 'crop' );
			}

			$resizeObj->saveImage( $thumbPath, 100 );
		}
	}

	// Generate QR Code -------

	/*
	public function save_qr_code( $url ) {

		$date				= date('Y-m-d');
		$design_directory 	= $this->uploadDir . "temp/" . $date . "/";

		if( !file_exists( $design_directory ) ) {

			mkdir( $design_directory , 0777, true );
		}

		$name		= md5( date('Y-m-d H:i:s:u') );
		$filename	= $name . ".png";
		$filepath 	= $design_directory . $name . ".png";
		
		QRcode::png( $url,  $filepath, "L", 8, 4);

		return EmblmCommerce::$temp_images_dir . $date . "/" . $filename;;
	}
	*/

	// Convert to target DPI --
	
	private function convertTo300DPI( $jpgPath ) {

       	$filename 	= $jpgPath;
        $input 		= imagecreatefromjpeg( $filename );

        $width		= imagesx( $input );
        $height		= imagesy( $input );

        $output 	= imagecreatetruecolor( $width, $height );
        $white 		= imagecolorallocate( $output,  255, 255, 255 );

        imagefilledrectangle( $output, 0, 0, $width, $height, $white );
        imagecopy( $output, $input, 0, 0, 0, 0, $width, $height );

        ob_start();

        imagejpeg( $output );

        $contents =  ob_get_contents();
		
        //Converting Image DPI to 300DPI                
        $contents = substr_replace( $contents, pack( "cnn", 1, 300, 300 ), 13, 5 );
        ob_end_clean();

		file_put_contents( $filename, $contents );
	}
}

?>
