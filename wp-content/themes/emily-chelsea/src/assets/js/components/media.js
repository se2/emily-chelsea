(function () {
	function vimeo(elementId, vimeoId, options = {}) {
		const promise = new Promise((resolve, reject) => {
			if (typeof Vimeo === "undefined") {
				reject(null);
			}

			const player = new Vimeo.Player(elementId, {
				...options,
				id: vimeoId,
			});
			resolve({
				play: () => player.play(),
				pause: () => player.pause(),
				paused: () => player.getPaused(),
			});
		});

		return promise;
	}

	function youtube(elmentID, youtubeID) {
		const promise = new Promise((resolve, reject) => {
			let interval = setInterval(function () {
				if (window?.YT?.ready) {
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
								},
								events: {
									onReady: (event) => {
										resolve({
											play: () =>
												event.target.playVideo(),
											pause: () =>
												event.target.pauseVideo(),
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
								},
							});
						} catch (error) {
							reject(null);
						}
					});
					clearInterval(interval);
				}
			}, 500);
		});

		return promise;
	}

	function video(id) {
		const promise = new Promise((resolve, reject) => {
			const myVideo = document.getElementById(id);
			if (!myVideo) {
				reject(null);
			}

			resolve({
				play: () => myVideo.play(),
				pause: () => myVideo.pause(),
				paused: () => myVideo.paused,
			});
		});

		return promise;
	}

	$(".ttg-media").each(function (index, el) {
		const $media = $(el).find(".ttg-media__video");
		const $playButton = $(el).find(".ttg-media__play");
		const type = $media.attr("data-type");

		const elmentID = $media.attr("id");
		const videoID = $media.attr("data-id");

		async function init(player) {
			$playButton.removeClass("disabled");
			$playButton.on("click", async function () {
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
			});
		}

		switch (type) {
			case "youtube": {
				youtube(elmentID, videoID).then(init);
				break;
			}
			case "vimeo": {
				vimeo(elmentID, videoID).then(init);
				break;
			}
			default: {
				const elmentID = $media.find("video").attr("id");
				video(elmentID).then(init);
			}
		}
	});
})();
