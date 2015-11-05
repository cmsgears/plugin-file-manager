<?php
namespace cmsgears\files\widgets;

use \Yii;
use yii\web\View;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class AvatarUploader extends Widget {

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
	public $infoFields		= false;

	// uploader components
	public $postview		= true;
	public $btnChooserIcon	= "cmti cmti-edit";
	public $postviewIcon	= "cmti cmti-5x cmti-user";
	public $chooser			= true;
	public $preview			= true;
	public $preloader		= true;

	public $postaction			= false;
	public $postactionurl		= null;
	public $postactionvisible	= false;
	public $postactionid		= "frm-ajax-avatar";
	public $cmtcontroller		= 'default';
	public $cmtaction			= 'avatar';
	
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

		$postviewHtml	= $this->renderPostview();
		$chooserHtml	= $this->renderChooser();
		$previewHtml	= $this->renderPreview();
		$preloaderHtml	= $this->renderPreloader();
		$postactionHtml	= $this->renderPostaction();

		return $postviewHtml . "<div class='wrap-chooser'>" . $chooserHtml . $previewHtml . $preloaderHtml . "</div>" . $postactionHtml;
    }

    protected function renderPostview() {

		$postviewHtml	= '';
		$btnChooserIcon	= $this->btnChooserIcon;
		$postviewIcon	= $this->postviewIcon;

		if( $this->postview ) {

			if( isset( $this->model ) ) {

				$name			= $this->model->name;
				
				if( isset( $name ) ) {
					
					$url			= $this->model->getThumbUrl();

					$postviewHtml	= "<div class='postview'>
										<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>
										<div class='wrap-image'><img src='$url' class='fluid' /></div>
										<div class='message-upload'>$this->postUploadMessage</div>
							   		</div>";
				}
				else {

					$postviewHtml	= "<div class='postview'>
										<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>
										<div class='wrap-image'><span class='$postviewIcon'></span></div>
										<div class='message-upload'>$this->postUploadMessage</div>
							   		</div>";
				}
			}
			else {

				$postviewHtml	= "<div class='postview'>
										<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>
										<div class='wrap-image'><span class='$postviewIcon'></span></div>
										<div class='message-upload'>$this->postUploadMessage</div>
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

    protected function renderPostaction() {

		$fieldsHtml		= $this->renderFields();
		$infoFieldsHtml	= $this->renderInfoFields();

		$postactionHtml	= '';

		if( $this->postaction && isset( $this->postactionurl ) ) {

			$paClass = 'post-action';

			if( $this->postactionvisible ) {

				$paClass = 'post-action-v';
			}

			$postactionHtml	 = "<div class='$paClass'><form id='$this->postactionid' class='frm-ajax' cmt-controller='$this->cmtcontroller' cmt-action='$this->cmtaction' action='$this->postactionurl' method='post'>";
			$postactionHtml	.= $fieldsHtml . $infoFieldsHtml;
			$postactionHtml	.= "<input type='submit' value='Save' /> </form>";
			$postactionHtml	.= "</div>";
		}
		else {

			$postactionHtml	.= $fieldsHtml . $infoFieldsHtml;
		}

		return $postactionHtml;
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

				$infoFieldsHtml	= "<div class='fields'>
										<label>Title</label> <input type='text' name='$modelClass"."[title]' value='$model->title' />
										<label>Description</label> <input type='text' name='$modelClass"."[description]' value='$model->description' />
										<label>Alternate Text</label> <input type='text' name='$modelClass"."[altText]' value='$model->altText' />
										<label>Link</label> <input type='text' name='$modelClass"."[link]' value='$model->link' />
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

		return $infoFieldsHtml;
	}
}

?>