// Reset Ratio -----------------------------------------------

Kinetic.pixelRatio = 1;

var emblmDesigner			= null;
var avatarDesigner			= null;
var mkDesigner				= null;
var activeDesigner			= null;

// Marketplace design
var selectedDesignUrl		= null;
var selectedDesignRatings	= 0.0;
var loadingMkDesign			= false;
var mkDesignSelected		= false;

var designFileName			= "";
var loadingAvatar			= false;

// Emblm Design Tool -----------------------------------------

function DesignTool( canvasDeviceParent, canvasDesignParent, width, height, spinner, toolPrefix ) {
	
	this.canvasDeviceParent	= canvasDeviceParent;
	this.canvasDesignParent	= canvasDesignParent;
	this.width				= width;
	this.height				= height;
	this.spinner			= spinner;
	this.toolPrefix			= toolPrefix;
	this.templateMode		= false;
	
	// Stage
	this.stage			= new Kinetic.Stage( { container: this.canvasDeviceParent, width: this.width, height: this.height } );
	this.designStage	= null;
	this.designlStage	= null;

	// Device
	this.deviceObjLayer		= new Kinetic.Layer();
	this.deviceObjImg 		= new Image();
	this.deviceObj			= null;
	
	// Design
	this.designObjLayer		= new Kinetic.Layer();
	this.currentObjImg		= null;
	this.designLayers		= new Array();
	this.designCounter		= 0;
	this.totalDesigns		= 1;
	
	this.templateGroup		= new Array();
	this.activeTemplate		= 0;
	this.templatelGroup		= new Array();
	
	// Design Large
	this.designlObjLayer	= new Kinetic.Layer();
	this.designlLayers		= new Array();
	
	// Active Designs
	this.activeDesign		= null;
	this.activeDesignl		= null;
		
	// Avatar
	this.avatarObjImg		= null;
	this.avatarObj			= null;
	
	// Mask
	this.maskObjLayer		= new Kinetic.Layer();
	this.maskObjImg 		= null;
	this.maskObj			= null;
	this.maskInitialised	= false;
	
	// QR Code
	this.qrObjImg 			= null;
	this.qrObj				= null;
	this.qrlObj				= null;
	this.qrOffsetBottom		= 80;
	this.qrSetup			= false;
	
	this.designLoaded		= false;
	
	this.mkDesigner			= false;
	
	// Design Canvas dimensions
	this.designerTop	= 0;
	this.designerLeft	= 0;
	this.designerWidth	= 0;
	this.designerHeight	= 0;	
	
	// Designer Zoom Factor
	this.zoomFactor		= 2.8;
	this.zoomScale		= 1 / this.zoomFactor;
	
	this.qualityRatio	= 3;
	this.qualitySize	= 100;
	
	// Designer Animation
	this.rotationAnimation	= null;
	this.zoomAnimation		= null;

	// Current Active Layer
	this.activeObj		= null;
	this.activeLayer	= null;
}

DesignTool.prototype.initialise = function() {
	
	this.designerWidth	= deviceDesignerWidth;
	this.designerHeight	= deviceDesignerHeight;
	
	if( null != this.designStage )
	{
		this.designStage.destroy();
		
		this.designStage = null;
	}
	
	if( null != this.designlStage )
	{
		this.designlStage.destroy();
		
		this.designlStage = null;
	}	
}

// Load device for design tool on main designer
DesignTool.prototype.loadDevice = function( deviceImage )
{
	if( null != this.designStage )
	{
		this.designStage.destroy();
		
		this.designStage = null;
	}
	
	if( null != this.designlStage )
	{
		this.designlStage.destroy();
		
		this.designlStage = null;
	}

	// Load new device		
    this.deviceObjImg.onload 	= function( event ) {

    	// TODO: find a way to avoid using global here
    	activeDesigner.initialiseDevice();
    };
    
	this.deviceObjImg.src 		= deviceImage;
}

DesignTool.prototype.initialiseDevice = function()
{	
	this.designerTop	= parseInt( ( this.height - this.deviceObjImg.height ) / 2 ) + parseInt( deviceDesignerTop );
	this.designerLeft	= ( this.width - this.designerWidth ) / 2;

	jQuery( "#" + this.canvasDesignParent ).css( {top: this.designerTop, left: this.designerLeft, width: this.designerWidth, height: this.designerHeight } );

   	this.deviceObj = new Kinetic.Image( { x: 0, y: 0, image: this.deviceObjImg } );

	this.deviceObj.offsetX( this.deviceObjImg.width/2 );
	this.deviceObj.offsetY( this.deviceObjImg.height/2 );
		
    this.deviceObjLayer.add( this.deviceObj ); 

    this.stage.add( this.deviceObjLayer );

    this.positionCenterStage( this.deviceObj );

    // Hide Spinner
	hideSpinner( this.spinner );
	
	// Load Select Design for the Selected Device
	if( null != selectedDesignUrl && selectedDesignUrl.length > 0 )
	{
		showSpinner( "designer-spinner" );

		// Show the ratings for selected marketplace design
		jQuery("#rating-container").show();
		jQuery('#design-rating').rateit('value', selectedDesignRatings );

		this.loadDesign( selectedDesignUrl ); // Load the marketplace design
	}
	
	// marketplace
	jQuery('#user_d_chooser_mk').prop('disabled', false);	
	
	// Initialise Designer
	this.initDesigner();
}

DesignTool.prototype.initDesigner = function() 
{
		// Designer Stage, Layer and Mask
		this.designStage 	= new Kinetic.Stage( { container: this.canvasDesignParent, width: this.designerWidth, height: this.designerHeight } );
			
		this.designObjLayer.destroyChildren();
		this.maskObjLayer.destroyChildren();

        this.designStage.add( this.designObjLayer );

		// Large Stage and Layer
		this.designlStage 	= new Kinetic.Stage( { container: this.canvasDesignParent + '-l', width: this.designerWidth * this.zoomFactor, height: this.designerHeight * this.zoomFactor } );
		
		this.designlObjLayer.destroyChildren();	
		
        this.designlStage.add( this.designlObjLayer );	
        
		// Bind action events to be performed on designer image loaded by User
		this.activateInteraction();     	
}

DesignTool.prototype.loadDesign = function( imageUrl )
{
	// Add the design to canvas
	if( isCanvasSupported() && null != this.stage ) {

		this.currentObjImg	= new Image();

	    // marketplace
	    if( activeDesigner == mkDesigner )
	    {
	    	this.mkDesigner	= true;
			loadingMkDesign	= true;
		}
	
	    this.currentObjImg.onload = function() {

			activeDesigner.initialiseDesign();

			if( !activeDesigner.maskInitialised )
			{
	    		activeDesigner.maskObjImg			= new Image(); 
	
				activeDesigner.maskObjImg.onload 	= function() {
	
					activeDesigner.initialiseMask();   	
				}
	
				activeDesigner.maskObjImg.src 	= deviceMaskUrl;
			}
			
		    // marketplace
		    if( activeDesigner == mkDesigner )
		    {			
				loadingMkDesign		= false;
				mkDesignSelected	= true;

				enableDesignFields();
			}	
		}

		this.currentObjImg.src = imageUrl;

		if( activeDesigner == emblmDesigner )
		{
			// Enable Add to Cart Button
			jQuery('#add_to_cart_button').attr( 'disabled', false );
			jQuery('#add_to_cart_button').unbind("click");
			jQuery('#add_to_cart_button').click( function() {
				
				addItemToCart();
			} );	
		}	
	}
}

