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
}

?>