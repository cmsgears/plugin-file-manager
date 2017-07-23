<?php
namespace cmsgears\files\widgets;

class AvatarUploader extends ImageUploader {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template		= 'avatar';

	// File - directory and type
	public $directory		= 'avatar';

	// File - model and model class for loading by controller
	public $modelClass		= 'Avatar';

	// Widget - Container
	public $fileIcon			= 'cmti cmti-5x cmti-user';

	public $postActionUrl		= 'user/avatar';
	public $cmtApp				= 'user';
	public $cmtController		= 'user';
	public $cmtAction			= 'avatar';

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

	// AvatarUploader ------------------------

}
