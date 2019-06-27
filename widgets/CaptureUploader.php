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
 * CaptureUploader widget is pre-configured to upload image from camera.
 *
 * @since 1.0.0
 */
class CaptureUploader extends ImageUploader {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template		= 'capture';

	// File - directory and type
	public $directory		= 'capture';

	// File - model and model class for loading by controller
	public $modelClass		= 'Capture';

	// Widget - Container
	public $fileIcon		= 'cmti cmti-5x cmti-camera';

	public $parentId;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CaptureUploader -----------------------

}
