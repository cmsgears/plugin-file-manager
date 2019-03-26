<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Migration;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The file manager migration inserts the base data required to manage files.
 *
 * @since 1.0.0
 */
class m160701_061028_file_manager extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;
	private $master;

	private $uploadsDir;
	private $uploadsUrl;

	public function init() {

		// Table prefix
		$this->prefix	= Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		$this->uploadsDir	= Yii::$app->migration->getUploadsDir();
		$this->uploadsUrl	= Yii::$app->migration->getUploadsUrl();

		Yii::$app->core->setSite( $this->site );
	}

	public function up() {

		// Create various config
		$this->insertFileConfig();

		// Init default config
		$this->insertDefaultConfig();
	}

	private function insertFileConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config File', 'slug' => 'config-file',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'File configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false, 'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		] );

		$config = Form::findBySlugType( 'config-file', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields = [
			[ $config->id, 'image_extensions', 'Image Extensions', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Image Extensions","placeholder":"Image Extensions"}' ],
			[ $config->id, 'video_extensions', 'Video Extensions', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Video Extensions","placeholder":"Video Extensions"}' ],
			[ $config->id, 'audio_extensions', 'Audio Extensions', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Audio Extensions","placeholder":"Audio Extensions"}' ],
			[ $config->id, 'document_extensions', 'Document Extensions', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Document Extensions","placeholder":"Document Extensions"}' ],
			[ $config->id, 'compressed_extensions', 'Compressed Extensions', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Compressed Extensions","placeholder":"Compressed Extensions"}' ],
			[ $config->id, 'image_quality', 'Image Quality', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Image Quality","placeholder":"Image Quality"}' ],
			[ $config->id, 'generate_name', 'Generate Name', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Generate Name"}' ],
			[ $config->id, 'pretty_name', 'Pretty Name', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Pretty Name"}' ],
			[ $config->id, 'max_size', 'Max Size', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Max Size","placeholder":"Max Size"}' ],
			[ $config->id, 'max_resolution', 'Max Resolution', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Max Resolution - limits pixel count","placeholder":"Max Resolution"}' ],
			[ $config->id, 'generate_medium', 'Generate Medium', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Generate Medium Image"}' ],
			[ $config->id, 'generate_small', 'Generate Small', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Generate Small Image"}' ],
			[ $config->id, 'generate_thumb', 'Generate Thumb', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Generate Thumb Image"}' ],
			[ $config->id, 'generate_placeholder', 'Generate Placeholder', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Generate Placeholder Image"}' ],
			[ $config->id, 'medium_width', 'Medium Width', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Medium Width","placeholder":"Medium Width"}' ],
			[ $config->id, 'medium_height', 'Medium Height', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Medium Height","placeholder":"Medium Height"}' ],
			[ $config->id, 'small_width', 'Small Width', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Small Width","placeholder":"Small Width"}' ],
			[ $config->id, 'small_height', 'Small Height', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Small Height","placeholder":"Small Height"}' ],
			[ $config->id, 'thumb_width', 'Thumb Width', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Thumb Width","placeholder":"Thumb Width"}' ],
			[ $config->id, 'thumb_height', 'Thumb Height', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Thumb Height","placeholder":"Thumb Height"}' ],
			[ $config->id, 'uploads', 'Uploads', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Checks whether file upload is allowed."}' ],
			[ $config->id, 'uploads_directory', 'Uploads Directory', FormField::TYPE_TEXT, false, true, true, NULL, 0, NULL, '{"title":"Uploads Directory","placeholder":"Uploads Directory"}' ],
			[ $config->id, 'uploads_url', 'Uploads URL', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Uploads URL","placeholder":"Uploads URL"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'active', 'valueType', 'value', 'data' ];

		$metas = [
			[ $this->site->id, 'image_extensions', 'Image Extensions', 'file', 1, 'text', 'png,jpg,jpeg,gif', NULL ],
			[ $this->site->id, 'video_extensions', 'Video Extensions', 'file', 1, 'text', 'mp4,flv,ogv,avi', NULL ],
			[ $this->site->id, 'audio_extensions', 'Audio Extensions', 'file', 1, 'text', 'mp3,m4a,wav', NULL ],
			[ $this->site->id, 'document_extensions', 'Document Extensions', 'file', 1, 'text', 'pdf,doc,docx,xls,xlsx,txt', NULL ],
			[ $this->site->id, 'compressed_extensions', 'Compressed Extensions', 'file', 1, 'text', 'rar,zip', NULL ],
			[ $this->site->id, 'image_quality', 'Image Quality', 'file', 1, 'text', '75', NULL ],
			[ $this->site->id, 'generate_name', 'Generate Name', 'file', 1, 'flag', '1', NULL ],
			[ $this->site->id, 'pretty_name', 'Pretty Name', 'file', 1, 'flag', '0', NULL ],
			[ $this->site->id, 'max_size', 'Max Size', 'file', 1, 'text', '5', NULL ],
			[ $this->site->id, 'max_resolution', 'Max Resolution', 'file', 1, 'text', '10000', NULL ],
			[ $this->site->id, 'generate_medium', 'Generate Medium', 'file', 1, 'flag', '1', NULL ],
			[ $this->site->id, 'generate_small', 'Generate Small', 'file', 1, 'flag', '1', NULL ],
			[ $this->site->id, 'generate_thumb', 'Generate Thumb', 'file', 1, 'flag', '1', NULL ],
			[ $this->site->id, 'generate_placeholder', 'Generate Placeholder', 'file', 1, 'flag', '1', NULL ],
			[ $this->site->id, 'medium_width', 'Medium Width', 'file', 1, 'text', '0', NULL ],
			[ $this->site->id, 'medium_height', 'Medium Height', 'file', 1, 'text', '0', NULL ],
			[ $this->site->id, 'small_width', 'Small Width', 'file', 1, 'text', '0', NULL ],
			[ $this->site->id, 'small_height', 'Small Height', 'file', 1, 'text', '0', NULL ],
			[ $this->site->id, 'thumb_width', 'Thumb Width', 'file', 1, 'text', '120', NULL ],
			[ $this->site->id, 'thumb_height', 'Thumb Height', 'file', 1, 'text', '120', NULL ],
			[ $this->site->id, 'uploads', 'Uploads', 'file', 1, 'text', '1', NULL ],
			[ $this->site->id, 'uploads_directory', 'Uploads Directory', 'file', 1, 'text', $this->uploadsDir, NULL ],
			[ $this->site->id, 'uploads_url', 'Uploads URL', 'file', 1, 'text', $this->uploadsUrl, NULL ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	public function down() {

		echo "m160701_061028_file_manager will be deleted with m160621_014408_core.\n";

		return true;
	}

}