DesignTool.prototype.initialiseDesign = function()
{
		// Add Design to designer
		var designObj 	= new Kinetic.Image( { x: 0, y: 0, image: this.currentObjImg, draggable: true } );

		designObj.offsetX( this.currentObjImg.width/2 );
		designObj.offsetY( this.currentObjImg.height/2 );
		designObj.scaleX( this.zoomScale ); 
		designObj.scaleY( this.zoomScale );
		
		if( this.mkDesigner )
		{
			this.designObjLayer.destroyChildren();
			this.designObjLayer.add( designObj );
		}
		else if( ! this.templateMode )
		{
			this.designObjLayer.add( designObj );
		}
		else
		{
			if( null != this.templateGroup[this.activeTemplate] )
			{
				this.templateGroup[this.activeTemplate].destroyChildren();
			}
			
			this.templateGroup[this.activeTemplate].add( designObj );
		} 
        
        this.addMouseActions( designObj ); 
        this.positionCenterStage( designObj, this.designStage );

		// Hide the layer till we get the mask
		if( this.maskInitialised )
		{
			// Hide the Designer Spinner on mask image load
			hideSpinner( "designer-spinner" );
       	}
       
        // Load the Hidden Design
	   	var designlObj 	= new Kinetic.Image( { x: 0, y: 0, image: this.currentObjImg, draggable: true } );

		designlObj.offsetX( this.currentObjImg.width/2 );
		designlObj.offsetY( this.currentObjImg.height/2 );
				
		if( ! this.templateMode )
		{
			this.designlObjLayer.add( designlObj );
		}
		else
		{
			this.templatelGroup[this.activeTemplate].add( designlObj );
		} 
		
        this.positionCenterStage( designlObj, this.designlStage );	

		// Set Active Layer
		this.activeObj		= designObj;
		this.activeLayer	= this.designObjLayer;    
		
		if( !this.templateMode )
		{
			this.designLayers[this.designCounter]	= new DesignLayer( designObj );
			this.designlLayers[this.designCounter] 	= new DesignLayer( designlObj );
		}
		else 
		{
			this.designLayers[this.activeTemplate] 		= new DesignLayer( designObj );
			this.designlLayers[this.activeTemplate] 	= new DesignLayer( designlObj );
		}
		
		// Add the design
		this.showUserDesign( this.designCounter, this.currentObjImg );
			
		// Low Quality Warning
		var widthCheck	= this.designlStage.getWidth() / this.qualityRatio;
		var heightCheck	= this.designlStage.getHeight() / this.qualityRatio;   

		if( this.currentObjImg.width < widthCheck || this.currentObjImg.height < heightCheck )
		{
			alert( getQualityError() );
		}
}

DesignTool.prototype.initialiseMask = function()
{
		this.maskObj 	= new Kinetic.Image( { x: 0, y: 0, image: this.maskObjImg } );
		
		this.maskObj.offsetX( this.maskObjImg.width/2 );
		this.maskObj.offsetY( this.maskObjImg.height/2 );
					
		this.maskObjLayer.add( this.maskObj );
		this.designStage.add( this.maskObjLayer );
		this.positionCenterStage( this.maskObj, this.designStage );
		this.maskObjLayer.setListening(false);
		
		// Hide the Designer Spinner on mask image load
		hideSpinner( "designer-spinner" );

        // Show the layer
        this.designObjLayer.show();
        this.designStage.draw();
        
        this.designlObjLayer.show();
        this.designlStage.draw();
        
        this.maskInitialised	= true;
}

DesignTool.prototype.loadQrCode = function( imageUrl )
{
	// Add the design to canvas
	if( isCanvasSupported() && null != this.stage && null != this.designStage ){

		this.qrObjImg	= new Image();

	    this.qrObjImg.onload = function() {
			
			removeQrCode();
			activeDesigner.initialiseQrCode();
		}

		this.qrObjImg.src = imageUrl;
	}
	else {
		alert( ERROR_QR_CODE_EARLY );
	}
}

DesignTool.prototype.initialiseQrCode = function()
{
		// Add QR Code to designer
		this.qrObj 	= new Kinetic.Image( { x: 0, y: 0, image: this.qrObjImg, draggable: true } );
		
		this.addMouseActions( this.qrObj );
		
		this.qrObj.offsetX( this.qrObjImg.width/2 );
		this.qrObj.offsetY( this.qrObjImg.height/2 );
		
		this.qrObj.scaleX( this.zoomScale );
		this.qrObj.scaleY( this.zoomScale );
        this.designObjLayer.add( this.qrObj );

		var width 	= this.qrObj.getWidth() * this.zoomScale;
		var height	= this.qrObj.getHeight() * this.zoomScale;
		var bottom	= this.qrOffsetBottom * this.zoomScale;
		
		this.qrObj.setX( ( this.designStage.getWidth() ) / 2 );
		this.qrObj.setY( this.designStage.getHeight() - height/2 - bottom );
        
        this.designStage.draw();
		
		// Add QR Code to hidden design
	   	this.qrlObj 	= new Kinetic.Image( { x: 0, y: 0, image: this.qrObjImg, draggable: false } );
	   	
		this.qrlObj.offsetX( this.qrObjImg.width/2 );
		this.qrlObj.offsetY( this.qrObjImg.height/2 );
			   	
		this.qrlObj.setX( ( this.designlStage.getWidth() ) / 2 );
		this.qrlObj.setY( this.designlStage.getHeight() - this.qrlObj.getHeight()/2 - this.qrOffsetBottom );

        this.designlObjLayer.add( this.qrlObj );
        
        this.designlStage.draw();
        
        this.qrSetup	= true;
        
        this.showUserDesign( 0, this.qrObjImg );
}

DesignTool.prototype.loadAvatar = function( imageUrl )
{
	// Add the design to canvas
	if( isCanvasSupported() && null != this.stage ){
		
		this.designObjLayer.destroyChildren();
		
		this.avatarObjImg	= new Image();
	    
	    this.avatarObjImg.onload = function() {
			
			activeDesigner.initialiseAvatar();

			jQuery( '#upload-profile' ).prop("disabled", false);
		}

		this.avatarObjImg.src = imageUrl;	
	}
}

DesignTool.prototype.initialiseAvatar = function()
{
		this.avatarObj 	= new Kinetic.Image( { x: 0, y: 0, image: this.avatarObjImg, draggable: true } );
			
		this.avatarObj.offsetX( this.avatarObjImg.width/2 );
		this.avatarObj.offsetY( this.avatarObjImg.height/2 );

        this.designObjLayer.add( this.avatarObj );

        this.stage.add( this.designObjLayer );
        
        this.addMouseActions( this.avatarObj );
        
        this.positionCenterStage( this.avatarObj, this.designStage );  
        
		// Current Active Layer
		this.activeObj		= this.avatarObj;
		this.activeLayer	= this.designObjLayer;
		
		this.activateInteraction();
}

