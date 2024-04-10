import { isInViewport, addScript } from "./base";

(function ($) {
	const STATE = {
		ENDED: 0,
		PLAYING: 1,
		UNSTARTED: -1,
		PAUSE: 2,
	};
	function vimeo(elementId, vimeoId, options = {}) {
		const { autoPlay = false, onStateChange, ...otherOptions } = options;
		const autoPlayOptions = autoPlay
			? { autoplay: true, muted: true, loop: true, background: 1 }
			: {};
		const promise = new Promise((resolve, reject) => {
			if (typeof Vimeo === "undefined") {
				reject(null);
			}

			const player = new Vimeo.Player(elementId, {
				...otherOptions,
				...autoPlayOptions,
				id: vimeoId,
			});

			if (onStateChange) {
				player.on("ended", () => onStateChange(STATE.ENDED));
				player.on("playing", () => onStateChange(STATE.PLAYING));
				player.on("pause", () => onStateChange(STATE.PAUSE));
			}

			player.on("loaded", () => {
				resolve({
					play: () => player.play(),
					pause: () => player.pause(),
					paused: () => player.getPaused(),
				});
			});
		});

		return promise;
	}

	function youtube(elmentID, youtubeID, options = {}) {
		const { autoPlay = false, onStateChange } = options;
		const autoPlayOptions = autoPlay
			? { autoplay: 1, mute: 1, loop: 1 }
			: {};
		const promise = new Promise((resolve, reject) => {
			window.YT.ready(() => {
				try {
					const layer = new YT.Player(elmentID, {
						videoId: youtubeID,
						playerVars: {
							controls: 0, // Show pause/play buttons in player
							showinfo: 1, // Hide the video title
							modestbranding: 1, // Hide the Youtube Logo
							fs: 0, // Hide the full screen button
							cc_load_policy: 0, // Hide closed captions
							iv_load_policy: 3, // Hide the Video Annotations
							autohide: 1, // Hide video controls when playing
							...autoPlayOptions,
						},
						events: {
							onReady: (event) => {
								resolve({
									play: () => event.target.playVideo(),
									pause: () => event.target.pauseVideo(),
									paused: () => {
										const state =
											event.target.getPlayerState();
										return (
											state === 2 ||
											state === 0 ||
											state === 5
										);
									},
								});
							},
							onStateChange: ({ data }) => {
								switch (data) {
									case STATE.ENDED: {
										onStateChange(STATE.ENDED);
										break;
									}
									case STATE.PLAYING: {
										onStateChange(STATE.PLAYING);
										break;
									}
									case STATE.PAUSE: {
										onStateChange(STATE.PAUSE);
										break;
									}
								}
							},
						},
					});
				} catch (error) {
					reject(null);
				}
			});
		});

		return promise;
	}

	function video(id, options = {}) {
		const { onStateChange, autoPlay } = options;
		const promise = new Promise((resolve, reject) => {
			const myVideo = document.getElementById(id);
			console.log("video", myVideo);
			if (!myVideo) {
				reject(null);
			}

			const source = myVideo.querySelector("source");
			const src = source.getAttribute("data-src");
			source.setAttribute("src", src);

			myVideo.load();

			if (autoPlay) {
				myVideo.video = true;
				myVideo.muted = true;
				myVideo.loop = true;
				myVideo.load();
				myVideo.play();
			}

			if (onStateChange) {
				myVideo.addEventListener("ended", () => {
					onStateChange(STATE.ENDED);
				});
				myVideo.addEventListener("playing", () => {
					onStateChange(STATE.PLAYING);
				});
			}

			resolve({
				play: () => {
					console.log("play");
					myVideo.play();
				},
				pause: () => myVideo.pause(),
				paused: () => myVideo.paused,
			});
		});

		return promise;
	}

	function loadVideos() {
		$(".ttg-media").each(function (index, el) {
			const $that = $(this);
			const $media = $(el).find(".ttg-media__video");
			const $center = $that.find(".ttg-media__center");
			const $playButton = $(el).find(".ttg-media__play");
			const type = $media.attr("data-type");
			const autoPlay = $media.attr("data-autoplay");

			const elmentID = $media.attr("id");
			const videoID = $media.attr("data-id");

			const onStateChange = (s) => {
				if (!autoPlay) {
					if (s === STATE.ENDED) $(el).removeClass("is-playing");
					if (s === STATE.PAUSE) $(el).removeClass("is-playing");
				}
			};

			async function init(player) {
				$that.removeClass("loading");
				$that.addClass("loaded");
				$playButton.removeClass("disabled");

				async function play() {
					let isPaused = false;

					if (typeof player.paused().then !== "undefined") {
						isPaused = await player.paused();
					} else {
						isPaused = player.paused();
					}

					if (player) {
						if (isPaused) {
							player.play();
							$(el).addClass("is-playing");
						} else {
							player.pause();
							$(el).removeClass("is-playing");
						}
					}
				}

				$playButton.on("click", function () {
					play();
				});

				$that.on("playVideo", () => {
					player.play();
					$(el).addClass("is-playing");
				});

				$that.on("pauseVideo", () => {
					player.pause();
					$(el).removeClass("is-playing");
				});
			}

			function loadVideo() {
				if (!$that.hasClass("loaded")) {
					if (autoPlay) {
						$(el).addClass("is-playing");
					}
					$that.addClass("loading");

					switch (type) {
						case "youtube": {
							addScript(
								"iframe-api-js",
								"https://www.youtube.com/iframe_api",
								() => {
									youtube(elmentID, videoID, {
										autoPlay,
										onStateChange,
									}).then(init);
								},
							);

							break;
						}
						case "vimeo": {
							addScript(
								"vimeo-api-js",
								"https://player.vimeo.com/api/player.js",
								() => {
									vimeo(elmentID, videoID, {
										autoPlay,
										onStateChange,
									}).then(init);
								},
							);

							break;
						}
						default: {
							const elmentID = $media.find("video").attr("id");
							console.log("elmentID", elmentID);
							video(elmentID, {
								autoPlay,
								onStateChange,
							}).then(init);
						}
					}
				}
			}

			function loadVideoOnDemand() {
				const isTrigger = isInViewport($center[0]);
				if (isTrigger) {
					loadVideo();
				}
			}

			$that.on("loadVideo", () => {
				loadVideo();
			});
			loadVideoOnDemand();

			jQuery(window).on("scroll", function () {
				loadVideoOnDemand();
			});
		});
	}

	$(window).on("load", function () {
		loadVideos();
	});
})(jQuery);
