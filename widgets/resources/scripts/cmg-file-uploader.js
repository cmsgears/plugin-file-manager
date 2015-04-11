/***************************************************************************************************
(c) 2014 - http://www.cmsgears.com
cmg-file-uploader.js, version - 1.0.0, http://www.cmsgears.com/license,
http://www.cmsgears.com/jquery/cmg-file-uploader/
****************************************************************************************************
Dependencies: jQuery 1.11.0, cmg-browser-features.js
Description: The file uploader uploads files using either xhr for modern browsers or form data for
			 older browsers.
***************************************************************************************************/

// Change the supported file formats if required
var FILE_FORMATS	= [ "jpg", "jpeg", "png", "gif", "pdf", "csv" ];

// call to initialise file uploader. It generate the required html with classes and append the listeners.
function initFileUploader() {

	// Create uploader html elements
	var containers = jQuery( ".file-container" );

	for( var i = 0 ; i < containers.length; i++ ) {

		var container 	= containers[i];

		// Legend to be displayed on top of uploader
		var legend		= container.getAttribute( "legend" );

		// The folder selector for each file uploader. It need to be unique for each file type. A folder will be created on server to save files based on this selector.
		var selector	= container.getAttribute( "selector" );
		
		if( null == selector ) {

			selector = "";
		}
		
		// Unique ID assigned to each file uploader
		var parentId	= container.getAttribute( "id" );

		// The type of file. It can be image, pdf.
		var type		= container.getAttribute( "utype" );
		
		if( null == type ) {
			
			alert( "Please specify file type." );
		}

		// The classs applied to the file input 
		var buttonClass	= container.getAttribute( "btn-class" );

		// The text visible for file input
		var buttonText	= container.getAttribute( "btn-text" );

		// Init fieldset with legend
		var htmlCode	= "<fieldset class='file-fieldset'><legend>" + legend + "</legend>";

		// Modern browsers with drag and drop with thumbnail preview for images
		htmlCode += "<div class='file-selector-modern'>";
		htmlCode +=		"<div class='file-target-container'>";
		htmlCode += 		"<div class='file-target' parent-id='" + parentId + "' selector='" + selector + "' utype='" + type + "' >";
		htmlCode +=				"<div class='file-target-drag'>Drag here</div>";
		htmlCode +=				"<canvas class='file-preview' width='120' height='120' ></canvas>";
		htmlCode +=				"<div class='file-progress-modern'></div>";
		htmlCode +=	"</div></div></div>";

		// Legacy browsers to sopport file input
		htmlCode += "<div class='file-selector-legacy'>";
		htmlCode += 	"<div class='" + buttonClass + "'>" + buttonText;
		htmlCode +=			"<input type='file' class='file-input' parent-id='" + parentId + "' selector='" + selector + "' utype='" + type + "' />";
		htmlCode +=		"</div>";
		htmlCode +=		"<span class='file-progress-legacy'></span>";
		htmlCode +=	"</div>";

		// show uploaded file
		htmlCode +=	"<div class='file-image'></div>";

		// Close fieldset
		htmlCode +=	"</fieldset>";
		
		// Place the generate code for uploader
		container.innerHTML = htmlCode + container.innerHTML;
	}

	// Ensure that the browser supports File, FileList, FileReader and XHR Upload
	if ( isFileApiSupported() ) {

		// Allow traditional file selector to upload the files using xhr
		jQuery( ".file-fieldset input[type='file']" ).change( function( event ) { 

			fileSelectHandler( event, jQuery( this ).attr('parent-id'), jQuery( this ).attr('selector'), jQuery( this ).attr('utype') ); 
		} );

		// Bind event listeners for modern browsers and disable legacy file upload button

		var modernComp = jQuery( ".file-target" );

		modernComp.bind( 'dragover', function( event ) { 

			fileDragging( event ); 
		} );

		modernComp.bind( 'dragleave', function( event ) { 

			fileDragging( event );
		} );

		modernComp.bind( 'drop', function( event ) {

			fileSelectHandler( event, jQuery( this ).attr('parent-id'), jQuery( this ).attr('selector'), jQuery( this ).attr('utype') );
		} );

		modernComp.show();

		jQuery('.file-selector-modern').show();

		// jQuery('.file-selector-legacy').hide();
	}
	else if( isFormDataSupported() ) {

		jQuery( "input[type='file']" ).change( function( event ) {

			uploadTraditionalFile( jQuery( this ).attr('id'), jQuery(this).attr('selector'), jQuery(this).attr('utype') ); 
		} );
	}
}

