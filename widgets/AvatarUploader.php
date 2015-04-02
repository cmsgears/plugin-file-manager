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

	public $options 		= [];
	public $listenerId;
	public $avatarId;
	public $includeScripts	= false;
	public $footer			= false;

	// Constructor and Initialisation ------------------------------

	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {
		
		// Render Footer
		if( $this->footer ) {

			$this->renderFooter();
		}
		else {

			$this->renderHtml();
			
			$listenerJs = "jQuery( '#$this->listenerId' ).click( function() { showAvatarChooser(); });
						   jQuery( '#btn-avatar-cancel' ).click( function() { jQuery( '#avatar-chooser').hide( 'slow' ); });
						   jQuery( '#btn-avatar-upload' ).click( function() { uploadAvatar( '$this->listenerId', '$this->avatarId' ); });";

			$this->getView()->registerJs( $listenerJs, View::POS_READY );
		}

		// Output Javascript at the end of Page
		if( $this->includeScripts ) {

        	AssetLoaderUploader::register( $this->getView() );
		}
    }

	public function renderFooter() {
?>
		<div id="avatar-chooser" class="wrap-avatar-uploader">
			<div class="box-avatar-uploader">
				<div class="header">Change Avatar
					<span class="btn btn-medium">
						Choose Avatar
						<input type="file" />
					</span>
				</div>
				<div class="content">
					<div class="actions">
						<span class="cmt-zoom-in"></span>
					</div>
					<div class="avatar-canvas"></div>
				</div>
				<div class="clearfix">
					<div class="col2"><input id="btn-avatar-cancel" type="button" value="Cancel" /></div>
					<div class="col2"><input id="btn-avatar-upload" type="button" value="Upload" /></div>
				</div>
			</div>
		</div>
<?php
	}

    public function renderHtml() {
?>
		<div class="box-avatar">
<?php
		$defaultAvatar = Yii::getAlias( "@images" ) . "/avatar.png";

		if( Yii::$app->user->isGuest ) {

			echo "<div id='$this->avatarId' class='avatar'><img src='$defaultAvatar' /></div>"; 
		}
		else {

			$user 	= Yii::$app->user->getIdentity();
			$avatar	= $user->avatar;

			if( isset( $avatar ) ) {

				$avatarUrl	= Yii::$app->cmgFileManager->uploadUrl . $avatar->url;

				echo "<div id='$this->avatarId' class='avatar'><img src='$avatarUrl' /></div>";
			}
			else {

				echo "<div id='$this->avatarId' class='avatar'><img src='$defaultAvatar' /></div>";
			}
		}
?>

			<div>
				<span id="<?=$this->listenerId?>" class="btn">Change Profile Picture</span>
			</div>
		</div>
<?php
    }
}

?>