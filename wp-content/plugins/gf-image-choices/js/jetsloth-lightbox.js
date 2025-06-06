/*
	Based on Simple Lightbox by André Rinas, www.andrerinas.de
	https://github.com/andreknieriem/simplelightbox
	Available for use under the MIT License
	1.15.1
*/
;( function( $, window, document, undefined )
{
	'use strict';

	window.jetslothLightboxDefaults = gform.applyFilters('gfic_lightbox_defaults', {
		sourceAttr: 'href',
		overlay: true,
		spinner: true,
		nav: true,
		navText: ['&lsaquo;', '&rsaquo;'],
		captions: true,
		captionDelay: 0,
		captionSelector: 'self',
		captionType: 'attr',
		captionsData: 'title',
		captionPosition: 'bottom',
		captionClass: '',
		close: true,
		closeText: '×',
		swipeClose: true,
		showCounter: true,
		fileExt: 'png|jpg|jpeg|gif|webp',
		animationSlide: true,
		animationSpeed: 250,
		preloading: true,
		enableKeyboard: true,
		enableSwipe: true,
		loop: true,
		rel: false,
		docClose: true,
		swipeTolerance: 50,
		className: 'jetsloth-lightbox',
		widthRatio: 0.8,
		heightRatio: 0.9,
		scaleImageToRatio: false,
		disableRightClick: false,
		disableScroll: true,
		alertError: true,
		alertErrorMessage: 'Image not found, next image will be loaded',
		additionalHtml: false,
		history: true,
		throttleInterval: 0
	});

$.fn.jetslothLightbox = function( options )
{

	var options = $.extend(window.jetslothLightboxDefaults, options);

	// global variables
	var touchDevice	= ( 'ontouchstart' in window ),
		pointerEnabled = window.navigator.pointerEnabled || window.navigator.msPointerEnabled,
		touched = function( event ){
			if( touchDevice ) return true;
			if( !pointerEnabled || typeof event === 'undefined' || typeof event.pointerType === 'undefined' ) return false;
			if( typeof event.MSPOINTER_TYPE_MOUSE !== 'undefined' ) {
				if( event.MSPOINTER_TYPE_MOUSE != event.pointerType ) return true;
			}
			else {
				if( event.pointerType != 'mouse' ) return true;
			}
			return false;
		},
		swipeDiff = 0,
		swipeYDiff = 0,
		curImg = $(),
		transPrefix = function(){
			var s = document.body || document.documentElement;
			s = s.style;
			if( s.WebkitTransition === '' ) return '-webkit-';
			if( s.MozTransition === '' ) return '-moz-';
			if( s.OTransition === '' ) return '-o-';
			if( s.transition === '' ) return '';
			return false;
		},
		opened = false,
		loaded = [],
		getRelated = function(rel, jqObj) {
			var $related = jqObj.filter(function () {
				return ($(this).attr('rel') === rel);
			});
			return $related;
		},
		objects = (options.rel && options.rel !== false) ? getRelated(options.rel, $(this)) : this,
		transPrefix = transPrefix(),
		globalScrollbarwidth = 0,
		canTransisions = (transPrefix !== false) ? true : false,
		supportsPushState = ('pushState' in history),
		historyhasChanged = false,
		historyUpdateTimeout,
		winLoc = window.location,
		getHash = function(){
			return winLoc.hash.substring(1);
		},
		initialHash = getHash(),
		updateHash = function(){
			var hash = getHash(),
			newHash = 'pid='+(index+1);
			var newURL = winLoc.href.split('#')[0] + '#' + newHash;

			if(supportsPushState){
				history[historyhasChanged ? 'replaceState' : 'pushState']('', document.title, newURL);
			}else {
				if(historyhasChanged) {
					winLoc.replace( newURL );
				} else {
					winLoc.hash = newHash;
				}
			}
			historyhasChanged = true;
		},
		resetHash = function() {
			if (supportsPushState) {
				history.pushState('', document.title, winLoc.pathname + winLoc.search );
			} else {
				winLoc.hash = '';
			}
			clearTimeout(historyUpdateTimeout);

		},
		updateURL = function(){
			if(!historyhasChanged) {
				updateHash(); // first time
			} else {
				historyUpdateTimeout = setTimeout(updateHash, 800);
			}
		},
		throttle = function(func, limit) {
			var inThrottle;
			return function() {
				var args = arguments;
				var context = this;
				if (!inThrottle) {
					func.apply(context, args);
					inThrottle = true;
					setTimeout(function() {
						return inThrottle = false;
					}, limit);
				}
			};
		},
		prefix = 'jetslothlb',
		overlay = $('<div>').addClass('jl-overlay'),
		closeBtn = $('<button>').addClass('jl-close').html(options.closeText),
		spinner = $('<div>').addClass('jl-spinner').html('<div></div>'),
		nav = $('<div>').addClass('jl-navigation').html('<button class="jl-prev">'+options.navText[0]+'</button><button class="jl-next">'+options.navText[1]+'</button>'),
		counter = $('<div>').addClass('jl-counter').html('<span class="jl-current"></span>/<span class="jl-total"></span>'),
		animating = false,
		index = 0,
		startIndex = 0,
		caption = $('<div>').addClass('jl-caption '+options.captionClass+' pos-'+options.captionPosition),
		image = $('<div>').addClass('jl-image'),
		wrapper = $('<div>').addClass('jl-wrapper').addClass(options.className),
		isValidLink = function( element ){
			if(!options.fileExt) return true;
			var filEext = /\.([0-9a-z]+)(?=[?#])|(\.)(?:[\w]+)$/gmi;
			var testExt = $( element ).attr( options.sourceAttr ).match(filEext);
			return testExt && $( element ).prop( 'tagName' ).toLowerCase() == 'a' && ( new RegExp( '\.(' + options.fileExt + ')$', 'i' ) ).test( testExt );
		},
		setup = function(){
			if(options.close) closeBtn.appendTo(wrapper);
			if(options.showCounter){
				if(objects.length > 1){
					counter.appendTo(wrapper);
					counter.find('.jl-total').text(objects.length);
				}
			}
			if(options.nav) nav.appendTo(wrapper);
			if(options.spinner) spinner.appendTo(wrapper);
		},
		openImage = function(elem){
			elem.trigger($.Event('show.jetslothlightbox'));
			if(options.disableScroll) globalScrollbarwidth = handleScrollbar('hide');
			wrapper.appendTo('body');
			image.appendTo(wrapper);
			if(options.overlay) overlay.appendTo($('body'));
			animating = true;
			index = objects.index(elem);
			curImg = $( '<img/>' )
				.hide()
				.attr('src', elem.attr(options.sourceAttr));
			if(loaded.indexOf(elem.attr(options.sourceAttr)) == -1){
				loaded.push(elem.attr(options.sourceAttr));
			}
			image.html('').attr('style','');
			curImg.appendTo(image);
			addEvents();
			overlay.fadeIn('fast');
			$('.jl-close').fadeIn('fast');
			spinner.show();
			nav.fadeIn('fast');
			$('.jl-wrapper .jl-counter .jl-current').text(index +1);
			counter.fadeIn('fast');
			adjustImage();
			if(options.preloading) preload();
			setTimeout( function(){ elem.trigger($.Event('shown.jetslothlightbox')); } ,options.animationSpeed);
		},
		adjustImage = function(dir){
			if(!curImg.length) return;
			var tmpImage 	 = new Image(),
			windowWidth	 = window.innerWidth * options.widthRatio,
			windowHeight = window.innerHeight * options.heightRatio;
			tmpImage.src	= curImg.attr( 'src' );

			$(tmpImage).on('error',function(ev){
				//no image was found
				objects.eq(index).trigger($.Event('error.jetslothlightbox'));
				animating = false;
				opened = true;
				spinner.hide();
				var dirDefinined = (dir == 1 || dir == -1);
				if(startIndex === index && dirDefinined){
					close();
					return;
				}
				if(options.alertError){
					alert(options.alertErrorMessage);
				}
				if(dirDefinined){
					loadImage(dir);
				} else {
					loadImage(1);
				}
				return;
			});


			tmpImage.onload = function() {
				if (typeof dir !== 'undefined') {
					objects.eq(index)
						.trigger($.Event('changed.jetslothlightbox'))
						.trigger($.Event( (dir===1?'nextDone':'prevDone')+'.jetslothlightbox'));
				}

				// history
				if(options.history){
					updateURL();
				}

				if(loaded.indexOf(curImg.attr( 'src' )) == -1){
					loaded.push(curImg.attr( 'src' ));
				}
				var imageWidth	 = tmpImage.width,
					imageHeight	 = tmpImage.height;

				if( options.scaleImageToRatio || imageWidth > windowWidth || imageHeight > windowHeight ){
					var ratio	 = imageWidth / imageHeight > windowWidth / windowHeight ? imageWidth / windowWidth : imageHeight / windowHeight;
					imageWidth	/= ratio;
					imageHeight	/= ratio;
				}

				$('.jl-image').css({
					'top':    ( window.innerHeight - imageHeight ) / 2 + 'px',
					'left':   ( window.innerWidth - imageWidth - globalScrollbarwidth)/ 2 + 'px'
				});
				spinner.hide();
				curImg
				.css({
					'width':  imageWidth + 'px',
					'height': imageHeight + 'px'
				})
				.fadeIn('fast');
				opened = true;
				var cSel = (options.captionSelector == 'self') ? objects.eq(index) : objects.eq(index).find(options.captionSelector);
				var captionText;
				if(options.captionType == 'data'){
					captionText = cSel.data(options.captionsData);
				} else if(options.captionType == 'text'){
					captionText = cSel.html();
				} else {
					captionText = cSel.prop(options.captionsData);
				}

				if(!options.loop) {
					if(index === 0){ $('.jl-prev').hide();}
					if(index >= objects.length -1) {$('.jl-next').hide();}
					if(index > 0){ $('.jl-prev').show(); }
					if(index < objects.length -1){ $('.jl-next').show(); }
				}

				if(objects.length == 1) $('.jl-prev, .jl-next').hide();

				if(dir == 1 || dir == -1){
					var css = { 'opacity': 1.0 };
					if( options.animationSlide ) {
						if( canTransisions ) {
							slide(0, 100 * dir + 'px');
							setTimeout( function(){ slide( options.animationSpeed / 1000, 0 + 'px'); }, 50 );
						}
						else {
							css.left = parseInt( $('.jl-image').css( 'left' ) ) + 100 * dir + 'px';
						}
					}

					$('.jl-image').animate( css, options.animationSpeed, function(){
						animating = false;
						setCaption(captionText, imageWidth);
					});
				} else {
					animating = false;
					setCaption(captionText, imageWidth);
				}
				if(options.additionalHtml && $('.jl-additional-html').length === 0){
					$('<div>').html(options.additionalHtml).addClass('jl-additional-html').appendTo($('.jl-image'));
				}
			};
		},
		setCaption = function(captiontext, imageWidth){
			if(captiontext !== '' && typeof captiontext !== "undefined" && options.captions){
				caption.html(captiontext).css({'width': imageWidth + 'px'}).hide().appendTo($('.jl-image')).delay(options.captionDelay).fadeIn('fast');
			}
		},
		slide = function(speed, pos){
			var styles = {};
				styles[transPrefix + 'transform'] = 'translateX(' + pos + ')';
				styles[transPrefix + 'transition'] = transPrefix + 'transform ' + speed + 's linear';
				$('.jl-image').css(styles);
		},
		addEvents = function(){
			// resize/responsive
			$( window ).on( 'resize.'+prefix, adjustImage );

			// close lightbox on close btn
			$( document ).on('click.'+prefix+ ' touchstart.'+prefix, '.jl-close', function(e){
				e.preventDefault();
				if(opened){ close();}
			});

			if(options.history){
				setTimeout(function() {
					$(window).on('hashchange.'+prefix,function(){
						if(opened){
							if(getHash() === initialHash) {
								close();
								return;
							}
						}
					});
				}, 40);
			}

			// nav-buttons
			nav.on('click.'+prefix, 'button', throttle(function(e){
				e.preventDefault();
				swipeDiff = 0;
				loadImage( $(this).hasClass('jl-next') ? 1 : -1 );
			}, options.throttleInterval));

			// touchcontrols
			var swipeStart	 = 0,
				swipeEnd	 = 0,
				swipeYStart = 0,
				swipeYEnd = 0,
				mousedown = false,
				imageLeft = 0;

			if ( options.enableSwipe ) {

				image
					.on( 'touchstart.'+prefix+' mousedown.'+prefix, function(e)
					{
						if(mousedown) return true;
						if( canTransisions ) imageLeft = parseInt( image.css( 'left' ) );
						mousedown = true;
						swipeDiff = 0;
						swipeYDiff = 0;
						swipeStart = e.originalEvent.pageX || e.originalEvent.touches[ 0 ].pageX;
						swipeYStart = e.originalEvent.pageY || e.originalEvent.touches[ 0 ].pageY;
						return false;
					})
					.on( 'touchmove.'+prefix+' mousemove.'+prefix+' pointermove MSPointerMove', function(e)
					{
						if(!mousedown) return true;
						e.preventDefault();
						swipeEnd = e.originalEvent.pageX || e.originalEvent.touches[ 0 ].pageX;
						swipeYEnd = e.originalEvent.pageY || e.originalEvent.touches[ 0 ].pageY;
						swipeDiff = swipeStart - swipeEnd;
						swipeYDiff = swipeYStart - swipeYEnd;
						if( options.animationSlide ) {
							if( canTransisions ) slide( 0, -swipeDiff + 'px' );
							else image.css( 'left', imageLeft - swipeDiff + 'px' );
						}
					})
					.on( 'touchend.'+prefix+' mouseup.'+prefix+' touchcancel.'+prefix+' mouseleave.'+prefix+' pointerup pointercancel MSPointerUp MSPointerCancel',function(e)
					{
						if(mousedown){
							mousedown = false;
							var possibleDir = true;
							if(!options.loop) {
								if(index === 0 && swipeDiff < 0){ possibleDir = false; }
								if(index >= objects.length -1 && swipeDiff > 0) { possibleDir = false; }
							}
							if( Math.abs( swipeDiff ) > options.swipeTolerance && possibleDir ) {
								loadImage( swipeDiff > 0 ? 1 : -1 );
							}
							else if( options.animationSlide )
							{
								if( canTransisions ) slide( options.animationSpeed / 1000, 0 + 'px' );
								else image.animate({ 'left': imageLeft + 'px' }, options.animationSpeed / 2 );
							}

							if( options.swipeClose && Math.abs(swipeYDiff) > 50 && Math.abs( swipeDiff ) < options.swipeTolerance) {
								close();
							}
						}
					});

			}

		},
		removeEvents = function(){
			nav.off('click', 'button');
			$( document ).off('click.'+prefix, '.jl-close');
			$( window ).off( 'resize.'+prefix);
			$( window ).off( 'hashchange.'+prefix);
		},
		preload = function(){
			var next = (index+1 < 0) ? objects.length -1: (index+1 >= objects.length -1) ? 0 : index+1,
				prev = (index-1 < 0) ? objects.length -1: (index-1 >= objects.length -1) ? 0 : index-1;
			$( '<img />' ).attr( 'src', objects.eq(next).attr( options.sourceAttr ) ).on('load', function(){
				if(loaded.indexOf($(this).attr('src')) == -1){
					loaded.push($(this).attr('src'));
				}
				objects.eq(index).trigger($.Event('nextImageLoaded.jetslothlightbox'));
			});
			$( '<img />' ).attr( 'src', objects.eq(prev).attr( options.sourceAttr ) ).on('load', function(){
				if(loaded.indexOf($(this).attr('src')) == -1){
					loaded.push($(this).attr('src'));
				}
				objects.eq(index).trigger($.Event('prevImageLoaded.jetslothlightbox'));
			});

		},
		loadImage = function(dir){
			objects.eq(index)
			.trigger($.Event('change.jetslothlightbox'))
			.trigger($.Event( (dir===1?'next':'prev')+'.jetslothlightbox'));

		var newIndex = index + dir;
			if(animating || (newIndex < 0 || newIndex >= objects.length) && options.loop === false ) return;
			index = (newIndex < 0) ? objects.length -1: (newIndex > objects.length -1) ? 0 : newIndex;
			$('.jl-wrapper .jl-counter .jl-current').text(index +1);
				var css = { 'opacity': 0 };
			if( options.animationSlide ) {
				if( canTransisions ) slide(options.animationSpeed / 1000, ( -100 * dir ) - swipeDiff + 'px');
				else css.left = parseInt( $('.jl-image').css( 'left' ) ) + -100 * dir + 'px';
			}

			$('.jl-image').animate( css, options.animationSpeed, function(){
				setTimeout( function(){
					// fadeout old image
					var elem = objects.eq(index);
					curImg
					.attr('src', elem.attr(options.sourceAttr));
					if(loaded.indexOf(elem.attr(options.sourceAttr)) == -1){
						spinner.show();
					}
					$('.jl-caption').remove();
					adjustImage(dir);
					if(options.preloading) preload();
				}, 100);
			});
		},
		close = function(){
			if(animating) return;
			var elem = objects.eq(index),
			triggered = false;

			elem.trigger($.Event('close.jetslothlightbox'));
			if(options.history){
				resetHash();
			}
			$('.jl-image img, .jl-overlay, .jl-close, .jl-navigation, .jl-image .jl-caption, .jl-counter').fadeOut('fast', function(){
				if(options.disableScroll) handleScrollbar('show');
				$('.jl-wrapper, .jl-overlay').remove();
				removeEvents();
				if(!triggered) elem.trigger($.Event('closed.jetslothlightbox'));
				triggered = true;
			});
			curImg = $();
			opened = false;
			animating = false;
		},
		handleScrollbar = function(type){
			var scrollbarWidth = 0;
			if(type == 'hide'){
				var fullWindowWidth = window.innerWidth;
				if (!fullWindowWidth) {
					var documentElementRect = document.documentElement.getBoundingClientRect();
					fullWindowWidth = documentElementRect.right - Math.abs(documentElementRect.left);
				}
				if(document.body.clientWidth < fullWindowWidth){
					var scrollDiv = document.createElement('div'),
					padding = parseInt($('body').css('padding-right'),10);
					scrollDiv.className = 'jl-scrollbar-measure';
					$('body').append(scrollDiv);
					scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
					$(document.body)[0].removeChild(scrollDiv);
					$('body').data('padding',padding);
					if(scrollbarWidth > 0){
						$('body').addClass('hidden-scroll').css({'padding-right':padding+scrollbarWidth});
					}
				}
			} else {
				$('body').removeClass('hidden-scroll').css({'padding-right':$('body').data('padding')});
			}
			return scrollbarWidth;
		};

	// events
	setup();

	// open lightbox
	objects.on( 'click.'+prefix, function( e ){
		if(isValidLink(this)){
			e.preventDefault();
			if(animating) return false;
			var elem = $(this);
			startIndex = objects.index(elem);
			openImage(elem);
		}
	});

	// close on click on doc
	$( document ).on('click.'+prefix+ ' touchstart.'+prefix, function(e){
		if(opened){
			if((options.docClose && $(e.target).closest('.jl-image').length === 0 && $(e.target).closest('.jl-navigation').length === 0)){
				close();
			}
		}
	});

	// disable rightclick
	if(options.disableRightClick){
		$( document ).on('contextmenu', '.jl-image img', function(e){
			return false;
		});
	}


	// keyboard-control
	if( options.enableKeyboard ){
		$( document ).on( 'keyup.'+prefix, throttle(function( e ){
			swipeDiff = 0;
			// keyboard control only if lightbox is open
			var key = e.keyCode;
			if(animating && key == 27) {
				curImg.attr('src', '');
				animating = false;
				close();
			}

			if(opened){
				e.preventDefault();
				if( key == 27 ) {
					close();
				}
				if( key == 37 || e.keyCode == 39 ) {
					loadImage( e.keyCode == 39 ? 1 : -1 );
				}
			}
		}, options.throttleInterval));
	}

	// Public methods
	this.open = function(elem){
		elem = elem || $(this[0]);
		startIndex = objects.index(elem);
		openImage(elem);
	};

	this.next = function(){
		loadImage( 1 );
	};

	this.prev = function(){
		loadImage( -1 );
	};

	this.close = function(){
		close();
	};

	this.destroy = function(){
		$( document ).off('click.'+prefix).off('keyup.'+prefix);
		close();
		$('.jl-overlay, .jl-wrapper').remove();
		this.off('click');
	};

	this.refresh = function(){
		this.destroy();
		$(this).jetslothLightbox(options);
	};

	return this;

};
})( jQuery, window, document );
