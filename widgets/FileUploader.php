<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\files\widgets;

// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\base\Widget;

use cmsgears\core\common\utilities\CodeGenUtil;

/**
 * FileUploader is the base widget to upload files.
 *
 * @since 1.0.0
 */
abstract class FileUploader extends Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $wrap		= true;

	// Widget - Template
	public $template	= null;

	// Widget - Html options
	public $options		= [ 'class' => 'box box-file-uploader file-uploader' ];

	// Widget - Options - Disable Upload
	public $disabled	= false;

	// File - directory and type
	public $directory	= null;
	public $type		= null;

	// File - model and model class for loading by controller
	public $model		= null;
	public $modelClass	= 'File';

	// Widget - Uploader
	public $uploaderView	= 'uploader';
	public $chooserIcon		= 'cmti cmti-edit';

	// Widget - Container
	public $container		= true;
	public $containerView	= 'container';
	public $fileIcon		= 'icon cmti cmti-5x cmti-file';
	public $uploadMessage	= null;

	// Widget - Dragger
	public $dragger			= true;
	public $draggerView		= 'dragger';

	// Widget - Dragger - preview dimensions for drag/drop
	public $previewWidth	= 120;
	public $previewHeight	= 120;

	// Widget - Chooser
	public $chooser		= true;
	public $chooserView	= 'chooser';

	// Widget - Preloader
	public $preloader		= true;
	public $preloaderView	= 'preloader';

	// Widget - Info - Used to auto collect file info
	public $info			= true;
	public $infoView		= 'info';
	public $additionalInfo	= false;
	public $infoFields		= []; // Useful only if $additionalInfo is true

	// Widget - Fields - Used to collect file info from user
	public $fields		= true;
	public $showFields	= false;
	public $fieldsView	= 'fields';
	public $fileLabel	= false;
	public $fileFields	= [ 'title', 'description', 'alt', 'link' ];

	// Widget - Form
	public $form		= true;
	public $formView	= 'form';

	// Widget - Form Post action
	public $postAction			= false;
	public $postActionUrl		= null;
	public $postActionVisible	= false;

	// CMT - JS - Application configuration
	public $cmtApp			= 'main';
	public $cmtController	= 'default';
	public $cmtAction		= 'file';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	public function run() {

		$this->options[ 'directory' ]	= $this->directory;
		$this->options[ 'type' ]		= $this->type;

		return $this->renderWidget();
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// cmsgears\core\common\base\Widget

	public function renderWidget( $config = [] ) {

		$containerHtml	= $this->container ? $this->renderContainer( $config ) : null;

		$draggerHtml	= $this->dragger ? $this->renderDragger( $config ) : null;

		$chooserHtml	= $this->chooser ? $this->renderChooser( $config ) : null;

		$preloaderHtml	= $this->preloader ? $this->renderPreloader( $config ) : null;

		$infoHtml		= $this->info ? $this->renderInfo( $config ) : null;

		$fieldsHtml		= $this->fields ? $this->renderFields( $config ) : null;

		$formHtml		= $this->form ? $this->renderForm( $config, [ 'infoHtml' => $infoHtml, 'fieldsHtml' => $fieldsHtml ] ) : null;

		$uploaderView	= CodeGenUtil::isAbsolutePath( $this->uploaderView ) ? $this->uploaderView : "$this->template/$this->uploaderView";

		$widgetHtml = $this->render( $uploaderView, [
			'widget' => $this,
			'containerHtml' => $containerHtml,
			'draggerHtml' => $draggerHtml,
			'chooserHtml' => $chooserHtml,
			'preloaderHtml' => $preloaderHtml,
			'infoHtml' => $infoHtml,
			'fieldsHtml' => $fieldsHtml,
			'formHtml' => $formHtml
		] );

		if( $this->wrap ) {

			return Html::tag( $this->wrapper, $widgetHtml, $this->options );
		}

		return $widgetHtml;
	}

	// FileUploader --------------------------

	/**
	 * Generate and return the HTML of container.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderContainer( $config = [] ) {

		$containerView = CodeGenUtil::isAbsolutePath( $this->containerView, true ) ? $this->containerView : "$this->template/$this->containerView";

		return $this->render( $containerView, [ 'widget' => $this ] );
	}

	/**
	 * Generate and return the HTML of file dragger having drag area.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderDragger( $config = [] ) {

		$draggerView = CodeGenUtil::isAbsolutePath( $this->draggerView, true ) ? $this->draggerView : "$this->template/$this->draggerView";

		return $this->render( $draggerView, [ 'widget' => $this ] );
	}

	/**
	 * Generate and return the HTML of file chooser element.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderChooser( $config = [] ) {

		$chooserView = CodeGenUtil::isAbsolutePath( $this->chooserView, true ) ? $this->chooserView : "$this->template/$this->chooserView";

		return $this->render( $chooserView, [ 'widget' => $this ] );
	}

	/**
	 * Generate and return the HTML of pre-loader used to show upload progress.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderPreloader( $config = [] ) {

		$preloaderView = CodeGenUtil::isAbsolutePath( $this->preloaderView, true ) ? $this->preloaderView : "$this->template/$this->preloaderView";

		return $this->render( $preloaderView, [ 'widget' => $this ] );
	}

	/**
	 * Generate and return the HTML of info having fields required to store file.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderInfo( $config = [] ) {

		$infoView = CodeGenUtil::isAbsolutePath( $this->infoView, true ) ? $this->infoView : "$this->template/$this->infoView";

		return $this->render( $infoView, [ 'widget' => $this ] );
	}

	/**
	 * Generate and return the HTML of fields. These fields will submit additional data required for file.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderFields( $config = [] ) {

		$fieldsView = CodeGenUtil::isAbsolutePath( $this->fieldsView, true ) ? $this->fieldsView : "$this->template/$this->fieldsView";

		return $this->render( $fieldsView, [ 'widget' => $this ] );
	}

	/**
	 * Generate and return the HTML of form to submit the uploaded file.
	 *
	 * @param array $config
	 * @return string
	 */
	public function renderForm( $config = [], $html = [] ) {

		$formView = CodeGenUtil::isAbsolutePath( $this->formView, true ) ? $this->formView : "$this->template/$this->formView";

		return $this->render( $formView, [
			'widget' => $this,
			'infoHtml' => $html[ 'infoHtml' ],
			'fieldsHtml' => $html[ 'fieldsHtml' ]
		] );
	}

}
