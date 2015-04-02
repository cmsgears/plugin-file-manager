function showAvatarChooser() {

	var screenHeight	= jQuery( window ).height();
	var screenWidth		= jQuery( window ).width();
	var avatarUploader	= jQuery( "#avatar-chooser .box-avatar-uploader" );
	var boxHeight		= avatarUploader.height();
	var boxWidth		= avatarUploader.width();
	var boxTop			= ( screenHeight - boxHeight ) / 2;
	var boxLeft			= ( screenWidth - boxWidth ) / 2;

	jQuery( '#avatar-chooser' ).show( 'slow' );
	
	avatarUploader.css( { top: boxTop, left: boxLeft } );
}

function uploadAvatar( listenerId, avatarId ) {

}
