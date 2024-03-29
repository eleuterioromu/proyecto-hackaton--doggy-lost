<!doctype html>
<html lang="en">
<head>

<title>Realidad Aumentada</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<style>

body {
  margin: 0;
  padding: 0;
  background: #000;
  font-family: "Georgia", serif;
}


/*
  Container for the gallery:

  Absolutely positioned
  Stretch to fill the whole window width
  Fixed height
  Hide the overflow to prevent horizontal scrollbars

  Vertically centred in the viewport: http://css-discuss.incutio.com/wiki/Centering_Block_Element#Centering_an_absolutely_positioned_element 
*/

#galleryContainer {
  width: 100%;
  height: 800px;        /* Image height + 200px */
  overflow: hidden;
  position: absolute;
  top: 0;
  bottom: 0;
  margin-top: auto;
  margin-bottom: auto;
  z-index: 1;
}


/*
  The gallery div that contains all the images

  We'll set the width dynamically in the JavaScript as the images load
*/

#gallery {
  width: 100px;
  height: 700px;        /* Image height + 100px */
  padding: 50px 0;
  position: absolute;
  z-index: 1;
}


/*
  Individual slides within the gallery:

  Float them left so that they're all side by side
  Fixed height (the width will vary as required)
  Add some horizontal margin between the slides
  Add a bottom fading reflection for WebKit browsers
*/

#gallery img {
  float: left;
  height: 600px;
  margin: 0 100px;      /* Adjust the left/right margin to show greater or fewer slides at once */

}


/*
  Left and right buttons:

  Position them on the left and right sides of the gallery
  Stretch them to the height of the gallery
  Hide them by default
*/

#leftButton, #rightButton {
  position: absolute;
  z-index: 2;
  top: -100px;
  bottom: 0;
  padding: 0;
  margin: auto 0;
  width: 15%;
  height: 600px;        /* Image height */
  border: none;
  outline: none;
  color: #fff;
  background: transparent url(images/blank.gif);
  font-size: 100px;
  font-family: "Courier New", courier, fixed;
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: opacity .5s;
  -moz-transition: opacity .5s;
  -o-transition: opacity .5s;
  transition: opacity .5s;
}

#leftButton {
  left: 0;
}

#rightButton {
  right: 0;
}

/* (Turn off dotted black outline on FF3) */

#leftButton::-moz-focus-inner, #rightButton::-moz-focus-inner {
  border: none;
}

/*
  Left and right button hover states:
  Fade them in to 50% opacity
*/

#leftButton:hover, #rightButton:hover {
  opacity: .5;
  filter: alpha(opacity=50);
  outline: none;
}


/*
  Image caption:

  Position just under the centre image
  Hide by default
*/

#caption {
  position: absolute;
  z-index: 2;
  bottom: 90px;
  width: 100%;
  color: #ffc;
  text-align: center;
  font-family: "Georgia", serif;
  font-size: 24px;
  letter-spacing: .1em;
  display: none;
}


/*
  Loading text:

  Position in the centre of the gallery container
  Hide by default
*/

#loading {
  position: absolute;
  z-index: 1;
  bottom: 50%;
  width: 100%;
  color: #ffc;
  text-align: center;
  font-family: "Georgia", serif;
  font-size: 36px;
  letter-spacing: .1em;
  opacity: 0;
  filter: alpha(opacity=0);
}


/*
  Tutorial info box:

  Position it in the bottom right corner of the window
  Give the 'i' h1 a circular border
  Hide the whole div by default
  Fade it in on hover 
*/

#info {
  color: #ffc;
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 99;
  border: none;
  border-radius: 20px;
  padding: 20px;
  background: transparent;
  -webkit-transition: background-color .5s;
  -moz-transition: background-color .5s;
  -o-transition: background-color .5s;
  transition: background-color .5s;
  font-size: 70%;
}

#info>* {
  margin: 20px 40px 30px 0;
  cursor: default;
  opacity: 0;
  filter: alpha(opacity=0);
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  -webkit-transition: opacity .5s;
  -moz-transition: opacity .5s;
  -o-transition: opacity .5s;
  transition: opacity .5s;
  zoom: 1;  /* Force elements to be positioned in IE7, otherwise opacity doesn't work! */
}

#info p {
  opacity: 0;
  filter: alpha(opacity=0);
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
}