DesignTool.prototype.loadMk = function( selectedDeviceId ) {

   		var element 		= jQuery( "#tool_select_" + selectedDeviceId ); 
   		
   		deviceMaskUrl		= element.attr('mask');
   		deviceTargetWidth	= element.attr('dtw');
   		deviceTargetHeight	= element.attr('dth');
   		deviceDesignerTop	= element.attr('ddt');

   		this.designerWidth		= element.attr('ddw');
   		this.designerHeight		= element.attr('ddh');	
   		
   		this.loadDevice( jQuery("#tool_select_" + selectedDeviceId).html() );
}

DesignTool.prototype.positionCenterStage = function( imageObj, stage )
{
	var stageWidth 	= 0;
	var stageHeight = 0;
	
	var objWidth 	= imageObj.getWidth();
	var objHeight 	= imageObj.getHeight();
		
	if( null != stage )
	{
		stageWidth 	= stage.getWidth();
		stageHeight = stage.getHeight();	
	} 
	else
	{
		stageWidth 	= this.stage.getWidth();
		stageHeight = this.stage.getHeight();
	}
	
	imageObj.setX( stageWidth / 2 );
	imageObj.setY( stageHeight / 2 );	

	if( null == stage )
		this.stage.draw();
	else
		stage.draw();
}

DesignTool.prototype.addMouseActions = function( imageObj )
{
	imageObj.on('mouseover', function() {
		
		var cursorUrl = imagesResourcePath + 'users/blue_arrow.png';
		
		document.body.style.cursor = "url(" + cursorUrl + ") 32 32, move";
	});
	
	imageObj.on('mouseout', function() {
		
		document.body.style.cursor = 'default';
	});	
}

DesignTool.prototype.activateInteraction = function()
{
		jQuery( '#rotate-' + this.toolPrefix ).mousedown( function( e ){
			
			e.preventDefault(); 

			activeDesigner.rotate();
		});
		
		jQuery( '#rotate-' + this.toolPrefix ).bind( "mouseup mouseout", function( e ){
			
			e.preventDefault();
			
			activeDesigner.stopRotation();
		});
				
		jQuery( '#rotate-reverse-' + this.toolPrefix ).mousedown( function( e ){
			
			e.preventDefault();
			
			activeDesigner.rotateRev();	
		});
		
		jQuery( '#rotate-reverse-' + this.toolPrefix ).bind( "mouseup mouseout", function( e ){
			
			e.preventDefault();
			
			activeDesigner.stopRotation();		
		});
				
		jQuery( '#zoom-in-' + this.toolPrefix ).mousedown( function( e ){
			
			e.preventDefault();
			
			activeDesigner.zoomIn();
		});
		
		jQuery( '#zoom-in-' + this.toolPrefix ).bind( "mouseup mouseout", function( e ){
			
			e.preventDefault();
			
			activeDesigner.stopZooming();				
		});

		jQuery( '#zoom-out-' + this.toolPrefix ).mousedown( function( e ){
			
			e.preventDefault();
			
			activeDesigner.zoomOut();	
		});
		
		jQuery( '#zoom-out-' + this.toolPrefix ).bind( "mouseup mouseout", function( e ){
			
			e.preventDefault();
			
			activeDesigner.stopZooming();				
		});	

		jQuery( '#reset-' + this.toolPrefix ).click( function( e ){
			
			e.preventDefault();
			
			activeDesigner.emblmReset();	
		});			
}

// Canvas Actions ---------

DesignTool.prototype.rotate = function()
{
	if( null == this.activeObj || null == this.activeLayer )
	{
		return;
	}

	if( null != this.rotationAnimation )
	{
		this.rotationAnimation.stop();
		this.rotationAnimation = null;
	}	
	
    // one revolution per 4 seconds
    var angularSpeed 		= Math.PI / 2;
    
    this.rotationAnimation 	= new Kinetic.Animation( function( frame ) {
      
	      var angleDiff = frame.timeDiff * angularSpeed / 200;
	      
	      activeDesigner.activeObj.rotate(angleDiff);
	          
    }, activeDesigner.activeLayer );

    this.rotationAnimation.start();
}

DesignTool.prototype.rotateRev = function()
{
	if( null == this.activeObj || null == this.activeLayer )
	{
		return;
	}

	if( null != this.rotationAnimation )
	{
		this.rotationAnimation.stop();
		this.rotationAnimation = null;
	}	
			
    // one revolution per 4 seconds
    var angularSpeed 	= Math.PI / 2;
    
    this.rotationAnimation 	= new Kinetic.Animation( function( frame ) {
      
	      var angleDiff = frame.timeDiff * angularSpeed / 200;
	      
	      activeDesigner.activeObj.rotate(-angleDiff);
	          
    }, activeDesigner.activeLayer );

    this.rotationAnimation.start();
}

DesignTool.prototype.stopRotation = function()
{
	if( null != this.rotationAnimation )
	{
		this.rotationAnimation.stop();
	}
}

DesignTool.prototype.zoomIn = function()
{
	if( null == this.activeObj || null == this.activeLayer )
	{
		return;
	}
	
	if( null != this.zoomAnimation )
	{
		this.zoomAnimation.stop();
		this.zoomAnimation = null;
	}	
	
	var scale 	= this.activeObj.getScaleX();
	
   	this.zoomAnimation = new Kinetic.Animation( function( frame ) {
      
	      scale += scale * frame.timeDiff / 4000;
	      
	      activeDesigner.activeObj.setScaleX(scale);
	      activeDesigner.activeObj.setScaleY(scale);
	          
    }, activeDesigner.activeLayer );

    this.zoomAnimation.start();
}

DesignTool.prototype.zoomOut = function()
{
	if( null == this.activeObj || null == this.activeLayer )
	{
		return;
	}
	
	if( null != this.zoomAnimation )
	{
		this.zoomAnimation.stop();
		this.zoomAnimation = null;
	}
	
	var scale 	= this.activeObj.getScaleX();
	
	// No more scaling beyond this level
	if( this.scale < 0.0 )
		return;
		
    this.zoomAnimation 	= new Kinetic.Animation( function( frame ) {
      
	      scale -= scale * frame.timeDiff / 4000;
	      
	      activeDesigner.activeObj.setScaleX(scale);
	      activeDesigner.activeObj.setScaleY(scale);
	          
    }, activeDesigner.activeLayer );

    this.zoomAnimation.start();
}

DesignTool.prototype.stopZooming = function()
{
	if( null != this.zoomAnimation )
	{
		this.zoomAnimation.stop();
	}
}

DesignTool.prototype.emblmReset = function()
{
	if( null == this.activeObj || null == this.activeLayer )
	{
		return;
	}

	this.activeObj.setScaleX( this.zoomScale );
	this.activeObj.setScaleY( this.zoomScale );
	this.activeObj.setRotationDeg(0);
	
	this.positionCenterStage( this.activeObj, this.designStage );

	if( null != this.designStage )
		this.designStage.draw();		
}

