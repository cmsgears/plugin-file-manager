<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

class VideoUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= 'video';

	// file model and model class for loading by controller
	public $modelClass		= 'Video';

	// file directory and type
	public $directory		= 'video';
	public $type			= 'video';

	// uploader components
	public $postViewIcon	= 'cmti cmti-5x cmti-file-video';
}
