<?php
namespace cmsgears\files\widgets;

// CMG Imports
use cmsgears\core\common\utilities\CodeGenUtil;

class ImageUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template		= 'image';

	// File - directory and type
	public $directory		= 'banner';
	public $type			= 'image';

	// File - model and model class for loading by controller
	public $modelClass		= 'Banner';

	// Widget - Container
	public $fileIcon		= 'cmti cmti-5x cmti-image';

	// Image and Thumbnail Dimensions
	public $width			= null;
	public $height			= null;
	public $twidth			= null;
	public $theight			= null;

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

		$infoView		= CodeGenUtil::isAbsolutePath( $this->infoView ) ? $this->infoView : "$this->template/$this->infoView";

        return $this->render( $infoView, [
			'widget' => $this,
			'width' => $this->width, 'height' => $this->height, 'twidth' => $this->twidth, 'theight' => $this->theight
		]);
	}
}
