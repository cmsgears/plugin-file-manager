function isCanvasSupported() {

	// Canvas support
	var elem 			= document.createElement('canvas');
	var canvasSupported = !!(elem.getContext && elem.getContext('2d'));

	return canvasSupported;
}

function isXhrSupported() {

	var xhr	= new XMLHttpRequest();

	return xhr.upload;	
}

function isFileApiSupported() {

	return window.File && window.FileList && window.FileReader;	
}

function isFormDataSupported() {

	return !! window.FormData;
}

function isCanvasDataUrlSupported() {

	var cvsTest 			= document.createElement( "canvas" );
	var data				= cvsTest.toDataURL( "image/png" );
	var toDataUrlSupported	= data.indexOf( "data:image/png" ) == 0;

	return toDataUrlSupported;
}