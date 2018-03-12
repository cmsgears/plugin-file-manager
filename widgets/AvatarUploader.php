<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\files\widgets;

/**
 * AvatarUploader widget is pre-configured to upload avatar.
 *
 * @since 1.0.0
 */
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
	public $fileIcon		= 'cmti cmti-5x cmti-user';

	public $postActionUrl	= 'user/avatar';

	public $cmtApp			= 'user';
	public $cmtController	= 'user';
	public $cmtAction		= 'avatar';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AvatarUploader ------------------------

}
