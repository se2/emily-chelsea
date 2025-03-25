/*
 * Source: http://cutroni.com/blog/2012/02/21/advanced-content-tracking-with-google-analytics-part-1/
 * Original authors:
 *  - Nick Mihailovski
 *  - Thomas Baekdal
 *  - Avinash Kaushik
 *  - Joost de Valk
 *  - Eivind Savio
 *  - Justin Cutroni
 *
 * Google Tag Manager dataLayer events added by:
 *   Thomas Geiger
 *   gtm4wp.com
 */

if ( "undefined" == typeof console ) {
	window.console = {
		log: function () {}
	};
}

(function(  ) {
	// Debug flag
	const debugMode      = gtm4wp_scrollerscript_debugmode;

	// Default time delay before checking location
	const callBackTime   = gtm4wp_scrollerscript_callbacktime;

	// # px before tracking a reader
	const readerLocation = gtm4wp_scrollerscript_readerlocation;

	// Set some flags for tracking & execution
	let timer       = 0;
	let scroller    = false;
	let endContent  = false;
	let didComplete = false;

	// Set some time variables to calculate reading time
	const startTime = new Date();
	const beginning = startTime.getTime();
	let totalTime = 0;

	// Track the aticle load
	if ( !debugMode ) {
		window[ gtm4wp_datalayer_name ].push({
			'event': 'gtm4wp.reading.articleLoaded'
		});
	} else {
		console.log( 'Article loaded' );
	}

	// Check the location and track user
	function trackLocation() {
		//const bottom = $( window ).height() + $( window ).scrollTop();
		const bottom = window.innerHeight + window.scrollY;
		//const height = $( document ).height();
		const height = document.body.offsetHeight
		let scrollStart = 0;

		// If user starts to scroll send an event
		if ( bottom > readerLocation && !scroller ) {
			const currentTime = new Date();
			scrollStart = currentTime.getTime();
			const timeToScroll = Math.round( ( scrollStart - beginning ) / 1000 );

			if ( !debugMode ) {
				window[ gtm4wp_datalayer_name ].push({
					'event': 'gtm4wp.reading.startReading',
					'timeToScroll': timeToScroll
				});
			} else {
				console.log( 'Started reading ' + timeToScroll );
			}
			scroller = true;
		}

		// If user has hit the bottom of the content send an event
		//if ( bottom >= $( '#' + gtm4wp_scrollerscript_contentelementid ).scrollTop() + $( '#' + gtm4wp_scrollerscript_contentelementid ).innerHeight() && !endContent ) {
		const scroll_top = document.querySelector('#' + gtm4wp_scrollerscript_contentelementid)?.scrollTop || 0;
		const client_height = document.querySelector('#' + gtm4wp_scrollerscript_contentelementid )?.clientHeight || 0;
		if ( bottom >= scroll_top + client_height && !endContent ) {
			
			const currentTime = new Date();
			const contentScrollEnd = currentTime.getTime();
			const timeToContentEnd = Math.round( ( contentScrollEnd - scrollStart ) / 1000 );

			if ( !debugMode ) {
				window[ gtm4wp_datalayer_name ].push({
					'event': 'gtm4wp.reading.contentBottom',
					'timeToScroll': timeToContentEnd
				});
			} else {
				console.log( 'End content section ' + timeToContentEnd );
			}

			endContent = true;
		}

		// If user has hit the bottom of page send an event
		if ( bottom >= height && !didComplete ) {
			const currentTime = new Date();
			const end = currentTime.getTime();
			totalTime = Math.round( ( end - scrollStart ) / 1000 );

			if ( !debugMode ) {
				if ( totalTime < gtm4wp_scrollerscript_scannertime ) {
					window[ gtm4wp_datalayer_name ].push({
						'event': 'gtm4wp.reading.readerType',
						'readerType': 'scanner'
					});
				} else {
					window[ gtm4wp_datalayer_name ].push({
						'event': 'gtm4wp.reading.readerType',
						'readerType': 'reader'
					});
				}

				window[ gtm4wp_datalayer_name ].push({
					'event': 'gtm4wp.reading.pagebottom',
					'timeToScroll': totalTime
				});
			} else {
				if ( totalTime < gtm4wp_scrollerscript_scannertime ) {
					console.log( 'The visitor seems to be a "scanner"' );
				} else {
					console.log( 'The visitor seems to be a "reader"' );
				}

				console.log( 'Bottom of page ' + totalTime );
			}

			didComplete = true;
		}
	}
	
	// Track the scrolling and track location
	//$( window ).scroll(function() {
	document.addEventListener("scroll", function(event){
		if ( timer ) {
			clearTimeout( timer );
		}

		// Use a buffer so we don't call trackLocation too often.
		timer = setTimeout( trackLocation, callBackTime );
	});
})(window, document);