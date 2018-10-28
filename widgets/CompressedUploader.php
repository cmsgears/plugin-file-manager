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
 * CompressedUploader widget is pre-configured to upload compressed files.
 *
 * @since 1.0.0
 */
class CompressedUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template = 'document';

	// File - directory and type
	public $directory	= 'document';
	public $type		= 'compressed';

	public $fileIcon = 'icon cmti cmti-5x cmti-file-archive';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// DocumentUploader ----------------------

}
