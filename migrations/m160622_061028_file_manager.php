<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\core\common\utilities\DateUtil;

class m160622_061028_file_manager extends \yii\db\Migration {

	public $prefix;

	private $uploadsDir;
	private $uploadsUrl;

	private $site;

	private $master;

	public function init() {

		$this->prefix		= 'cmg_';

		$this->uploadsDir	= Yii::$app->migration->getUploadsDir();
		$this->uploadsUrl	= Yii::$app->migration->getUploadsUrl();

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( 'demomaster' );

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
            'successMessage' => 'All configurations saved successfully.',
            'captcha' => false,
            'visibility' => Form::VISIBILITY_PROTECTED,
            'active' => true, 'userMail' => false,'adminMail' => false,
            'createdAt' => DateUtil::getDateTime(),
            'modifiedAt' => DateUtil::getDateTime()
        ]);

		$config	= Form::findBySlug( 'config-file', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'image_extensions', 'Image Extensions', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Image Extensions.\",\"placeholder\":\"Image Extensions\"}' ],
			[ $config->id, 'video_extensions', 'Video Extensions', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Video Extensions.\",\"placeholder\":\"Video Extensions\"}' ],
			[ $config->id, 'audio_extensions', 'Audio Extensions', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Audio Extensions.\",\"placeholder\":\"Audio Extensions\"}' ],
			[ $config->id, 'document_extensions', 'Document Extensions', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Document Extensions.\",\"placeholder\":\"Document Extensions\"}' ],
			[ $config->id, 'compressed_extensions', 'Compressed Extensions', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Compressed Extensions.\",\"placeholder\":\"Compressed Extensions\"}' ],
			[ $config->id, 'generate_name', 'Generate Name', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Generate Name.\"}' ],
			[ $config->id, 'pretty_name', 'Pretty Name', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Pretty Name.\"}' ],
			[ $config->id, 'max_size', 'Max Size', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Max Size.\",\"placeholder\":\"Max Size\"}' ],
			[ $config->id, 'generate_medium', 'Generate Medium', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Generate Medium Image.\"}' ],
			[ $config->id, 'generate_thumb', 'Generate Thumb', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Generate Thumb Image.\"}' ],
			[ $config->id, 'medium_width', 'Medium Width', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Medium Width.\",\"placeholder\":\"Medium Width\"}' ],
			[ $config->id, 'medium_height', 'Medium Height', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Medium Height.\",\"placeholder\":\"Medium Height\"}' ],
			[ $config->id, 'thumb_width', 'Thumb Width', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Thumb Width.\",\"placeholder\":\"Thumb Width\"}' ],
			[ $config->id, 'thumb_height', 'Thumb Height', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Thumb Height.\",\"placeholder\":\"Thumb Height\"}' ],
			[ $config->id, 'uploads_directory', 'Uploads Directory', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Uploads Directory.\",\"placeholder\":\"Uploads Directory\"}' ],
			[ $config->id, 'uploads_url', 'Uploads URL', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Uploads URL.\",\"placeholder\":\"Uploads URL\"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'valueType', 'value' ];

		$metas	= [
			[ $this->site->id, 'image_extensions', 'Image Extensions', 'file', 'text', 'png,jpg,jpeg,gif' ],
			[ $this->site->id, 'video_extensions', 'Video Extensions', 'file', 'text', 'mp4,flv,ogv,avi' ],
			[ $this->site->id, 'audio_extensions', 'Audio Extensions', 'file', 'text', 'mp3,m4a,wav' ],
			[ $this->site->id, 'document_extensions', 'Document Extensions', 'file', 'text', 'pdf,doc,docx,xls,xlsx,txt' ],
			[ $this->site->id, 'compressed_extensions', 'Compressed Extensions', 'file', 'text', 'rar,zip' ],
			[ $this->site->id, 'generate_name', 'Generate Name', 'file', 'flag', '1' ],
			[ $this->site->id, 'pretty_name', 'Pretty Name', 'file', 'flag', '0' ],
			[ $this->site->id, 'max_size', 'Max Size', 'file','text', '5' ],
			[ $this->site->id, 'generate_medium', 'Generate Medium', 'file', 'flag', '1' ],
			[ $this->site->id, 'generate_thumb', 'Generate Thumb', 'file', 'flag', '1' ],
			[ $this->site->id, 'medium_width', 'Medium Width', 'file', 'text', '480' ],
			[ $this->site->id, 'medium_height', 'Medium Height', 'file', 'text', '320' ],
			[ $this->site->id, 'thumb_width', 'Thumb Width', 'file', 'text', '120' ],
			[ $this->site->id, 'thumb_height', 'Thumb Height', 'file', 'text', '120' ],
			[ $this->site->id, 'uploads_directory', 'Uploads Directory', 'file', 'text', $this->uploadsDir ],
			[ $this->site->id, 'uploads_url', 'Uploads URL', 'file', 'text', $this->uploadsUrl ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

    public function down() {

        echo "m160622_061028_file_manager will be deleted with m160621_014408_core.\n";

        return true;
    }
}

?>