<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\files\config;

// CMG Imports
use cmsgears\core\common\config\Properties;

/**
 * FileProperties provide methods to access the properties specific to file management.
 *
 * @since 1.0.0
 */
class FileProperties extends Properties {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const CONFIG_FILE				= 'file';

	const PROP_EXTENSION_IMAGE 		= 'image_extensions';

	const PROP_EXTENSION_VIDEO 		= 'video_extensions';

	const PROP_EXTENSION_AUDIO 		= 'audio_extensions';

	const PROP_EXTENSION_DOCUMENT 	= 'document_extensions';

	const PROP_EXTENSION_COMPRESSED = 'compressed_extensions';

	const PROP_IMAGE_QUALITY		= 'image_quality';

	const PROP_NAME_GENERATE		= 'generate_name';

	const PROP_NAME_PRETTY			= 'pretty_name';

	const PROP_MAX_SIZE				= 'max_size';

	const PROP_MAX_RESOLUTION		= 'max_resolution';

	const PROP_GENERATE_MEDIUM		= 'generate_medium';

	const PROP_GENERATE_SMALL		= 'generate_medium';

	const PROP_GENERATE_THUMB		= 'generate_thumb';

	const PROP_GENERATE_PLACEHOLDER	= 'generate_placeholder';

	const PROP_MEDIUM_WIDTH			= 'medium_width';

	const PROP_MEDIUM_HEIGHT		= 'medium_height';

	const PROP_SMALL_WIDTH			= 'small_width';

	const PROP_SMALL_HEIGHT			= 'small_height';

	const PROP_THUMB_WIDTH			= 'thumb_width';

	const PROP_THUMB_HEIGHT			= 'thumb_height';

	const PROP_UPLOAD				= 'uploads';

	const PROP_UPLOAD_DIR			= 'uploads_directory';

	const PROP_UPLOAD_URL			= 'uploads_url';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

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

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileProperties ------------------------

	/**
	 * Returns the extensions allowed for images.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getImageExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_IMAGE ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return preg_split( "/,/", $prop );
		}

		return $default;
	}

	/**
	 * Returns the extensions allowed for videos.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getVideoExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_VIDEO ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return preg_split( "/,/", $prop );
		}

		return $default;
	}

	/**
	 * Returns the extensions allowed for music and audio.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getAudioExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_AUDIO ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return preg_split( "/,/", $prop );
		}

		return $default;
	}

	/**
	 * Returns the extensions allowed for documents.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getDocumentExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_DOCUMENT ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return preg_split( "/,/", $prop );
		}

		return $default;
	}

	/**
	 * Returns the extensions allowed for compressed documents.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getCompressedExtensions( $default = null ) {

		$prop = $this->properties[ self::PROP_EXTENSION_COMPRESSED ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return preg_split( "/,/", $prop );
		}

		return $default;
	}

	/**
	 * Returns the quality of saved image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getImageQuality( $default = null ) {

		$prop = $this->properties[ self::PROP_IMAGE_QUALITY ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Check whether name must be auto generated while storing the file. The given file
	 * name will be stored in title.
	 *
	 * @param type $default
	 * @return string
	 */
	public function isGenerateName( $default = null ) {

		$prop = $this->properties[ self::PROP_NAME_GENERATE ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Check whether pretty names must be generated by hyphenating the given file name. The
	 * given file name will be stored in title.
	 *
	 * @param type $default
	 * @return string
	 */
	public function isPrettyName( $default = null ) {

		$prop = $this->properties[ self::PROP_NAME_PRETTY ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the maximum size allowed for file.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getMaxSize( $default = null ) {

		$prop = $this->properties[ self::PROP_MAX_SIZE ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the maximum resolution allowed for image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getMaxResolution( $default = null ) {

		$prop = $this->properties[ self::PROP_MAX_RESOLUTION ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Check whether medium image must be generated while storing the image file.
	 *
	 * @param type $default
	 * @return string
	 */
	public function isGenerateMedium( $default = null ) {

		$prop = $this->properties[ self::PROP_GENERATE_MEDIUM ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Check whether small image must be generated while storing the image file.
	 *
	 * @param type $default
	 * @return string
	 */
	public function isGenerateSmall( $default = null ) {

		$prop = $this->properties[ self::PROP_GENERATE_SMALL ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Check whether thumb image must be generated while storing the image file.
	 *
	 * @param type $default
	 * @return string
	 */
	public function isGenerateThumb( $default = null ) {

		$prop = $this->properties[ self::PROP_GENERATE_THUMB ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Check whether placeholder image must be generated while storing the image file.
	 *
	 * @param type $default
	 * @return string
	 */
	public function isGeneratePlaceholder( $default = null ) {

		$prop = $this->properties[ self::PROP_GENERATE_PLACEHOLDER ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the width of medium image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getMediumWidth( $default = null ) {

		$prop = $this->properties[ self::PROP_MEDIUM_WIDTH ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the height of medium image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getMediumHeight( $default = null ) {

		$prop = $this->properties[ self::PROP_MEDIUM_HEIGHT ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the width of small image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getSmallWidth( $default = null ) {

		$prop = $this->properties[ self::PROP_SMALL_WIDTH ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the height of small image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getSmallHeight( $default = null ) {

		$prop = $this->properties[ self::PROP_SMALL_HEIGHT ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the width of thumb image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getThumbWidth( $default = null ) {

		$prop = $this->properties[ self::PROP_THUMB_WIDTH ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the height of medium image.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getThumbHeight( $default = null ) {

		$prop = $this->properties[ self::PROP_THUMB_HEIGHT ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Checks whether file upload is allowed.
	 *
	 * @param type $default
	 * @return boolean
	 */
	public function isUpload( $default = null ) {

		$prop = $this->properties[ self::PROP_UPLOAD ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the uploads directory path to which files will be stored.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getUploadDir( $default = null ) {

		$prop = $this->properties[ self::PROP_UPLOAD_DIR ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

	/**
	 * Returns the uploads URL path to access file via URL.
	 *
	 * @param type $default
	 * @return string
	 */
	public function getUploadUrl( $default = null ) {

		$prop = $this->properties[ self::PROP_UPLOAD_URL ];

		if( isset( $prop ) && strlen( $prop ) > 0 ) {

			return $prop;
		}

		return $default;
	}

}