DesignTool.prototype.destroy = function() {
	
	if( null != this.stage ) {
		
		this.stage.destroy();
	}
}

// User Designs ---------------------------------------------------------------------------------

DesignTool.prototype.showUserDesign = function( designCounter, design ) {

	if( activeDesigner.qrSetup )
	{
		liItem	= "<li id='qr'><div class='user-design active' style='background-image: url(" + design.src + ");' onclick=activateQrCode()><div class='close' onclick=removeQrCode()></div></div></li>";

		activeDesigner.qrSetup = false;
		
		// Add the code
		jQuery("#user-design-list").prepend( liItem );	
		
		activateQrCode();	
	}	
	else if( activeDesigner.templateMode )
	{		
		jQuery(".user-design.active").css("background-image", "url("+design.src+")" );

		// Activate Design
		activateDesignLayer( activeDesigner.activeTemplate + 1 );
	}
	else 
	{
		var liItem	= null;
		
		if( null != selectedDesignUrl && !activeDesigner.designLoaded ) {
			
			activeDesigner.designLoaded	= true;
			
			liItem	= "<li id='" + designCounter + "'><div class='user-design active' style='background-image: url(" + design.src + ");' onclick=activateDesignLayer(" + designCounter + ")></div></li>";
			
			// Activate Design
			activateDesignLayer( 0 );
		}
		else {
			
			jQuery(".user-design").removeClass("active");

			liItem	= "<li id='" + designCounter + "'><div class='user-design active' style='background-image: url(" + design.src + ");' onclick=activateDesignLayer(" + designCounter + ")><div class='close' onclick=removeDesignThumb(" + designCounter + ")></div></div></li>";
			
			// Activate Design
			activateDesignLayer( designCounter );
		}
		
		// Add the design
		jQuery("#user-design-list").append( liItem );
		
		this.designCounter++;
		this.totalDesigns++;
	}	
}

function activateDesignLayer( element ) {

	jQuery(".user-design").removeClass("active");
	
	jQuery( "#user-design-list li[id='" + element + "'] .user-design").addClass("active");

	var designSmall		= null;
	var designLarge		= null;
	
	activeDesigner.activeTemplate = element - 1;

	if( activeDesigner.templateMode )
	{	
		designSmall		= activeDesigner.designLayers[ activeDesigner.activeTemplate ];
		designLarge		= activeDesigner.designlLayers[ activeDesigner.activeTemplate ];		
	}
	else 
	{
		designSmall		= activeDesigner.designLayers[ element ];
		designLarge		= activeDesigner.designlLayers[ element ];
	}

	if( null != designSmall ) 
	{
		activeDesigner.activeObj	= designSmall.designObj;

		designSmall.designObj.moveToTop();
		designLarge.designObj.moveToTop();
				
		activeDesigner.activeDesign		= designSmall;
		activeDesigner.activeDesignl	= designLarge;

		// Activate Filter
		activateFilter( designSmall.currentFilter );

		activeDesigner.designStage.draw();
		activeDesigner.designlStage.draw();
	}
	else {
		
		activeDesigner.activeObj		= null;
		activeDesigner.activeDesign		= null;
		activeDesigner.activeDesignl	= null;	
		
		clearFilter();	
	}		
}

function removeDesignThumb( element ) {
	
	jQuery( "#user-design-list li[id='" + element + "']").remove();
	
	designSmall	= activeDesigner.designLayers[ element ];
	designLarge	= activeDesigner.designlLayers[ element ];
	
	designSmall.designObj.remove();
	designLarge.designObj.remove();

	delete designSmall;
	delete designLarge;
	
	activeDesigner.designStage.draw();
	activeDesigner.designlStage.draw();
	
	activeDesigner.totalDesigns--;
}

function activateQrCode() {

	jQuery(".user-design").removeClass("active");
	
	jQuery( "#user-design-list li[id='qr'] .user-design").addClass("active");

	activeDesigner.activeObj	= activeDesigner.qrObj;
	activeDesigner.activeLayer	= activeDesigner.designObjLayer;  

	activeDesigner.qrObj.moveToTop();
	activeDesigner.qrlObj.moveToTop();		
	
	activeDesigner.designStage.draw();
	activeDesigner.designlStage.draw();	
	
	activeDesigner.activeObj		= null;
	activeDesigner.activeDesign		= null;
	activeDesigner.activeDesignl	= null;	
	
	clearFilter();
}

function removeQrCode() {
	
	if( null != activeDesigner.qrObj )
	{
		jQuery( "#user-design-list li[id='qr']").remove();
		
		activeDesigner.qrObj.remove();
		activeDesigner.qrlObj.remove();
		
		activeDesigner.designStage.draw();
		activeDesigner.designlStage.draw();
	}	
}

// Upload ---------------------------------------------------------------------------------------

function preDesignUpload()
{
	if( null != activeDesigner.designLayers && null != activeDesigner.designlLayers )
	{
		var designLayers	= activeDesigner.designLayers;
		var designlLayers	= activeDesigner.designlLayers;

		for (var i=0; i < designLayers.length; i++) {
			
			if( null != designLayers[i] )
			{
				var designSmall	= designLayers[i].designObj;
				
				if( null != designSmall )
				{
					var designLarge	= designlLayers[ i ].designObj;
					
					var newX 	= designSmall.getX() * activeDesigner.zoomFactor;
					var newY 	= designSmall.getY() * activeDesigner.zoomFactor;
					var scaleX	= designSmall.getScaleX() * activeDesigner.zoomFactor;
					var scaleY	= designSmall.getScaleY() * activeDesigner.zoomFactor;
					
				    designLarge.setX( newX );
				    designLarge.setY( newY );
				    designLarge.setScaleX( scaleX );
				    designLarge.setScaleY( scaleY );
				   	designLarge.rotation( designSmall.rotation() );
				}
			}	
		};
		
		var qrSmall	= activeDesigner.qrObj;
		var qrLarge	= activeDesigner.qrlObj;
		
		if( null != qrSmall )
		{
				var newX 	= qrSmall.getX() * activeDesigner.zoomFactor;
				var newY 	= qrSmall.getY() * activeDesigner.zoomFactor;
				var scaleX	= qrSmall.getScaleX() * activeDesigner.zoomFactor;
				var scaleY	= qrSmall.getScaleY() * activeDesigner.zoomFactor;
				
			    qrLarge.setX( newX );
			    qrLarge.setY( newY );
			    qrLarge.setScaleX( scaleX );
			    qrLarge.setScaleY( scaleY );
			   	qrLarge.rotation( qrSmall.rotation() );		
		}

	    activeDesigner.designlStage.draw();
	}    	
}

function clearDesigner()
{
		if( null != activeDesigner.designObjLayer )
		{
			activeDesigner.designObjLayer.destroyChildren();
			activeDesigner.designlObjLayer.destroyChildren();
			
			activeDesigner.designStage.draw();
			activeDesigner.designlStage.draw();
			
			jQuery( "#user-design-list").html( "" );
			
			activeDesigner.designCounter 	= 0;
			activeDesigner.totalDesigns 	= 1;	
			
			activeDesigner.templateGroup.splice( 0, activeDesigner.templateGroup.length );
			activeDesigner.templatelGroup.splice( 0, activeDesigner.templatelGroup.length );		
			activeDesigner.designLayers.splice( 0, activeDesigner.designLayers.length );
			activeDesigner.designlLayers.splice( 0, activeDesigner.designlLayers.length );
			
			activeDesigner.templateMode	= false;
		}	
}

