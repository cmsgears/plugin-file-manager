// Avatar Uploader -------------------------------------------------------------

var imageEditor = null;

function showAvatarUploader() {

	var screenHeight	= jQuery( window ).height();
	var screenWidth		= jQuery( window ).width();
	var avatarUploader	= jQuery( "#avatar-uploader" );
	var boxHeight		= avatarUploader.height();
	var boxWidth		= avatarUploader.width();
	var boxTop			= ( screenHeight - boxHeight ) / 2;
	var boxLeft			= ( screenWidth - boxWidth ) / 2;
	var canvasContainer	= jQuery( "#avatar-uploader .avatar-wrap" );

	jQuery( '#wrap-avatar-uploader' ).show( 'slow' );

	avatarUploader.css( { top: boxTop, left: boxLeft } );

	if( null != imageEditor ) {

		imageEditor.clear();
	}

	imageEditor = new ImageEditor( { 
				uploadUrl: fileUploadUrl,
				selector: 'avatar',
				width: canvasContainer.width(), 
				height: canvasContainer.height(), 
				canvasContainer:  canvasContainer[ 0 ],
				imageChooser: jQuery( "#avatar-uploader .avatar-chooser" )[0]
			});

	imageEditor.init();
}

function hideAvatarUploader() {

	jQuery( '#wrap-avatar-uploader' ).hide( 'slow' );
}

function uploadAvatar() {
	
	imageEditor.uploadImage();
}

function saveAvatar( avatarData ) {

	var csrfToken 	= jQuery( 'meta[name=csrf-token]' ).attr( 'content' );
	avatarData		= avatarData[ 'data' ];

	jQuery.ajax({
		type: "POST",
	  	url: this.siteUrl + "apix/cmgcore/user/avatar",
	  	data: { '_csrf': csrfToken, 'Avatar[name]': avatarData.name, 'Avatar[extension]': avatarData.extension, 'Avatar[directory]': 'avatar', 'Avatar[changed]': 1 },
	  	dataType: "JSON"
	}).done( function( response ) {

		// Set Avatar
		jQuery( "#avatar" ).html( "<img src=' " + response[ 'data' ].fileUrl + "' />" );

		// Hide Uploader
		hideAvatarUploader();
	});
}

// Image Editor Library --------------------------------------------------------

// Dependencies: cmg-browser-features.js

function isImageEditorSupported() {

	return isFileApiSupported() && isFormDataSupported() && isCanvasSupported() && isCanvasDataUrlSupported();
}

// File Listeners ------------------------

function FileListener( event ) {

	event.stopPropagation();
	event.preventDefault();
	event.target.className = (event.type == "dragover" ? "hover" : ""); 
	
	var files 		= event.target.files || event.originalEvent.dataTransfer.files;
	var file		= files[0];
	var image_url 	= window.URL || window.webkitURL;
	var image_src 	= image_url.createObjectURL( file );
	
	imageEditor.imageName	= file.name;

	imageEditor.loadImage( image_src );
}

// Image Editor --------------------------

function ImageEditor( options ) {
	
	// Uploader
	this.uploadUrl			= options.uploadUrl;
	this.selector			= options.selector;
	
	// Editor
	this.width 				= parseInt( options.width );
	this.height 			= parseInt( options.height );
	this.canvasContainer 	= options.canvasContainer;
	this.imageChooser		= options.imageChooser;
	this.canvas				= null;
	this.context			= null;
	this.image				= null;
	this.imageName			= null;
}

// ImageEditor initialisation
ImageEditor.prototype.init = function() {

	this.canvas			= document.createElement( 'canvas' );
	this.canvas.width  	= this.width;
	this.canvas.height 	= this.height;
	
	this.context		= this.canvas.getContext( '2d' );

	this.canvasContainer.appendChild( this.canvas );

	this.initImageLoader();
};

// ImageEditor clear
ImageEditor.prototype.clear = function() {

	this.imageName		= null;
	this.image			= null;
	this.context		= null;
	this.canvas			= null;
	canvasContainer 	= this.canvasContainer;

	// Remove children
	while ( canvasContainer.firstChild ) {

	    canvasContainer.removeChild( canvasContainer.firstChild );
	}
};

// Load the image
ImageEditor.prototype.initImageLoader = function() {

	if ( isFileApiSupported() ) {

		this.imageChooser.removeEventListener( 'change', FileListener );

		this.imageChooser.addEventListener( 'change', FileListener );
	}
};

ImageEditor.prototype.loadImage = function( imageUrl ) {

	this.image			= new Image();

	this.image.onload 	= function() {

		var dims = imageEditor.keepAspectRatio( imageEditor.image, imageEditor.width, imageEditor.height );

        imageEditor.context.drawImage( imageEditor.image, 0, 0, dims[0], dims[1] );
	};
	this.image.src 		= imageUrl;	
};

ImageEditor.prototype.keepAspectRatio = function( image, targetWidth, targetHeight ) {

    var ratio 	= 0;
    var width 	= image.width;
    var height 	= image.height;

    // Check if the current width is larger than the max
    if( width > targetWidth ) {

        ratio 	= targetWidth / width;
        height 	= height * ratio;
        width 	= width * ratio;
    }

    // Check if current height is larger than max
    if( height > targetHeight ) {

        ratio 	= targetHeight / height;
        width 	= width * ratio;
    }

    return new Array( width, height );
};

ImageEditor.prototype.uploadImage = function() {

	var imageData 	= this.canvas.toDataURL( "image/png" );
	var selector	= this.selector;
	
	imageData		= imageData.substr( imageData.indexOf( ',' ) + 1 ).toString();
	
	jQuery.ajax({
		type: "POST",
	  	url: this.uploadUrl + "?type=image&selector=" + selector,
	  	data: { fileData: true, fileName: this.imageName, fileExtension: 'png', file: imageData },
	  	dataType: "JSON"
	}).done( function( response ) {

		saveAvatar( response );
	});
};