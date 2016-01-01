<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

class AvatarUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= 'avatar';

	// file directory and type
	public $directory		= 'avatar';

	// uploader components
	public $postActionUrl		= '/apix/user/avatar';
	public $postActionId		= "frm-ajax-avatar";
	public $cmtController		= 'default';
	public $cmtAction			= 'avatar';
}

?>