// Templates ------------------------------------------------------------------------------------

function initTemplate( title, shape1, shape2, shape3, shape4, shape5 )
{
	// Clear the Designer
	clearDesigner();
	
	// Activate template mode	
	activeDesigner.templateMode		= true;
	activeDesigner.activeTemplate	= 0;
	
	// Initialise Templates
	var liItem	= null;
	
	addShape( shape1, true );
	addShape( shape2, false );
	addShape( shape3, false );
	addShape( shape4, false );
	addShape( shape5, false );

	jQuery("#t-title").html( title );
	jQuery("#template-container").show();
}

function addShape( shape, active )
{
	if( null != shape && shape.length > 0 ) {
		
		activeDesigner.designCounter++;
		activeDesigner.totalDesigns++;
		
		var s		= shape.split(",");
		
		if( active )
		{
			liItem		= "<li id='" + activeDesigner.designCounter + "'><div class='user-design active' onclick=activateDesignLayer(" + activeDesigner.designCounter + ")></div></li>";
		}
		else 
		{
			liItem		= "<li id='" + activeDesigner.designCounter + "'><div class='user-design' onclick=activateDesignLayer(" + activeDesigner.designCounter + ")></div></li>";
		}
		
		// Add the design
		jQuery("#user-design-list").append( liItem );	
		
		var index	= activeDesigner.designCounter - 1 ;
				
		var t 		= ( activeDesigner.designerHeight * s[0] ) / 100;
		var l 		= ( activeDesigner.designerWidth * s[1] ) / 100;
		var w 		= ( activeDesigner.designerWidth * s[2] ) / 100;
		var h 		= ( activeDesigner.designerHeight * s[3] ) / 100;
		var group	= new Kinetic.Group();

		activeDesigner.templateGroup[ index ] = group;
		activeDesigner.designObjLayer.add( group );
		group.clip( {x: l, y: t, width: w, height: h} );
      	activeDesigner.designStage.draw();
      	
		t 		= ( activeDesigner.designerHeight * s[0] * activeDesigner.zoomFactor) / 100;
		l 		= ( activeDesigner.designerWidth * s[1] * activeDesigner.zoomFactor) / 100;
		w 		= ( activeDesigner.designerWidth * s[2] * activeDesigner.zoomFactor) / 100;
		h 		= ( activeDesigner.designerHeight * s[3] * activeDesigner.zoomFactor) / 100;
		group	= new Kinetic.Group();

		activeDesigner.templatelGroup[ index ] = group;
		activeDesigner.designlObjLayer.add( group );
		group.clip( {x: l, y: t, width: w, height: h} );
      	activeDesigner.designlStage.draw();      	
	}	
}

// Design Layer ---------------------------------------------------------------------------------

function DesignLayer( designObj ) {
	
	this.designObj				= designObj;
	
	this.previousFilter			= 0;
	this.currentFilter			= 0;	
}

// Filters --------------------------------------------------------------------------------------

var FILTER_NONE			= 0;
var FILTER_BLUR			= 1;
var FILTER_SEPIA		= 2;
var FILTER_GRAYSCALE	= 3;
var FILTER_RGB			= 4;
var FILTER_BRIGHTEN		= 5;
var FILTER_POSTERIZE	= 6;
var FILTER_PIXELATE		= 7;
var FILTER_SOLARIZE		= 8;
var FILTER_KALSCOPE		= 9;
var FILTER_INVERT		= 10;
var FILTER_NOISE		= 11;

function initFilters()
{
	jQuery("#filter-panel").show();

	 // Blur Filter
	 jQuery("#slider-blur").on( "slidechange", function( event, ui ) {
	 		
	 		applyBlur( ui.value );
	 });
	 
	 // RGB Filter
	 jQuery("#slider-red").on( "slidechange", function( event, ui ) {
	 		
	 		applyRed( ui.value );
	 });
	 
	 jQuery("#slider-green").on( "slidechange", function( event, ui ) {
	 		
	 		applyGreen( ui.value );
	 });
	 
	 jQuery("#slider-blue").on( "slidechange", function( event, ui ) {
	 		
	 		applyBlue( ui.value );
	 });

	 // Brightness Filter
	 jQuery("#slider-brighten").on( "slidechange", function( event, ui ) {
	 		
	 		applyBrightness( ui.value );
	 });

	 // Posterize Filter
	 jQuery("#slider-posterize").on( "slidechange", function( event, ui ) {
	 		
	 		applyPosterize( ui.value );
	 });
}

function activateFilter( filterId )
{
	var activeDesign	= activeDesigner.activeDesign;

	if( null != activeDesign )
	{
		activeDesign.previousFilter	= activeDesign.currentFilter;
		activeDesign.currentFilter 	= parseInt( filterId );
		
		switch( activeDesign.currentFilter )
		{
			case FILTER_NONE:
			{
				clearFilter();
				
				break;	
			}
			case FILTER_BLUR:
			{
				activateBlur();
	
				break;
			}
			case FILTER_SEPIA:
			{
				activateSolo( FILTER_SEPIA );
	
				break;
			}
			case FILTER_GRAYSCALE:
			{
				activateSolo( FILTER_GRAYSCALE );
				
				break;
			}
			case FILTER_RGB:
			{
				activateRGB();
	
				break;
			}
			case FILTER_BRIGHTEN:
			{
				activateBrightness();
	
				break;
			}			
			case FILTER_POSTERIZE:
			{
				activatePosterize();

				break;
			}
			case FILTER_PIXELATE:
			{
				activateSolo( FILTER_PIXELATE );

				break;
			}
			case FILTER_SOLARIZE:
			{
				activateSolo( FILTER_SOLARIZE );

				break;
			}	
			case FILTER_KALSCOPE:
			{
				activateSolo( FILTER_KALSCOPE );
				
				break;
			}
			case FILTER_INVERT:
			{
				activateSolo( FILTER_INVERT );
				
				break;				
			}	
			case FILTER_NOISE:
			{
				activateNoise( FILTER_NOISE );
				
				break;
			}	
		}
	}
	else {

		alert( ALERT_CHOOSE_FILTER );
		
		jQuery("#filter-select-box").val( 0 );
		jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );		
	}	
}

function clearFilter()
{
	jQuery(".filter-controls, .filter").hide();
	
	if( null != activeDesigner.activeDesign )
	{		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;

		switch( activeDesigner.activeDesign.previousFilter )
		{
			case FILTER_BLUR:
			{
				designSmall.blurRadius( 0 );	
				designLarge.blurRadius( 0 );

				break;
			}
			case FILTER_RGB:
			{
				designSmall.red( 0 );	
				designLarge.green( 0 );
				designLarge.blue( 0 );
		
				break;
			}
		}
		
		activeDesigner.designObjLayer.batchDraw();
		activeDesigner.designlObjLayer.batchDraw();	
		
		// Clear Filter
		designSmall.filters( [] );
		designLarge.filters( [] );		
	}
	
	jQuery("#filter-select-box").val( 0 );
	jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );
}

