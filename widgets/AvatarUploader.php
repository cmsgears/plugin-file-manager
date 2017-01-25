<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

class AvatarUploader extends ImageUploader {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= 'avatar';

	// file directory and type
	public $directory		= 'avatar';

	// file model and model class for loading by controller
	public $modelClass		= 'Avatar';

	// uploader components
	public $postViewIcon		= 'cmti cmti-5x cmti-user';

	public $postActionUrl		= 'user/avatar';
	public $postActionId		= 'frm-ajax-avatar';
	public $cmtController		= 'default';
	public $cmtAction			= 'avatar';
}

?>