#info h1 {
  position: fixed;
  z-index: 99;
  margin: 0;
  padding: 17px 1px 4px 14px;
  right: 30px;
  bottom: 30px;
  width: 20px;
  height: 14px;
  font-size: 20px;
  border: 3px solid #ffc;
  line-height: 1px;
  border-radius: 20px;
  color: #ffc;
  opacity: .5;
  filter: alpha(opacity=50);
}

#info:hover, #info.hover {
  background: rgba(50,50,50,.6);
}

#info:hover *, #info.hover * {
  opacity: 1;
  filter: alpha(opacity=100);
  color: #ffc;
}

/* (Give the link the same colour as the other text in the box) */

#info a {
  color: #ffc;
}

</style>


<!-- IE7 positions the buttons incorrectly; compensate -->

<!--[if lt IE 8]>
<style>
#leftButton, #rightButton {
  top: 50px;
}
</style>
<![endif]-->


<!-- IE7/8 spectacularly fail to cope with fading in a semitransparent background on hover; use an opaque bg instead -->

<!--[if lt IE 9]>
<style>
#info:hover, #info.hover {
  background: rgb(50,50,50);
}
</style>
<![endif]-->


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="jquery.jswipe-0.1.2.js"></script>
<script type="text/javascript">


//  --- Begin Config ---
var preloadSlides = 3;                // Number of slides to preload before showing gallery
var loadingMessageDelay = 2000;       // How long to wait before showing loading message (in ms)
var loadingMessageSpeed = 1200;       // Duration of each pulse in/out of the loading message (in ms)
var loadingMessageMinOpacity = 0.4;   // Minimum opacity of the loading message
var loadingMessageMaxOpacity = 1;     // Maximum opacity of the loading message
var captionSpeed = 1200;              // Duration of the caption fade in/out (in ms)
var captionOpacity = 0.5;             // Maximum opacity of the caption when faded in
var swipeXThreshold = 30;             // X-axis minimum threshold for swipe action (in px) 
var swipeYThreshold = 90;             // Y-axis maximum threshold for swipe action (in px) 
var leftKeyCode = 37;                 // Character code for "move left" key (default: left arrow)
var rightKeyCode = 39;                // Character code for "move right" key (default: right arrow)
var currentSlideOpacity = 1.0;        // Opacity of the current (centre) slide
var backgroundSlideOpacity = 0.5;     // Opacity of the slides either side of the current slide
//  --- End Config ---

var slideHorizMargin = 0;             // Number of pixels either side of each slide
var buttonHeight = 0;                 // Temporary store for the button heights
var currentSlide = 0;                 // The slide that the user is currently viewing
var totalSlides = 0;                  // Total number of slides in the gallery
var slides = new Array();             // Holds jQuery objects representing each slide image
var slideWidths = new Array();        // Holds the widths (in pixels) of each slide
var slideLoaded = new Array();        // True if the given slide image has loaded
var loading = true;                   // True if we're still preloading images prior to displaying the gallery

$( init );


// Set up the gallery once the document is ready

function init() {

  // Grab the horizontal margin between slides for later calculations
  slideHorizMargin = parseInt( $('#gallery img').css('margin-left') );

  // Hide the gallery and left/right buttons
  $('#gallery').fadeTo( 0, 0 );
  $('#gallery').css('top','-999em');
  buttonHeight = $('#leftButton').css('height');
  $('#leftButton').css('height',0);
  $('#rightButton').css('height',0);

  // If the requried number of slides haven't loaded after 'loadingMessageDelay' ms,
  // start fading in the loading message

  $('#loading').delay( loadingMessageDelay );
  fadeInLoadingMessage();

  // Bind the handleSlideLoad() handler function to each slide's load event
  $('#gallery img').load( handleSlideLoad );

  // For each of the slide images:
  // 1. Hide the slide
  // 2. Record its serial number (0 = the first slide)
  // 3. Store it in the slides array
  // 4. Trigger the load event if the image is already cached (for IE and Opera)

  $('#gallery img').each( function() {
    $(this).hide();
    $(this).data( 'slideNum', totalSlides );
    slides[totalSlides++] = $(this);
    if ( this.complete ) $(this).trigger("load");
    $(this).attr( 'src', $(this).attr('src') );
  } );

  // Re-centre the current slide whenever the user resizes the browser
  $(window).resize( centreCurrentSlide ); 

  // Set the initial show/hide states of the left and right buttons
  setButtonStates();

  // Set the caption text to the alt text of the first slide
  $('#caption').html( slides[currentSlide].attr('alt') );

  // Bind the moveRight() and moveLeft() functions to
  // the swipeLeft and swipeRight events respectively.
  // (IE chokes on the swipe plugin, so skip this code on IE)

  if ( !$.browser.msie ) {

    $('#gallery').swipe( {
         swipeLeft: moveRight,
         swipeRight: moveLeft,
         threshold: { x:swipeXThreshold, y:swipeYThreshold }
    } );
  }

  // Bind the moveleft() and moveRight() functions to the
  // "move left" and "move right" keys on the keyboard

  $(document).keydown( function(event) {
    if ( event.which == leftKeyCode ) moveLeft();
    if ( event.which == rightKeyCode ) moveRight();
  } );

  // Show/hide the tutorial info message when touched (for touch devices)
  $('#info').bind( 'touchstart', function() { $(this).toggleClass('hover'); } );
}


