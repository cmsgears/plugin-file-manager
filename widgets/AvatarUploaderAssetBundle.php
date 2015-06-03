<?php
namespace cmsgears\files\widgets;

// Yii Imports
use yii\web\AssetBundle;
use yii\web\View;

class AvatarUploaderAssetBundle extends AssetBundle {

	// Constructor and Initialisation ------------------------------

	public function __construct()  {

		parent::__construct();

		// Path Configuration

	    $this->sourcePath = dirname( __DIR__ ) . '/widgets/resources';

		// Load CSS
 
	    $this->css     = [

	    ];

		// Load Javascript

	    $this->js      = [
	            "scripts/cmt-image-editor.js"
	    ];

		// Define the Position to load Assets
	    $this->jsOptions = [
	        "position" => View::POS_END
	    ];

		// Define dependent Asset Loaders
	    $this->depends = [
			'yii\web\JqueryAsset'
	    ];
	}
}

?>