function activateBlur()
{	
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		jQuery(".filter-controls").show();
		jQuery(".filter").hide();
		jQuery(".filter-blur").show();
		
		jQuery(".filter-blur").css( "display:", "inline-block" );	

		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// cache Designs
		designSmall.cache();
		designLarge.cache();
		
		// Set Filter
		designSmall.filters( [Kinetic.Filters.Blur] );	
		designLarge.filters( [Kinetic.Filters.Blur] );

		// Restore filter
		jQuery('#slider-blur').slider('value', designSmall.blurRadius() );
		
		jQuery("#filter-select-box").val( FILTER_BLUR );
		jQuery("#filter-panel .select .styledSelect").html( jQuery("#filter-select-box option").eq( FILTER_BLUR ).html() );
	}
	else {
		
		alert( ALERT_CHOOSE_FILTER );
		
		jQuery("#filter-select-box").val( 0 );
		jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );
	}
}

function applyBlur( blurValue ) 
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// Set Filter
		designSmall.blurRadius( blurValue );	
		designLarge.blurRadius( blurValue );
	}
	
	activeDesigner.designObjLayer.batchDraw();
	activeDesigner.designlObjLayer.batchDraw();	
}

function activateSolo( filter )
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		jQuery(".filter-controls").show();
		jQuery(".filter").hide();

		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// cache Designs
		designSmall.cache();
		designLarge.cache();
		
		switch( filter )
		{
			case FILTER_SEPIA:
			{
				designSmall.filters( [Kinetic.Filters.Sepia] );	
				designLarge.filters( [Kinetic.Filters.Sepia] );
								
				break;
			}
			case FILTER_GRAYSCALE:
			{
				designSmall.filters( [Kinetic.Filters.Grayscale] );	
				designLarge.filters( [Kinetic.Filters.Grayscale] );
								
				break;
			}
			case FILTER_PIXELATE:
			{
				designSmall.filters( [Kinetic.Filters.Pixelate] );	
				designLarge.filters( [Kinetic.Filters.Pixelate] );
								
				break;
			}
			case FILTER_SOLARIZE:
			{
				designSmall.filters( [Kinetic.Filters.Solarize] );	
				designLarge.filters( [Kinetic.Filters.Solarize] );
								
				break;
			}	
			case FILTER_KALSCOPE:
			{
				designSmall.filters( [Kinetic.Filters.Kaleidoscope] );	
				designLarge.filters( [Kinetic.Filters.Kaleidoscope] );
								
				break;
			}
			case FILTER_INVERT:
			{
				designSmall.filters( [Kinetic.Filters.Invert] );	
				designLarge.filters( [Kinetic.Filters.Invert] );
								
				break;
			}
			case FILTER_NOISE:
			{
				designSmall.filters( [Kinetic.Filters.Noise] );	
				designLarge.filters( [Kinetic.Filters.Noise] );
								
				break;
			}		
		}
		
		jQuery("#filter-select-box").val( filter );
		jQuery("#filter-panel .select .styledSelect").html( jQuery("#filter-select-box option").eq( filter ).html() );
				
		activeDesigner.designObjLayer.batchDraw();
		activeDesigner.designlObjLayer.batchDraw();	
	}
	else {
		
		alert( ALERT_CHOOSE_FILTER );
		
		jQuery("#filter-select-box").val( 0 );
		jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );
	}	
}

function activateRGB()
{	
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		jQuery(".filter-controls").show();
		jQuery(".filter").hide();
		jQuery(".filter-rgb").show();
		
		jQuery(".filter-rgb").css( "display:", "inline-block" );	

		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// cache Designs
		designSmall.cache();
		designLarge.cache();
		
		// Set Filter
		designSmall.filters( [Kinetic.Filters.RGB] );	
		designLarge.filters( [Kinetic.Filters.RGB] );

		// Restore filter
		jQuery('#slider-red').slider('value', designSmall.red() );
		jQuery('#slider-green').slider('value', designSmall.green() );
		jQuery('#slider-blue').slider('value', designSmall.blue() );
		
		jQuery("#filter-select-box").val( FILTER_RGB );
		jQuery("#filter-panel .select .styledSelect").html( jQuery("#filter-select-box option").eq( FILTER_RGB ).html() );
	}
	else {
		
		alert( ALERT_CHOOSE_FILTER );
		
		jQuery("#filter-select-box").val( 0 );
		jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );
	}
}

function applyRed( redValue ) 
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// Set Filter
		designSmall.red( redValue );	
		designLarge.red( redValue );
	}
	
	activeDesigner.designObjLayer.batchDraw();
	activeDesigner.designlObjLayer.batchDraw();	
}

function applyBlue( blueValue ) 
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// Set Filter
		designSmall.blue( blueValue );	
		designLarge.blue( blueValue );
	}
	
	activeDesigner.designObjLayer.batchDraw();
	activeDesigner.designlObjLayer.batchDraw();	
}

function applyGreen( greenValue ) 
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// Set Filter
		designSmall.green( greenValue );	
		designLarge.green( greenValue );
	}
	
	activeDesigner.designObjLayer.batchDraw();
	activeDesigner.designlObjLayer.batchDraw();	
}

function activateBrightness()
{	
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		jQuery(".filter-controls").show();
		jQuery(".filter").hide();
		jQuery(".filter-brighten").show();
		
		jQuery(".filter-brighten").css( "display:", "inline-block" );	

		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// cache Designs
		designSmall.cache();
		designLarge.cache();
		
		// Set Filter
		designSmall.filters( [Kinetic.Filters.Brighten] );	
		designLarge.filters( [Kinetic.Filters.Brighten] );

		// Restore filter
		jQuery('#slider-brighten').slider('value', designSmall.brightness() * 100 );
		
		jQuery("#filter-select-box").val( FILTER_BRIGHTEN );
		jQuery("#filter-panel .select .styledSelect").html( jQuery("#filter-select-box option").eq( FILTER_BRIGHTEN ).html() );
	}
	else {
		
		alert( ALERT_CHOOSE_FILTER );
		
		jQuery("#filter-select-box").val( 0 );
		jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );
	}
}

function applyBrightness( brightnessValue ) 
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// Set Filter
		designSmall.brightness( brightnessValue / 100 );	
		designLarge.brightness( brightnessValue / 100 );
	}
	
	activeDesigner.designObjLayer.batchDraw();
	activeDesigner.designlObjLayer.batchDraw();	
}

