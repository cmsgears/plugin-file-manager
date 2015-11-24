<?php
namespace cmsgears\files\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CmgProperties;

class FileProperties extends CmgProperties {

	const CONFIG_FILE			= 'file';

	const PROP_EXTENSION_IMAGE 	= 'Image Extensions';

	const PROP_EXTENSION_VIDEO 	= 'Video Extensions';

	const PROP_EXTENSION_DOC 	= 'Doc Extensions';

	const PROP_EXTENSION_ZIP 	= 'Zip Extensions';

	const PROP_NAME_GENERATE	= 'Generate Name';

	const PROP_NAME_PRETTY		= 'Pretty Names';

	const PROP_MAX_SIZE			= 'Max Size';

	const PROP_GENERATE_THUMB	= 'Generate Thumb';
	
	const PROP_THUMB_WIDTH		= 'Thumb Width';

	const PROP_THUMB_HEIGHT		= 'Thumb Height';

	const PROP_UPLOAD_DIR		= 'Uploads Directory';

	const PROP_UPLOAD_URL		= 'Uploads URL';

	// Singleton instance
	private static $instance;

	// Constructor and Initialisation ------------------------------

 	private function __construct() {

	}

	/**
	 * Return Singleton instance.
	 */
	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new FileProperties();

			self::$instance->init( self::CONFIG_FILE );
		}

		return self::$instance;
	}

	public function getImageExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_IMAGE ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return preg_split( "/,/", $prop );
		}
		
		return $default;
	}

	public function getVideoExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_VIDEO ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return preg_split( "/,/", $prop );
		}
		
		return $default;
	}

	public function getDocExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_DOC ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return preg_split( "/,/", $prop );
		}
		
		return $default;
	}

	public function getZipExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_ZIP ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return preg_split( "/,/", $prop );
		}
		
		return $default;
	}

	public function isGenerateName( $default = null ) {

		$prop = $this->properties[ self::PROP_NAME_GENERATE ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function isPrettyName( $default = null ) {

		$prop = $this->properties[ self::PROP_NAME_PRETTY ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function getMaxSize( $default = null ) {

		$prop = $this->properties[ self::PROP_MAX_SIZE ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function isGenerateThumb( $default = null ) {

		$prop = $this->properties[ self::PROP_GENERATE_THUMB ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function getThumbWidth( $default = null ) {

		$prop = $this->properties[ self::PROP_THUMB_WIDTH ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function getThumbHeight( $default = null ) {

		$prop = $this->properties[ self::PROP_THUMB_HEIGHT ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function getUploadDir( $default = null ) {

		$prop = $this->properties[ self::PROP_UPLOAD_DIR ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {
			
			return $prop;
		}

		return $default;
	}

	public function getUploadUrl( $default = null ) {

		$prop = $this->properties[ self::PROP_UPLOAD_URL ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}
}

?>