// Process each slide once it's finished loading

function handleSlideLoad() {

  // Record the slide's width in the slideWidths array
  slideWidths[$(this).data('slideNum')] = $(this).width();

  // Increase the gallery div's width to encompass this newly-loaded slide
  $('#gallery').width( $('#gallery').width() + $(this).width() + slideHorizMargin*2 );

  // Record the fact that this slide has loaded in the slideLoaded array
  slideLoaded[$(this).data('slideNum')] = true;

  // Are we still preloading the slides?

  if ( loading ) {

    // Yes: Calculate how many slides we've now preloaded

    var preloaded = 0;

    for ( var i=0; i < preloadSlides; i++ ) {
      if ( slideLoaded[i] ) preloaded++;
    }

    // If we've preloaded enough slides, fade in the gallery and enable the left/right buttons

    if ( preloaded == preloadSlides || preloaded == totalSlides ) {
      $('#loading').clearQueue().stop().fadeTo('slow', 0 );
      $('#gallery').css('top',0);
      $('#gallery').fadeTo('slow', 1 );
      $('#leftButton').css('height',buttonHeight);
      $('#rightButton').css('height',buttonHeight);
      $('#rightButton').show();
      addSlideHover();
      loading = false;
    }
  }

  // If this newly-loaded slide is the first slide in the gallery,
  // centre it in the browser viewport and set its opacity to currentSlideOpacity.
  // Otherwise, set its opacity to backgroundSlideOpacity.

  if ( $(this).data('slideNum') == 0 ) {
    centreCurrentSlide();
    $(this).fadeTo( 'slow', currentSlideOpacity );
  } else {
    $(this).fadeTo( 'slow', backgroundSlideOpacity );
  }

}


// Move one slide to the left by sliding the gallery left-to-right

function moveLeft() {

  // Don't move if this is the first slide, or if we don't yet have a width for the previous slide
  if ( currentSlide == 0 ) return;
  if ( slideWidths[currentSlide-1] == undefined ) return;

  // Cancel all event handlers on the current slide
  slides[currentSlide].unbind('mouseenter').unbind('mouseleave').unbind('touchstart');

  // Stop any fades on the caption and hide it
  $('#caption').stop().clearQueue().hide();

  // Slide the whole gallery right so that the previous slide is now centred
  var offset = slideWidths[currentSlide]/2 + slideHorizMargin*2 + slideWidths[currentSlide-1]/2;
  $('#gallery').animate( { left: '+=' + offset } );

  // Fade the old slide to backgroundSlideOpacity, and the new slide to currentSlideOpacity
  slides[currentSlide].animate( { opacity: backgroundSlideOpacity } );
  slides[currentSlide-1].animate( { opacity: currentSlideOpacity } );

  // Update the current slide index
  currentSlide--;

  // Update the shown/hidden states of left/right buttons as appropriate
  setButtonStates();

  // Set the caption to the new current slide's alt text,
  // and attach the hover events to the new slide
  $('#caption').html( slides[currentSlide].attr('alt') );
  addSlideHover();
}


// Move one slide to the right by sliding the gallery right-to-left

function moveRight() {

  // Don't move if this is the final slide, or if we don't yet have a width for the next slide
  if ( currentSlide == totalSlides - 1 ) return;
  if ( slideWidths[currentSlide+1] == undefined ) return;

  // Cancel all event handlers on the current slide
  slides[currentSlide].unbind('mouseenter').unbind('mouseleave').unbind('touchstart');

  // Stop any fades on the caption and hide it
  $('#caption').stop().clearQueue().hide();

  // Slide the whole gallery left so that the next slide is now centred
  var offset = slideWidths[currentSlide]/2 + slideHorizMargin*2 + slideWidths[currentSlide+1]/2;
  $('#gallery').animate( { left: '-=' + offset } );

  // Fade the old slide to backgroundSlideOpacity, and the new slide to currentSlideOpacity
  slides[currentSlide].animate( { opacity: backgroundSlideOpacity } );
  slides[currentSlide+1].animate( { opacity: currentSlideOpacity } );

  // Update the current slide index
  currentSlide++

  // Update the shown/hidden states of left/right buttons as appropriate
  setButtonStates();

  // Set the caption to the new current slide's alt text,
  // and attach the hover events to the new slide
  $('#caption').html( slides[currentSlide].attr('alt') );
  addSlideHover();
}


