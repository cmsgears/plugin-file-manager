<?php
namespace cmsgears\files\widgets;

use \Yii;
use yii\web\View;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class FileUploader extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	// html options
	public $options		 	= [];

	// file model and model class for loading by controller
	public $model			= null;
	public $modelClass		= 'File';

	// file directory and type
	public $directory		= 'avatar';
	public $type			= 'image';

	// file fields
	public $infoFields			= false;
	public $infoFieldsSeoOnly	= false;

	// uploader components
	public $postview		= true;
	public $btnChooserIcon	= "cmt-action-icon cmt-edit";
	public $postviewIcon	= "cmt-icon cmt-user";
	public $chooser			= true;
	public $preview			= true;
	public $preloader		= true;

	public $postAction			= false;
	public $postActionUrl		= null;
	public $postActionVisible	= false;
	public $postActionId		= 'file-uploader';
	public $cmtController		= 'default';
	public $cmtAction			= 'default';

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

		$postviewHtml	= $this->renderPostview();
		$chooserHtml	= $this->renderChooser();
		$previewHtml	= $this->renderPreview();
		$preloaderHtml	= $this->renderPreloader();
		$postActionHtml	= $this->renderpostAction();

		return $postviewHtml . "<div class='wrap-chooser'>" . $chooserHtml . $previewHtml . $preloaderHtml . "</div>" . $postActionHtml;
    }

    protected function renderPostview() {

		$postviewHtml	= '';
		$btnChooserIcon	= $this->btnChooserIcon;
		$postviewIcon	= $this->postviewIcon;

		if( $this->postview ) {

			if( isset( $this->model ) ) {

				$name			= $this->model->name;
				
				if( isset( $name ) ) {
					
					$url			= $this->model->getFileUrl();

					$postviewHtml	= "<div class='postview'>
										<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>
										<div class='wrap-image'><img src='$url' class='fluid' /></div>
							   		</div>";
				}
				else {

					$postviewHtml	= "<div class='postview'>
										<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>
										<div class='wrap-image'><span class='$postviewIcon'></span></div>
							   		</div>";
				}
			}
			else {

				$postviewHtml	= "<div class='postview'>
										<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>
										<div class='wrap-image'><span class='$postviewIcon'></span></div>
								   </div>";
			}
		}

		return $postviewHtml;
    }

    protected function renderChooser() {

		$chooserHtml	= '';

		// File Chooser
		if( $this->chooser ) {

			$chooserHtml	= "<div class='chooser'>
									<div class='btn'>Choose Image
										<input type='file' class='input' />
									</div>
							   </div>";
		}
		
		return $chooserHtml;
	}

    protected function renderPreview() {

		$previewHtml	= '';

		// Preview for Drag/Drop
		if( $this->preview ) {

			$previewHtml	= "<div class='preview'>
									<div class='wrap-drag'>
										<div class='drag'>Drag here</div>
										<canvas class='canvas' width='$this->previewWidth' height='$this->previewHeight' ></canvas>
									</div>
							   </div>";
		}

		return $previewHtml;
	}
	
    protected function renderPreloader() {

		$preloaderHtml	= '';

		// Pre-Loader
		if( $this->preloader ) {

			$preloaderHtml	= "<div class='preloader'>
									<div class='preloader-bar'></div>
							   </div>";
		}

		return $preloaderHtml;
	}

    protected function renderpostAction() {

		$fieldsHtml		= $this->renderFields();
		$infoFieldsHtml	= $this->renderInfoFields();

		$postActionHtml	= '';

		if( $this->postAction && isset( $this->postActionUrl ) ) {

			$paClass = 'post-action';

			if( $this->postActionVisible ) {

				$paClass = 'post-action-v';
			}
			
			$postActionUrl	= Url::toRoute( [ $this->postActionUrl ], true );
			$postActionHtml	= "<div class='$paClass'><form id='$this->postActionId' class='cmt-form' cmt-controller='$this->cmtController' cmt-action='$this->cmtAction' action='$postActionUrl' method='post'>";
			$postActionHtml	.= $fieldsHtml . $infoFieldsHtml;
			$postActionHtml	.= "<input type='submit' value='Save' /> </form>";
			$postActionHtml	.= "</div>";
		}
		else {

			$postActionHtml	.= $fieldsHtml . $infoFieldsHtml;
		}

		return $postActionHtml;
	}

    protected function renderFields() {

		$fieldsHtml		= '';
		$directory		= $this->directory;
		$type			= $this->type;

		// File Fields
		if( isset( $this->model ) ) {

			$model 			= $this->model;
			$modelClass		= $this->modelClass;
			$fieldsHtml 	= "<div class='fields'>
									<input type='hidden' name='$modelClass"."[id]' value='$model->id' />
									<input type='hidden' name='$modelClass"."[name]' class='name' value='$model->name' />
									<input type='hidden' name='$modelClass"."[extension]' class='extension' value='$model->extension' />
									<input type='hidden' name='$modelClass"."[directory]' value='$directory' />
									<input type='hidden' name='$modelClass"."[changed]' class='change' value='$model->changed' />
									<input type='hidden' name='$modelClass"."[width]' value='$this->width' />
									<input type='hidden' name='$modelClass"."[height]' value='$this->height' />
									<input type='hidden' name='$modelClass"."[twidth]' value='$this->twidth' />
									<input type='hidden' name='$modelClass"."[theight]' value='$this->theight' />
								</div>";
		}
		else {

			$modelClass		= $this->modelClass;
			$fieldsHtml 	= "<div class='fields'>
									<input type='hidden' name='$modelClass"."[name]' class='name' />
									<input type='hidden' name='$modelClass"."[extension]' class='extension' />
									<input type='hidden' name='$modelClass"."[directory]' value='$directory' />
									<input type='hidden' name='$modelClass"."[changed]' class='change' />
									<input type='hidden' name='$modelClass"."[width]' value='$this->width' />
									<input type='hidden' name='$modelClass"."[height]' value='$this->height' />
									<input type='hidden' name='$modelClass"."[twidth]' value='$this->twidth' />
									<input type='hidden' name='$modelClass"."[theight]' value='$this->theight' />
								</div>";
		}

		return $fieldsHtml;
	}

	protected function renderInfoFields() {

		$infoFieldsHtml = '';

		// File Fields
		if( $this->infoFields ) {

			$model		= $this->model;
			$modelClass	= $this->modelClass;

			if( isset( $model ) ) {
				
				if( $this ->infoFieldsSeoOnly ) {

					$infoFieldsHtml	= "<div class='fields'>
											<label>Alternate Text</label> <input type='text' name='$modelClass"."[altText]' value='$model->altText' />
										</div>";
				}
				else {

					$infoFieldsHtml	= "<div class='fields'>
											<label>Title</label> <input type='text' name='$modelClass"."[title]' value='$model->title' />
											<label>Description</label> <input type='text' name='$modelClass"."[description]' value='$model->description' />
											<label>Alternate Text</label> <input type='text' name='$modelClass"."[altText]' value='$model->altText' />
											<label>Link</label> <input type='text' name='$modelClass"."[link]' value='$model->link' />
										</div>";
				}
			}
			else {

				if( $this ->infoFieldsSeoOnly ) {

					$infoFieldsHtml	= "<div class='fields'>
											<label>Alternate Text</label> <input type='text' name='$modelClass"."[altText]' />
										</div>";
				}
				else {

					$infoFieldsHtml	= "<div class='fields'>
											<label>Title</label> <input type='text' name='$modelClass"."[title]' />
											<label>Description</label> <input type='text' name='$modelClass"."[description]' />
											<label>Alternate Text</label> <input type='text' name='$modelClass"."[altText]' />
											<label>Link</label> <input type='text' name='$modelClass"."[link]' />
										</div>";
				}
			}
		}

		return $infoFieldsHtml;
	}
}

?>