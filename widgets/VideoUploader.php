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
 * VideoUploader widget is pre-configured to upload videos.
 *
 * @since 1.0.0
 */
class VideoUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template = 'video';

	// File - directory and type
	public $directory	= 'video';
	public $type		= 'video';

	// File - model and model class for loading by controller
	public $modelClass = 'Video';

	// Widget - Container
	public $fileIcon = 'cmti cmti-5x cmti-file-video';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// VideoUploader -------------------------

}