function activatePosterize()
{	
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		jQuery(".filter-controls").show();
		jQuery(".filter").hide();
		jQuery(".filter-posterize").show();
		
		jQuery(".filter-posterize").css( "display:", "inline-block" );	

		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// cache Designs
		designSmall.cache();
		designLarge.cache();
		
		// Set Filter
		designSmall.filters( [Kinetic.Filters.Posterize] );	
		designLarge.filters( [Kinetic.Filters.Posterize] );
		
		// Restore filter
		jQuery('#slider-posterize').slider('value', designSmall.levels() * 255 );
		
		jQuery("#filter-select-box").val( FILTER_POSTERIZE );
		jQuery("#filter-panel .select .styledSelect").html( jQuery("#filter-select-box option").eq( FILTER_POSTERIZE ).html() );
	}
	else {
		
		alert( ALERT_CHOOSE_FILTER );
		
		jQuery("#filter-select-box").val( 0 );
		jQuery("#filter-panel .select .styledSelect").html( "Choose Filter" );
	}
}

function applyPosterize( levelValue ) 
{
	// Set Filter for Active Design
	if( null != activeDesigner.activeDesign ) {
		
		designSmall	= activeDesigner.activeDesign.designObj;
		designLarge	= activeDesigner.activeDesignl.designObj;
		
		// Set Filter
		designSmall.levels( levelValue / 255 );	
		designLarge.levels( levelValue / 255 );
	}
	
	activeDesigner.designObjLayer.batchDraw();
	activeDesigner.designlObjLayer.batchDraw();	
}

// Browser Feature Detection --------------------------------------------------------------------

function canvasSupported()
{
	// Canvas support
	var elem 			= document.createElement('canvas');
	var canvasSupported = !!(elem.getContext && elem.getContext('2d'));
	
	return canvasSupported;
}

function isCanvasSupported()
{
	// Canvas support
	var elem 			= document.createElement('canvas');
	var canvasSupported = !!(elem.getContext && elem.getContext('2d'));
	
	if( !canvasSupported )
	{
		return false;
	}
	
	// ToDataURL canvas support
	var cvsTest 			= document.createElement("canvas");
	var data				= cvsTest.toDataURL("image/png");
	var toDataUrlSupported	= data.indexOf("data:image/png") == 0;

	if( !toDataUrlSupported )
	{
		return false;
	}
	
	if( !isFileApiSupported() && !isFormDataSupported() )
	{
		return false;
	}	
	
	return true;
}

function isFileApiSupported()
{
	// File API Support
	var fileApiSupported = false;
	
	if ( window.File && window.FileList && window.FileReader )
	{
		fileApiSupported = true;
	}

	return fileApiSupported;	
}

function isFormDataSupported()
{
	return !! window.FormData;
}

function getQualityError()
{
	var widthCheck	= activeDesigner.designlStage.getWidth() / activeDesigner.qualityRatio;
	var heightCheck	= activeDesigner.designlStage.getHeight() / activeDesigner.qualityRatio; 
			
	return ALERT_DESIGN_QUALITY + parseInt(widthCheck) + "px X " + parseInt(heightCheck) + "px";
}

// File Utility ---------------------------------------------------------------------------------

// The method loadUserDesign allows users to use their own design on the designer tool.
// It shows the local file on canvas for Chrome, Firefox and Opera.
// It upload and than show the file for Safari since SAfari do not allow to use local file for Canvas.
function loadUserDesign() {
	
	if ( isFileApiSupported() )
	{
		// Update the canvas to show user image
		
		jQuery( '#user_design_chooser' ).unbind('change');
		
		jQuery( '#user_design_chooser' ).bind( 'change', function(event){
			
			if( activeDesigner.totalDesigns > 5 ) {

				alert( ERROR_MAX_DESIGN );

				return;					
			}
			
			event.stopPropagation();
			event.preventDefault();
			event.target.className = (event.type == "dragover" ? "hover" : ""); 
			
			// Show the Designer Spinner
			showSpinner( "designer-spinner" );
			
			var files 		= event.target.files || event.originalEvent.dataTransfer.files;	
			var file		= files[0];
			var image_url 	= window.URL || window.webkitURL;
    		var image_src 	= image_url.createObjectURL(file);
    		designFileName 	= file.name;
    		var sizeInKb	= file.size / 1024;

    		// Hide the ratings
    		if( null == selectedDesignUrl )
    		{
				jQuery("#rating-container").hide();	
		    }
		    
		    if( sizeInKb < activeDesigner.qualitySize )
		    {
		    	alert( getQualityError() );
		    }	
		    	
			// Show user design on canvas
			activeDesigner.loadDesign( image_src );
		} );
	}
	else if( isFormDataSupported() )
	{
		jQuery( '#user_design_chooser' ).unbind('change');
		
		jQuery( '#user_design_chooser' ).bind( 'change', function(event){ 
					
			var file 		= document.getElementById('user_design_chooser').files[0];
			var formData 	= new FormData();
			designFileName 	= file.name;
			
			formData.append( 'filetoprocess', file );
			
			// Show the Designer Spinner
			showSpinner( "designer-spinner" );
			
			jQuery.ajax({
			  type: "POST",
			  url: EU.ajaxurl + "?action=ecart_handle_order_design",
			  data: formData,
			  processData: false,
       		  contentType: false,
			}).done(function( response ) {
				
				// Hide the ratings
				jQuery("#rating-container").hide();	
							
				// Show user design on canvas
				activeDesigner.loadDesign( response );
			});	
		});	
	}
}

// Load the user design - This design can be act on user activities - Zoom In/ Zoom Out, Rotate, Crop
function loadUserAvatar() {
	
	if ( isFileApiSupported() )
	{
		jQuery( '#avatar_chooser' ).unbind('change');
		
		// Update the canvas to show user image
		jQuery( '#avatar_chooser' ).bind( 'change', function(event){ 

			event.stopPropagation();
			event.preventDefault();
			event.target.className = (event.type == "dragover" ? "hover" : ""); 
			
			var files 		= event.target.files || event.originalEvent.dataTransfer.files;
			var file		= files[0];
			var image_url 	= window.URL || window.webkitURL;
    		var image_src 	= image_url.createObjectURL(file);
			avatarFileName	= file.name;
			
			activeDesigner.loadAvatar( image_src );
		} );
	}
	else if( formDataSupported() )
	{
		jQuery( '#avatar_chooser' ).unbind('change');
		
		jQuery( '#avatar_chooser' ).bind( 'change', function(event){ 
					
			var file 		= document.getElementById('avatar_chooser').files[0];
			var formData 	= new FormData();
			avatarFileName 	= file.name;
			
			formData.append( 'filetoprocess', file );
			
			// Show the Designer Spinner
			showSpinner( "designer-spinner" );
			
			jQuery.ajax({
			  type: "POST",
			  url: EU.ajaxurl + "?action=user_handle_avatar",
			  data: formData,
			  processData: false,
       		  contentType: false,
			}).done(function( response ) {
				
				// Show user avatar on canvas
				activeDesigner.loadAvatar( image_src );
			});	
		});
	}
}

