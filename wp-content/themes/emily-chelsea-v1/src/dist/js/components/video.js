/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./assets/js/components/video.js ***!
  \***************************************/
(function () {
  function onYouTubeIframeAPIReady(elmentID, youtubeID) {
    var timer = setInterval(function () {
      var _window;
      if ((_window = window) !== null && _window !== void 0 && (_window = _window.YT) !== null && _window !== void 0 && _window.ready) {
        window.YT.ready(function () {
          try {
            new YT.Player(elmentID, {
              videoId: youtubeID,
              playerVars: {
                autoplay: 1,
                // Auto-play the video on load
                controls: 0,
                // Show pause/play buttons in player
                showinfo: 1,
                // Hide the video title
                modestbranding: 1,
                // Hide the Youtube Logo
                loop: 1,
                // Run the video in a loop
                fs: 0,
                // Hide the full screen button
                cc_load_policy: 0,
                // Hide closed captions
                iv_load_policy: 3,
                // Hide the Video Annotations
                autohide: 1,
                // Hide video controls when playing
                playsinline: 1,
                //forbid fullscreen on ios
                playlist: youtubeID
              },
              events: {
                onReady: function onReady(e) {
                  e.target.mute();
                  e.target.playVideo();
                  //e.target?.h?.classList?.add("video-loaded");
                }
              }
            });
          } catch (error) {
            console.log(error);
          }
        });
        clearInterval(timer);
      }
    }, 500);
  }
  function loadVideos(items) {
    items.forEach(function (el) {
      var id = el.id;
      var isLoaded = el.classList.contains("video-loaded");
      if (!isLoaded) {
        var _el$classList;
        var youtubeID = el.getAttribute("data-id");
        onYouTubeIframeAPIReady(id, youtubeID);
        el === null || el === void 0 || (_el$classList = el.classList) === null || _el$classList === void 0 || _el$classList.add("video-loaded");
      }
    });
  }
  window.addEventListener("load", function () {
    var videos = document.querySelectorAll(".video-youtube");
    loadVideos(videos);
  });
  window.addEventListener("scroll", function () {
    var videos = document.querySelectorAll(".video-youtube");
    loadVideos(videos);
  }, false);
})();
/******/ })()
;
//# sourceMappingURL=video.js.map