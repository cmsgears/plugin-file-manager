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
		}
    }

	public function renderFooter() {
?>
		<div id="wrap-avatar-uploader">
			<div id="avatar-uploader">
				<div class="header">Change Avatar
					<span class="btn btn-medium">
						Choose Avatar
						<input type="file" class="avatar-chooser" />
					</span>
				</div>
				<div class="content">
					<div class="actions">
						<span class="cmt-icon medium cmt-zoom-in"></span>
						<span class="cmt-icon medium cmt-zoom-out"></span>
						<span class="cmt-icon medium cmt-rotate-clock"></span>
						<span class="cmt-icon medium cmt-rotate-anti-clock"></span>
					</div>
					<div class="avatar-wrap"></div>
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

			echo "<div class='avatar'><img src='$defaultAvatar' /></div>"; 
		}
		else {

			$user 	= Yii::$app->user->getIdentity();
			$avatar	= $user->avatar;

			if( isset( $avatar ) ) {

				$avatarUrl	= $avatar->getFileUrl();

				echo "<div class='avatar'><img src='$avatarUrl' /></div>";
			}
			else {

				echo "<div class='avatar'><img src='$defaultAvatar' /></div>";
			}
		}
?>

			<div>
				<span class="btn">Change Profile Picture</span>
			</div>
		</div>
<?php
    }
}

?>