// Load the user design - This design can be act on user activities - Zoom In/ Zoom Out, Rotate, Crop
function loadUserDesignMk(){
	
	if ( isFileApiSupported() )
	{
		jQuery( '#user_d_chooser_mk' ).unbind('change');
		
		// Update the canvas to show user image
		jQuery( '#user_d_chooser_mk' ).bind( 'change', function(event){ 

			event.stopPropagation();
			event.preventDefault();
			event.target.className = (event.type == "dragover" ? "hover" : ""); 
			
			// Show the Designer Spinner
			showSpinner( "designer-spinner" );
						
			var files 			= event.target.files || event.originalEvent.dataTransfer.files;
			var file			= files[0];
			var image_url 		= window.URL || window.webkitURL;
    		var image_src 		= image_url.createObjectURL(file);
			designFileName 		= file.name;

			activeDesigner.loadDesign( image_src );
		});
	}
	else if( isFormDataSupported() )
	{
		jQuery( '#user_d_chooser_mk' ).unbind('change');
		
		jQuery( '#user_d_chooser_mk' ).bind( 'change', function(event){ 

			var file 		= document.getElementById('user_design_chooser_mk').files[0];
			var formData 	= new FormData();
			designFileName 	= file.name;
			
			formData.append( 'filetoprocess', file );
			
			// Show the Designer Spinner
			showSpinner( "designer-spinner" );	
			
			jQuery.ajax({
			  type: "POST",
			  url: EU.ajaxurl + "?action=user_handle_mk_design",
			  data: formData,
			  processData: false,
       		  contentType: false,
			}).done(function( response ) {
				
				activeDesigner.loadDesign( response );
			});				
		});			
	}	
}

// Technology Layers ----------------------------------------------------------------------------

var techStage	= null;

var techLayer	= null;
var techImg		= null;
var techImgObj	= null;
var countTech	= 3;
var techLoaded	= 0;

var layer1Poly	= [ [0,150],   [168,0],  [270,25], [107,170] ];
var layer2Poly	= [ [0,200],  [168,53],  [270,78], [107,223] ];
var layer3Poly	= [ [0,250], [168,100], [270,125], [107,270] ];

var layer1Hit	= false;
var layer2Hit	= false;
var layer3Hit	= false;

function initTechCanvas()
{
	techStage	= new Kinetic.Stage( { container: 'canvas-container', width: 420, height: 520 } );
} 	
   
function showTechLayers( toplayerSrc, artlayerSrc, botttomlayerSrc )
{
	// 1. Create the Tech Layer objects
	techImgSrc 	= [ toplayerSrc, artlayerSrc, botttomlayerSrc ];

	techImg 	= new Array();
    techImgObj	= new Array();
    techLayer	= new Array();
    
    for( i = 0; i < countTech; i++ )
    {
    	techLayer[i]	= new Kinetic.Layer();
    	techImgObj[i]	= null; 	
    }

    for( i = 0; i < 3; i++ )
    {
    	techImg[i] 		= new Image();
    	techImg[i].src 	= techImgSrc[i];    	
    	
	    techImg[i].onload = function() {

			techLoaded++;
			
			if( i == techLoaded )
			{
				techLayersLoaded();
			}
		};
    }
}

function techLayersLoaded()
{
	var techX	= 80;
	var techYAd	= [ 42, 95, 140, 277 ];
	
	// Create the Kinetic Images
    for( i = 0; i < countTech; i++ )
    {	
		techY		= techYAd[i];
		
		keepTechLayerAspectRatio( techImg[i], 400 );
		
		techImgObj[i] = new Kinetic.Image( { x: techX, y: techY, image: techImg[i], offset: [ techImg[i].width/2, techImg[i].height/2 ] } );
	}
	
    for( i = countTech-1; i >= 0; i-- )
    {
        techLayer[i].add( techImgObj[i] );
        
        techStage.add( techLayer[i] );    
	}
	
    // Add the layer listeners for Post-Coat, Print and pre-Coat
    jQuery("#canvas-container").mousemove(function(e){
	   
		checkActiveLayer( jQuery(this).parent().offset(), e, true );
	});
	
    jQuery("#canvas-container").click(function(e){
	   
		checkActiveLayer( jQuery(this).parent().offset(), e, true );
	});		
}

function keepTechLayerAspectRatio( image, targetWidth )
{
    var ratio 	= 0;
    var width 	= image.width;
    var height 	= image.height;

    // Check if the current width is larger than the max
    if( width > targetWidth ){
        ratio 		= targetWidth / width;
        height 		= height * ratio;
        width 		= width * ratio;
    }
	
	image.width 	= width;
	image.height 	= height;
}

function pointInPoly( polyCords, pointX, pointY )
{
 
	var i, j, c = false;
 
	for (i = 0, j = polyCords.length - 1; i < polyCords.length; j = i++)
	{
 
		if ( ((polyCords[i][1] > pointY) != (polyCords[j][1] > pointY)) && (pointX < (polyCords[j][0] - polyCords[i][0]) * (pointY - polyCords[i][1]) / (polyCords[j][1] - polyCords[i][1]) + polyCords[i][0]) )
		{
			c = !c;
		}
 
	}

	return c;
}

function checkActiveLayer( elem, e, lock )
{
   	var relX 			= e.pageX - elem.left - 130;
   	var relY 			= e.pageY - elem.top - 50;
   	
    //alert(relX + " " + relY );
   	// Check for Top Layer
   	if( pointInPoly( layer1Poly, relX, relY) )
   	{
   		if( !layer1Hit )
   			animateSpotlight();
   			
   		layer1Hit = true; layer2Hit = false; layer3Hit = false;
   	}
   	else if( !layer1Hit && pointInPoly( layer2Poly, relX, relY) )
   	{
   		if( !layer2Hit )
   			animateSpotlight();
   			
   		layer1Hit = false; layer2Hit = true; layer3Hit = false;
   	}
   	else if( !layer1Hit && !layer2Hit && pointInPoly( layer3Poly, relX, relY) )
   	{
   		if( !layer3Hit )
   			animateSpotlight();
   			
   		layer1Hit = false; layer2Hit = false; layer3Hit = true;
   	}
   	else
   	{
   		layer1Hit = false; layer2Hit = false; layer3Hit = false;
   		activateTechLayer( currentAboutClasses );
   	}
   	
   	if( layer1Hit ){
		activateTechLayer( ".content-post" );
		
		if(lock) currentAboutClasses = ".content-post";
		
		showTechObjO(0);
   	}
   	else if( layer2Hit ){
		activateTechLayer( ".content-print" );
		
		if(lock) currentAboutClasses = ".content-print";
		
		showTechObjO(1);
   	}	
   	else if( layer3Hit ){
		activateTechLayer( ".content-pre" );
		
		if(lock) currentAboutClasses = ".content-pre";
		
		showTechObjO(2);
   	}	
}

function activateTechLayer( showHide )
{
	jQuery("#content-controller h1, #content-list li").css( {opacity: "0.1"} );
	jQuery("#content-controller h1, #content-list li").removeClass("content-active");
	
	var elem = jQuery( showHide );
	
	elem.css( {opacity: "1.0"} );
	
	elem.addClass("content-active");
}

function showTechObjO( index )
{	
	techImgObj[0].setOpacity(0.2);
	techImgObj[1].setOpacity(0.2);
	techImgObj[2].setOpacity(0.2);
	
	//setOpacity(opacity);
	
	techImgObj[index].setOpacity(1);
	
	techLayer[0].draw();	techLayer[1].draw();	techLayer[2].draw();
}