function fileSelectHandler( event, parentId, selector, type ) {

	// cancel event and add hover styling
	fileDragging( event );

	// fetch FileList object
	var files = event.target.files || event.originalEvent.dataTransfer.files;

	// process File object
	parseFile( parentId, type, files[0] );

	// Upload File	
	uploadFile( parentId, selector, type, files[0] );
}

function fileDragging( event ) {

	event.stopPropagation();
	event.preventDefault();

	event.target.className = ( event.type == "dragover" ? "hover" : "" );
}

// Update the file details parameters 
function parseFile( parentId, type, file ) {

	if( isCanvasSupported() && type == "image" ) {

		drawImage( parentId, file );
	}
}

function drawImage( parentId, file ) {

	var canvas		= jQuery( "#" + parentId + " .file-target canvas" );

	if( null != canvas[0] ) {

		canvas[0].style.display = "block";

		var context 	= canvas[0].getContext('2d');
	    var image 		= new Image();
	    var image_url 	= window.URL || window.webkitURL;
	    var image_src 	= image_url.createObjectURL( file );

	    image.src 		= image_src;

	    image.onload = function() {

	        var dims = keepAspectRatio( image, 100, 120 );

	        context.drawImage( image, 0, 0, dims[0], dims[1] );

	        image_url.revokeObjectURL( image_src );
	    };
	}
}

function keepAspectRatio( image, targetWidth, targetHeight ) {

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
}

// XHR File Upload

function uploadFile( parentId, selector, type, file ) {

	var xhr 				= new XMLHttpRequest();
	var fileType			= file.type.toLowerCase();
	var isValidFile			= jQuery.inArray( fileType, FILE_FORMATS );
	var progressContainer	= jQuery( "#" + parentId + " .file-target .file-progress-modern" );
	var formData 			= new FormData();

	// append form data
	formData.append( 'file', file );
	
	// reset progress bar
	progressContainer.css( "width", "0%" );
	
	// upload file
	if( xhr.upload && isValidFile ) {

		// Upload progress
		xhr.upload.onprogress = function( e ) {

			if( e.lengthComputable ) {

				var progress = Math.round( ( e.loaded * 100 ) / e.total );

				if( progress < 100 ) {

					progressContainer.css( "width", progress + "%" );
				}
			}
		};

		// file received/failed
		xhr.onreadystatechange = function( e ) {

			if ( xhr.readyState == 4 ) {

				if( xhr.status == 200 ) {

					var jsonResponse = JSON.parse( xhr.responseText );

					if( jsonResponse['result'] == 1 ) {

						fileUploaded( parentId, selector, type, jsonResponse['data'] );
					}
				}
			}
		};

		var urlParams	= fileUploadUrl + "?selector=" + encodeURIComponent( selector ) + "&type=" + encodeURIComponent( type );

		// start upload
		xhr.open("POST", urlParams, true );
		xhr.send( formData );
	}
	else {

		alert( "File format not allowed." );
	}
}

//Traditional File Upload

function uploadTraditionalFile( parentId, selector, type ) {

	var progress	= jQuery( "#" + parentId + " .file-selector-legacy .file-progress-legacy" );
	var fileList	= jQuery( "#" + parentId + " .file-selector-legacy .file-input-legacy" );
	var file 		= fileList.files[0];
	var formData 	= new FormData();
	fileName 		= file.name;

	// Show progress
	progress.show();
	progress.html( "Uploading file" );

	formData.append( 'file', file );

	var urlParams	= fileUploadUrl + "?selector=" + encodeURIComponent( selector ) + "&type=" + encodeURIComponent( type );

	jQuery.ajax({
	  type:			"POST",
	  url: 			urlParams,
	  data: 		formData,
      cache: 		false,
      contentType: 	false,
      processData: 	false,
	  dataType:		'json',
	}).done( function( response ) {

		progress.html( "File uploaded" );

		if( response['result'] == 1 ) {

			// callback
			fileUploaded( parentId, selector, type, response['data'] );
		}
	});
}