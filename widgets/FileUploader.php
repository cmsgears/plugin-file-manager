<?php
namespace cmsgears\files\widgets;

// Yii Imports
use \Yii;
use yii\helpers\Html;

class FileUploader extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $template		= 'image';

	// html options
	public $options		 	= [];

	// file model and model class for loading by controller
	public $model			= null;
	public $modelClass		= 'File';

	// file directory and type
	public $directory		= 'banner';
	public $type			= 'image';

	// file fields
	public $infoFields			= false;
	public $infoFieldsSeoOnly	= false;

	// uploader components
	public $postView		= true;
	public $btnChooserIcon	= "cmti cmti-edit";
	public $postViewIcon	= "cmti cmti-5x cmti-user";
	public $chooser			= true;
	public $preview			= true;
	public $preloader		= true;

	public $postAction			= false;
	public $postActionUrl		= null;
	public $postActionVisible	= false;
	public $postActionId		= 'file-uploader';
	public $cmtController		= 'default';
	public $cmtAction			= 'default';
	
	public $postUploadMessage	= null;

	// preview dimensions for drag/drop
	public $previewWidth	= 120;
	public $previewHeight	= 120;

	// Image and Thumbnail Dimensions
	public $width			= null;
	public $height			= null;
	public $twidth			= null;
	public $theight			= null;

	// Constructor and Initialisation ------------------------------

	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

		$html 					= $this->renderHtml();
		$options				= $this->options;
		$options['directory']	= $this->directory;
		$options['type']		= $this->type;

		return Html::tag( 'div', $html, $options );
    }

    public function renderHtml() {
		
		// views
		$postView		= $this->template . '/post-view';
		$chooser		= $this->template . '/file-chooser';
		$preview		= $this->template . '/file-preview';
		$preloader		= $this->template . '/preloader';
		$fields			= $this->template . '/fields';
		$infoFields		= $this->template . '/info-fields';
		$postAction		= $this->template . '/post-action';

		$postViewHtml 	= $this->render( $postView, [ 'postView' => $this->postView, 'model' => $this->model, 'btnChooserIcon' => $this->btnChooserIcon, 'postViewIcon'	=> $this->postViewIcon, 'postUploadMessage' => $this->postUploadMessage ] );
		$chooserHtml	= $this->render( $chooser, [ 'chooser' => $this->chooser ] );
		$previewHtml	= $this->render( $preview, [ 'preview' => $this->preview, 'previewWidth' => $this->previewWidth, 'previewHeight' => $this->previewHeight ] );
		$preloaderHtml	= $this->render( $preloader, [ 'preloader' => $this->preloader ] );
		$fieldsHtml		= $this->render( $fields, [ 'model' => $this->model, 'modelClass' => $this->modelClass, 'directory' => $this->directory, 'type' => $this->type, 'width' => $this->width, 'height' => $this->height, 'twidth' => $this->twidth, 'theight' => $this->theight ] );
		$infoFieldsHtml	= $this->render( $infoFields, [ 'infoFields' => $this->infoFields, 'infoFieldsSeoOnly' => $this->infoFieldsSeoOnly, 'model' => $this->model, 'modelClass' => $this->modelClass ] );
		$postActionHtml	= $this->render( $postAction, [ 'fieldsHtml' => $fieldsHtml, 'infoFieldsHtml' => $infoFieldsHtml, 'postAction' => $this->postAction, 'postActionUrl' => $this->postActionUrl, 'postActionVisible' => $this->postActionVisible, 'postActionId' => $this-> postActionId, 'cmtController' => $this-> cmtController, 'cmtAction' => $this->cmtAction ] );

		return $postViewHtml . "<div class='wrap-chooser'>" . $chooserHtml . $previewHtml . $preloaderHtml . "</div>" . $postActionHtml;
    }
}

?>