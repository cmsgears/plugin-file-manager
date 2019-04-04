<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\files\utilities;

/**
 * The image resize utility provide several useful methods to resize an image.
 *
 * @since 1.0.0
 */
class ImageUtil {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $image;

	private $width;
	private $height;

	private $imageResized;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	function __construct( $fileName ) {

		$this->image = $this->openImage( $fileName );

		$this->width	= imagesx( $this->image );
		$this->height	= imagesy( $this->image );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ImageUtil -----------------------------

	public function getWidth() {

		return $this->width;
	}

	public function getHeight() {

		return $this->height;
	}

	/**
	 * Open the image using the image library shipped with PHP.
	 *
	 * @param string $file
	 * @return resource an image resource identifier on success, <b>FALSE</b> on errors.
	 */
	private function openImage( $file ) {

		$extension = strtolower( strrchr( $file, '.' ) );

		switch( $extension ) {

			// JPEG
			case '.jpg':
			case '.jpeg': {

				$img = @imagecreatefromjpeg( $file );

				break;
			}
			// GIF
			case '.gif': {

				$img = @imagecreatefromgif( $file );

				break;
			}
			// PNG
			case '.png': {

				$img = @imagecreatefrompng( $file );

				break;
			}
			// None
			default: {

				$img = false;

				break;
			}
		}

		return $img;
	}

	// Resize Image ------------------------------------------------

	public function resizeImage( $newWidth, $newHeight, $option = 'auto' ) {

		// Get optimal width and height - based on $option
		$optionArray = $this->getDimensions( $newWidth, $newHeight, $option );

		$optimalWidth	= $optionArray[ 'optimalWidth' ];
		$optimalHeight	= $optionArray[ 'optimalHeight' ];

		// Resample - create image canvas of x, y size
		$this->imageResized = imagecreatetruecolor( $optimalWidth, $optimalHeight );

		imagecopyresampled( $this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height );

		// If option is 'crop', then crop too
		if( $option == 'crop' ) {

			$this->crop( $optimalWidth, $optimalHeight, $newWidth, $newHeight );
		}
	}

	// Image Dimensions --------------------------------------------

	private function getDimensions( $newWidth, $newHeight, $option ) {

		switch( $option ) {

			case 'exact': {

				$optimalWidth	= $newWidth;
				$optimalHeight	= $newHeight;

				break;
			}
			case 'portrait': {

				$optimalWidth	= $this->getSizeByFixedHeight( $newHeight );
				$optimalHeight	= $newHeight;

				break;
			}
			case 'landscape': {

				$optimalWidth	= $newWidth;
				$optimalHeight	= $this->getSizeByFixedWidth( $newWidth );

				break;
			}
			case 'auto': {

				$optionArray	= $this->getSizeByAuto( $newWidth, $newHeight );
				$optimalWidth	= $optionArray[ 'optimalWidth' ];
				$optimalHeight	= $optionArray[ 'optimalHeight' ];

				break;
			}
			case 'crop': {

				$optionArray	= $this->getOptimalCrop( $newWidth, $newHeight );
				$optimalWidth	= $optionArray[ 'optimalWidth' ];
				$optimalHeight	= $optionArray[ 'optimalHeight' ];

				break;
			}
		}

		return [ 'optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight ];
	}

	private function getSizeByFixedHeight( $newHeight ) {

		$ratio		= $this->width / $this->height;
		$newWidth	= $newHeight * $ratio;

		return $newWidth;
	}

	private function getSizeByFixedWidth( $newWidth ) {

		$ratio		= $this->height / $this->width;
		$newHeight	= $newWidth * $ratio;

		return $newHeight;
	}

	private function getSizeByAuto( $newWidth, $newHeight ) {

		if( $this->height < $this->width ) {

			// Image to be resized is wider (landscape)
			$optimalWidth	= $newWidth;
			$optimalHeight	= $this->getSizeByFixedWidth( $newWidth );
		}
		elseif( $this->height > $this->width ) {

			// Image to be resized is taller (portrait)
			$optimalWidth	= $this->getSizeByFixedHeight( $newHeight );
			$optimalHeight	= $newHeight;
		}
		else {

			// Image to be resizerd is a square
			if( $newHeight < $newWidth ) {

				$optimalWidth	= $newWidth;
				$optimalHeight	= $this->getSizeByFixedWidth( $newWidth );
			}
			else if( $newHeight > $newWidth ) {

				$optimalWidth	= $this->getSizeByFixedHeight( $newHeight );
				$optimalHeight	= $newHeight;
			}
			else {

				// *** Sqaure being resized to a square
				$optimalWidth	= $newWidth;
				$optimalHeight	= $newHeight;
			}
		}

		return [ 'optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight ];
	}

	private function getOptimalCrop( $newWidth, $newHeight ) {

		$heightRatio = $this->height / $newHeight;
		$widthRatio	 = $this->width / $newWidth;

		if( $heightRatio < $widthRatio ) {

			$optimalRatio = $heightRatio;
		}
		else {

			$optimalRatio = $widthRatio;
		}

		$optimalHeight	= $this->height / $optimalRatio;
		$optimalWidth	= $this->width / $optimalRatio;

		return [ 'optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight ];
	}

	private function crop( $optimalWidth, $optimalHeight, $newWidth, $newHeight ) {

		// Find center - this will be used for the crop
		$cropStartX	 = ( $optimalWidth / 2) - ( $newWidth / 2 );
		$cropStartY	 = ( $optimalHeight / 2) - ( $newHeight / 2 );

		$crop = $this->imageResized;

		//imagedestroy($this->imageResized);

		// Now crop from center to exact requested size
		$this->imageResized = imagecreatetruecolor( $newWidth, $newHeight );

		imagecopyresampled( $this->imageResized, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth, $newHeight );
	}

	// Save Image --------------------------------------------------

	/**
	 * Save the image with given quality.
	 *
	 * @param string $savePath
	 * @param integer $imageQuality
	 */
	public function saveImage( $savePath, $imageQuality = 100 ) {

		// Get extension
		$extension	 = strrchr( $savePath, '.' );
		$extension	 = strtolower( $extension );

		switch( $extension ) {

			case '.jpg':
			case '.jpeg': {

				if( imagetypes() & IMG_JPG ) {

					$input = imagejpeg( $this->imageResized, $savePath, $imageQuality );
				}

				break;
			}
			case '.gif': {

				if( imagetypes() & IMG_GIF ) {

					imagegif( $this->imageResized, $savePath );
				}

				break;
			}
			case '.png': {

				// Scale quality from 0-100 to 0-9
				$scaleQuality = round( ( $imageQuality / 100 ) * 9 );

				// Invert quality setting as 0 is best, not 9
				$invertScaleQuality = 9 - $scaleQuality;

				if( imagetypes() & IMG_PNG ) {

					imagepng( $this->imageResized, $savePath, $invertScaleQuality );
				}

				break;
			}
			default: {

				// No extension - No save.
			}
		}

		imagedestroy( $this->imageResized );
	}

	// Image Filters

	public function applyGaussionBlurFilter( $blurRange = 5 ) {

		$this->imageResized = $this->image;

		for( $i = 0; $i <= $blurRange; $i++ ) {

			imagefilter( $this->imageResized, IMG_FILTER_GAUSSIAN_BLUR );
		}
	}

}
