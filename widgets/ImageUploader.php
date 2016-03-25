<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

class ImageUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= 'image';

	// file directory and type
	public $directory		= 'banner';
	public $type			= 'image';

	// uploader components
	public $postViewIcon	= 'cmti cmti-5x cmti-image';

	// Image and Thumbnail Dimensions
	public $width			= null;
	public $height			= null;
	public $twidth			= null;
	public $theight			= null;

	protected function renderAttributes( $attributes ) {

		return $this->render( $attributes, [ 'model' => $this->model, 'modelClass' => $this->modelClass, 'directory' => $this->directory, 'type' => $this->type,
					'hiddenInfo' => $this->hiddenInfo, 'hiddenInfoFields' => $this->hiddenInfoFields,
					'width' => $this->width, 'height' => $this->height, 'twidth' => $this->twidth, 'theight' => $this->theight
				]);
	}
}

?>