// Centre the current slide horizontally in the viewport

function centreCurrentSlide() {

  // Work out how far the left edge of the slide is from the
  // left hand edge of the gallery div

  var offsetFromGalleryStart = 0;

  for ( var i=0; i<currentSlide; i++ ) {
    offsetFromGalleryStart += slideWidths[i] + slideHorizMargin*2;
  }

  // Find the horizontal centre of the browser window
  var windowCentre = $(window).width() / 2;

  // Compute the left position of the slide based on the window centre and slide width
  var slideLeftPos = windowCentre - ( slideWidths[currentSlide] / 2 );

  // Compute the offset for the gallery div based on the slide position and
  // the slide offset from the gallery start. Also allow for the
  // horizontal margin on the left side of the slide.
  var offset = slideLeftPos - offsetFromGalleryStart - slideHorizMargin;

  // Move the gallery div to the new offset
  $('#gallery').css( 'left', offset );
}


// Show or hide the left and right buttons depending on the current slide:
// 1. If we're showing the first slide, hide the left button
// 2. If we're showing the last slide, hide the right button

function setButtonStates() {

  if ( currentSlide == 0 ) {
    $('#leftButton').hide();
  } else {
    $('#leftButton').show();
  }

  if ( currentSlide == totalSlides - 1 ) {
    $('#rightButton').hide();
  } else {
    $('#rightButton').show();
  }

}

// Attach mouseenter and mouseleave event handlers to the current slide to fade the caption in and out
// However, if the device supports touch events then fade the caption in/out when the slide is touched

function addSlideHover() {

  if ( 'ontouchstart' in document.documentElement ) {
    slides[currentSlide].bind( 'touchstart', function() {
      if ( $('#caption').is(':visible') ) {
        $('#caption').stop().clearQueue().fadeOut( captionSpeed );
      } else {
        $('#caption').stop().clearQueue().fadeTo( captionSpeed, captionOpacity );
      }
    } );
  } else {
    slides[currentSlide].hover(
      function() { $('#caption').stop().fadeTo( captionSpeed, captionOpacity ) },
      function() { $('#caption').stop().fadeTo( captionSpeed, 0 ) }
    );
  }
}


// Functions to pulse the loading message

function fadeInLoadingMessage() {
  $('#loading').animate( { opacity: loadingMessageMaxOpacity }, loadingMessageSpeed, 'swing', fadeOutLoadingMessage );
}

function fadeOutLoadingMessage(){
  $('#loading').animate( { opacity: loadingMessageMinOpacity }, loadingMessageSpeed, 'swing', fadeInLoadingMessage );
}


</script>

</head>

<body>

  <button id="leftButton" onclick='moveLeft()'>&lt;</button>
  <button id="rightButton" onclick='moveRight()'>&gt;</button>
  <div id="galleryContainer">
    <div id="gallery">
     <img  src="slides/uno.jpg" alt="Elegante perro ordinario" />
     <img  src="slides/dos.jpg" alt="San bernardo" />	
     <img  src="slides/tres.jpg" alt="cooker" />
     <img  src="slides/pastor.jpg" alt="Pastor Aleman " />
     <img  src="slides/golden.jpg" alt="Golden" />
	 <img  src="slides/shar.jpg" alt="Shar-Pei" /> 
     <img  src="slides/chihu.jpg" alt="Chihuahua" />	
     <img  src="slides/chow.jpg" alt="Chow Chow" />
     <img  src="slides/rot.jpg" alt="Rottweiler" />
     <img  src="slides/beagle.jpg" alt="Beagle" />			 
    </div>
    <div id="caption">Captura de Imagen</div>
    <div id="loading">Porfavor Espere...</div>
  </div>

  <div id="info">
    <h1>i</h1>
    <h2>Para poder visualizar la realidad usted necesitara Descargar la siguiente apk desde la play store </h2>
      <p></p>
       <p></p>
    <a href="http://layar.it/layarglass-101">Descargar Aqui</a>
  </div>

</body>
</html>

