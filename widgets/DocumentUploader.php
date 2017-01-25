<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

class DocumentUploader extends FileUploader {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= 'document';

	// file directory and type
	public $directory		= 'document';
	public $type			= 'document';
}

?>