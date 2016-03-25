<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

abstract class FileUploader extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= null;

	// html options
	public $options		 	= [];

	// file model and model class for loading by controller
	public $model			= null;
	public $modelClass		= 'File';

	// file directory and type
	public $directory		= null;
	public $type			= null;

	// view - post view
	public $postView			= true;
	public $btnChooserIcon		= "cmti cmti-edit";
	public $postViewIcon		= "cmti cmti-5x cmti-file";
	public $postUploadMessage	= null;

	// view - chooser
	public $chooser			= true;

    // Disable Upload
    public $disabled        = false;

    // Disable Upload
    public $disabled        = false;

	// view - preview
	public $preview			= true;

	// view - pre-loader
	public $preloader		= true;

	// view - attributes
	public $hiddenInfo			= false; // This can be used in case we want to have fixed info fields. The info flag must be false in such cases.
	public $hiddenInfoFields	= [];

	// view - info
	public $info			= false;
	public $infoLabel		= false;
	public $infoFields		= [ 'title', 'description', 'alt', 'link' ];

	// view - post action
	public $postAction			= false;
	public $postActionUrl		= null;
	public $postActionVisible	= false;
	public $postActionId		= 'file-uploader';
	public $cmtController		= 'default';
	public $cmtAction			= 'default';

	// preview dimensions for drag/drop
	public $previewWidth	= 120;
	public $previewHeight	= 120;

	// Constructor and Initialisation ------------------------------

	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

		$options				= $this->options;
		$options['directory']	= $this->directory;
		$options['type']		= $this->type;

		$html 					= $this->renderWidget();

		return Html::tag( 'div', $html, $options );
    }

	// cmsgears\core\common\base\Widget

    public function renderWidget( $config = [] ) {

		// postview - view displayed by default and after file is uploaded to server.
		$postView		= $this->template . '/post-view';

		// chooser - it's used to display file chooser
		$chooser		= $this->template . '/file-chooser';

		// preview - It allows to preview the file if possible or we can also show other details like file name and size.
		$preview		= $this->template . '/file-preview';

		// preloader - It shows the overall progress of file being uploaded
		$preloader		= $this->template . '/preloader';

		// attributes - auto-filled by file uploader, after file is uploaded successfully
		$attributes		= $this->template . '/file-attributes';

		// attributes - to be filled by user after file is uploaded successfully
		$info			= $this->template . '/file-info';

		$postAction		= $this->template . '/post-action';

		$postViewHtml 	= $this->renderPostView( $postView );

		$chooserHtml	= $this->renderChooser( $chooser );

		$previewHtml	= $this->renderPreview( $preview );

		$preloaderHtml	= $this->renderPreLoader( $preloader );

		$attributesHtml	= $this->renderAttributes( $attributes );

		$infoHtml		= $this->renderInfo( $info );

		$postActionHtml	= $this->renderPostAction( $postAction, $attributesHtml, $infoHtml );

		return $postViewHtml . "<div class='wrap-chooser'>" . $chooserHtml . $previewHtml . $preloaderHtml . "</div>" . $postActionHtml;
    }

	// FileUploader

	protected function renderPostView( $postView ) {

		return $this->render( $postView, [ 'postView' => $this->postView, 'model' => $this->model, 'btnChooserIcon' => $this->btnChooserIcon, 'postViewIcon' => $this->postViewIcon, 'postUploadMessage' => $this->postUploadMessage, 'disabled' => $this->disabled ] );
	}

	protected function renderChooser( $chooser ) {

		return $this->render( $chooser, [ 'chooser' => $this->chooser, 'disabled' => $this->disabled ] );
	}

	protected function renderPreview( $preview ) {

		return $this->render( $preview, [ 'preview' => $this->preview, 'previewWidth' => $this->previewWidth, 'previewHeight' => $this->previewHeight, 'disabled' => $this->disabled ] );
	}

	protected function renderPreLoader( $preloader ) {

		return $this->render( $preloader, [ 'preloader' => $this->preloader ] );
	}

	protected function renderAttributes( $attributes ) {

		return $this->render( $attributes, [ 'model' => $this->model, 'modelClass' => $this->modelClass, 'directory' => $this->directory, 'type' => $this->type,
							'hiddenInfo' => $this->hiddenInfo, 'hiddenInfoFields' => $this->hiddenInfoFields ] );
	}

	protected function renderInfo( $info ) {

		return $this->render( $info, [ 'info' => $this->info, 'infoLabel' => $this->infoLabel, 'infoFields' => $this->infoFields, 'model' => $this->model, 'modelClass' => $this->modelClass ] );
	}

	protected function renderPostAction( $postAction, $attributesHtml, $infoHtml ) {

		return $this->render( $postAction, [ 'attributesHtml' => $attributesHtml, 'infoHtml' => $infoHtml, 'postAction' => $this->postAction, 'postActionUrl' => $this->postActionUrl, 'postActionVisible' => $this->postActionVisible, 'postActionId' => $this-> postActionId, 'cmtController' => $this-> cmtController, 'cmtAction' => $this->cmtAction ] );
	}
}

?>