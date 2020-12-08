<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\files\widgets;

// CMG Imports
use cmsgears\core\common\utilities\UrlUtil;

/**
 * ImageUploader widget is pre-configured to upload images.
 *
 * @since 1.0.0
 */
class ImageUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template	= 'image';

	// File - directory and type
	public $directory	= 'banner';
	public $type		= 'image';

	// File - model and model class for loading by controller
	public $modelClass	= 'Banner';

	// Widget - Container
	public $fileIcon	= 'cmti cmti-5x cmti-file-image';

	// Image and Thumbnail Dimensions
	public $width	= null;
	public $height	= null;
	public $mwidth	= null;
	public $mheight	= null;
	public $swidth	= null;
	public $sheight	= null;
	public $twidth	= null;
	public $theight	= null;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ImageUploader -------------------------

	public function renderInfo( $config = [] ) {

		$infoView = UrlUtil::isAbsolutePath( $this->infoView ) ? $this->infoView : "$this->template/$this->infoView";

        return $this->render( $infoView, [
			'widget' => $this,
			'width' => $this->width, 'height' => $this->height,
			'mwidth' => $this->mwidth, 'mheight' => $this->mheight,
			'swidth' => $this->swidth, 'sheight' => $this->sheight,
			'twidth' => $this->twidth, 'theight' => $this->theight
		] );
	}

}
