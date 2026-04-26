(function ($) {
	const ajaxUrl = "/wp-admin/admin-ajax.php";

	const MODE = Object.freeze({
		START_WITH_SETTING: "START_WITH_SETTING",
		START_WITH_STONE: "START_WITH_STONE",
	});

	function setMode(params = {}) {
		const {
			success = () => {},
			error = () => {},
			mode = MODE.START_WITH_SETTING,
		} = params;
		jQuery.ajax({
			url: ajaxUrl + "?action=set_init_mode&initMode=" + mode,
			success: success,
			dataType: "json",
			error,
		});
	}

	function reset(params = {}) {
		const { success = () => {}, error = () => {} } = params;
		jQuery.ajax({
			url: ajaxUrl + "?action=reset",
			success: success,
			dataType: "json",
			error,
		});
	}

	$("#start-with-a-stone").on("click", function (e) {
		e.preventDefault();
		const parent = $(this).closest(".build-ring-selection");
		parent.addClass("loading");
		reset({
			success: function (response) {
				setMode({
					mode: MODE.START_WITH_STONE,
					success: function (response) {
						window.location.href = "/build-ring/";
						parent.removeClass("loading");
					},
				});
			},
		});
	});

	$("#start-with-a-setting").on("click", function (e) {
		e.preventDefault();
		const parent = $(this).closest(".build-ring-selection");
		parent.addClass("loading");
		reset({
			success: function (response) {
				setMode({
					mode: MODE.START_WITH_SETTING,
					success: function (response) {
						window.location.href = "/build-ring/";
						parent.removeClass("loading");
					},
				});
			},
		});
	});
})(jQuery);
