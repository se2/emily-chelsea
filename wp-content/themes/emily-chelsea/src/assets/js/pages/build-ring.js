(function ($) {
	const PRODUCT_TYPES = Object.freeze({
		RING: "rings",
		STONE: "center-stones",
	});

	const MODE = Object.freeze({
		START_WITH_SETTING: "START_WITH_SETTING",
		START_WITH_STONE: "START_WITH_STONE",
	});

	const ajaxUrl = "/wp-admin/admin-ajax.php";

	function init() {
		jQuery(document).on(
			"click",
			".build-ring-confirm .remove-design",
			function (e) {
				e.preventDefault();
				const uuid = $(this).attr("data-uuid");
				removeDesign(uuid, () => {
					refresh();
				});
			},
		);

		jQuery(document).on(
			"click",
			".build-ring-mini-collection .remove-design",
			function (e) {
				e.preventDefault();
				const parent = $(this).closest(".build-ring-mini-collection");
				$(parent).addClass("loading");
				const uuid = $(this).attr("data-uuid");
				removeDesign(uuid, () => {
					getInitData();
					$(parent).removeClass("loading");
				});
			},
		);

		getInitData((data) => {
			const collections = data.collections || {};

			jQuery(".build-ring-confirm").each(function (index, value) {
				const uuid = $(this).attr("data-uuid");
				const form = $(this).find(".variations_form");
				const collection = collections[uuid] || null;
				if (!collection) {
					return;
				}
				const attrs = collection.attrs;
				if (attrs) {
					$.each(attrs, (key, value) => {
						$(form).find(`[name='${key}']`).val(value);
					});
					const variationId = collection.variation_id || 0;
					$(form).find(".variation_id").val(variationId);
				}
			});

			$(document)
				.find(".build-ring-confirm")
				.each(function (index, value) {
					const uuid = $(this).attr("data-uuid");
					const form = $(this).find(".variations_form");

					$(form)
						.find("[name^=attribute_pa]")
						.each(function (index, value) {
							const selectId = $(this).attr("id");
							const id = `custom-select-${selectId}_${uuid}`;
							customSelect(value, id);
						});
				});

			jQuery(document)
				.find("[name='attribute_pa_metal-type']")
				.on("change", function (el) {
					const container = $(this).closest(".build-ring-confirm");
					const uuid = container.attr("data-uuid");
					const form = $(this).closest(".variations_form");
					const val = $(this).find("option:selected").attr("value");
					const productId = $(form).data("product_id");
					const size = $(form).find("[name='attribute_pa_size']");
					const selectedSize = $(size)
						.find("option:selected")
						.attr("value");

					if ($(size).length) {
						jQuery.ajax({
							method: "POST",
							url: ajaxUrl,
							data: {
								action: "get_products_by_attr",
								parent_product_id: productId,
								meta_type: val,
							},
							dataType: "json",
							success: function (res) {
								const { options } = res;
								$(document)
									.find("custom-select-pa_size_" + uuid)
									.remove();
								$(size).html(options);
								$(size).val(selectedSize);
								customSelect(
									$(size),
									"custom-select-pa_size_" + uuid,
								);
							},
							error: (err) => {
								console.error("Error fetching products:", err);
							},
						});
					}
				});
		});

		getSubtotal({
			success: (res) => {
				$(".subtotal").html(res.data);
			},
		});
	}

	function processLoading() {
		return {
			start: () => {
				jQuery(".page-build-ring").addClass("loading");
			},
			end: () => {
				jQuery(".page-build-ring").removeClass("loading");
			},
		};
	}

	function setStep(step, callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=set_step&step=" + step,
			success: (res) => {
				if (res.isSuccess) {
					callback();
				}
			},
			dataType: "json",
		});
	}

	function removeDesign(uuid, callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=remove_design&uuid=" + uuid,
			success: (res) => {
				if (res.isSuccess) {
					callback();
				}
			},
			dataType: "json",
		});
	}

	function removeTrayItem(id, callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=remove_tray_item&id=" + id.toString(),
			success: (res) => {
				callback(res);
			},
			dataType: "json",
		});
	}

	function parseQSToObject(queryString) {
		const urlParams = new URLSearchParams(queryString);
		const params = {};
		urlParams.forEach((value, key) => {
			params[key] = value;
		});
		return params;
	}

	var refreshTimer = null;
	function refresh(onSuccess = () => {}) {
		const loading = processLoading();
		loading.start();
		refreshTimer = setTimeout(() => {
			jQuery.ajax({
				url: "/build-ring?step=" + new Date().getTime(),
				success: (res) => {
					const oldFilter = jQuery(document).find(".page-build-ring");
					const filter = jQuery(res).find(".page-build-ring");
					oldFilter.replaceWith(filter);
					onSuccess();
					jQuery(document)
						.find(".variations_form")
						.each(function () {
							jQuery(this).wc_variation_form();
						});
					init();

					if (jQuery(".products-filter").length) {
						refreshFWP();
					}
					loading.end();
				},
			});
		}, 200);
	}

	var timer = null;
	function refreshFWP() {
		if (FWP) {
			FWP.refresh();
		}
	}

	function redirect() {
		window.location.href = "/build-ring?step=" + new Date().getTime();
	}

	function toggleMode(params = {}) {
		const { success = () => {}, error = () => {} } = params;
		jQuery.ajax({
			url: ajaxUrl + "?action=toggle_mode",
			success: success,
			dataType: "json",
			error,
		});
	}

	function setMode(params = {}) {
		const {
			success = () => {},
			error = () => {},
			mode = MODE.START_WITH_SETTING,
		} = params;
		jQuery.ajax({
			url: ajaxUrl + "?action=set_mode&mode=" + mode,
			success: success,
			dataType: "json",
			error,
		});
	}

	function parseData(data) {
		$("#build-ring-tray").html(data.trayItems);
		$("#build-ring-tray-extra-wrapper").html(data.trayExtra);
		$("#build-ring-mini-collection").replaceWith(data.miniCollection);
	}

	function refreshCollectionRing(uuid) {
		jQuery.ajax({
			url: ajaxUrl + "?action=refresh_collection_ring&uuid=" + uuid,
			success: (res) => {
				if (res.isSuccess) {
					const item = jQuery(document).find(
						'.build-ring-confirm[data-uuid="' + uuid + '"]',
					);
					jQuery(item)
						.find(".build-ring-collection__item__ring")
						.html(res.data);
				}
			},
			dataType: "json",
		});
	}

	function getInitData(callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=get_init_data",
			success: (data) => {
				callback(data);
				parseData(data);
			},
			dataType: "json",
		});
	}

	function getSubtotal(params = {}) {
		const {
			success = () => {},
			error = () => {},
			product = "",
			stone = "",
		} = params;
		jQuery.ajax({
			url: ajaxUrl + "?action=get_subtotal",
			success: success,
			dataType: "json",
			error,
		});
	}

	async function productIsValid(productId) {
		return new Promise((resolve, reject) => {
			jQuery.ajax({
				url:
					ajaxUrl +
					"?action=product_is_valid&product_id=" +
					productId,
				success: (res) => {
					if (res.isSuccess) {
						resolve(res.data);
					} else {
						reject(res.data);
					}
				},
				dataType: "json",
				error: (err) => {
					reject(err);
				},
			});
		});
	}

	function getErrorMessage(button) {
		if ($(button).hasClass("disabled")) {
			if ($(button).hasClass("wc-variation-is-unavailable")) {
				console.log("button22", $(button).hasClass("disabled"), button);
				return wc_add_to_cart_variation_params.i18n_unavailable_text;
			} else if ($(button).hasClass("wc-variation-selection-needed")) {
				return wc_add_to_cart_variation_params.i18n_make_a_selection_text;
			}
		}

		return "";
	}

	function selectProduct(
		productId,
		type,
		callback = () => {},
		extraParams = {},
	) {
		const ajaxUrl =
			typeof jsData !== "undefined" && jsData.ajaxUrl
				? jsData.ajaxUrl
				: "/wp-admin/admin-ajax.php";

		$.ajax({
			url: ajaxUrl,
			type: "POST",
			data: {
				action: "select_product",
				product_id: productId,
				type: type,
				...extraParams,
			},
			dataType: "json",
			success: function (response) {
				callback(response);
			},
			error: function (err) {
				console.error("Error selecting product", err);
			},
		});
	}

	function updateProductAttributes(formData, callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=update_product_attrs&" + formData,
			success: (data) => {
				callback(data);
			},
			dataType: "json",
		});
	}

	$(document).on("click", "#build-ring-toggle-mode", function () {
		toggleMode({
			success: () => {
				refresh();
			},
		});
	});

	$(document).on("click", "#build-ring-mode--stone", function () {
		setMode({
			mode: MODE.START_WITH_STONE,
			success: () => {
				refresh();
			},
		});
	});

	$(document).on("click", "#build-ring-mode--setting", function () {
		setMode({
			mode: MODE.START_WITH_SETTING,
			success: () => {
				refresh();
			},
		});
	});

	function initDragDrop() {
		if (!$.fn.draggable || !$.fn.droppable) {
			console.warn("jQuery UI Draggable/Droppable not loaded");
			return;
		}

		if ($(document).find(".center-stones-list").length) {
			$(document).find(".center-stones-list .product").draggable({
				revert: "invalid",
				helper: "clone",
				cursor: "move",
				zIndex: 9999,
				appendTo: "body",
			});

			$(document)
				.find("#build-ring-tray")
				.droppable({
					accept: ".center-stones-list .product",
					hoverClass: "ui-state-hover",
					drop: function (event, ui) {
						handleDrop(ui.draggable, PRODUCT_TYPES.STONE, $(this));
					},
					tolerance: "pointer",
				});
		}

		if ($(document).find(".rings-list").length) {
			$(document).find(".rings-list .product").draggable({
				revert: "invalid",
				helper: "clone",
				cursor: "move",
				zIndex: 9999,
				appendTo: "body",
			});
			$(document)
				.find("#build-ring-tray")
				.droppable({
					accept: ".rings-list .product",
					hoverClass: "ui-state-hover",
					drop: function (event, ui) {
						handleDrop(ui.draggable, PRODUCT_TYPES.RING, $(this));
					},
					tolerance: "pointer",
				});
		}
	}

	function handleDrop($item, type, $target) {
		let productId = 0;
		// Try to find post-ID class
		const classes = $item.attr("class").split(/\s+/);
		for (let cls of classes) {
			if (cls.match(/^post-\d+$/)) {
				productId = cls.replace("post-", "");
				break;
			}
		}

		if (!productId) {
			// Try finding add to cart button which usually has data-product_id
			productId = $item.find(".add_to_cart_button").data("product_id");
		}

		if (!productId) {
			console.error("Product ID not found for dragged item");
			return;
		}

		selectProduct(productId, type, (res) => {
			const message = res?.message || "";
			// Visual feedback
			if (res.isSuccess) {
				const $img = $item.find("img").clone();
				const $product = $(
					"<div class='build-ring-tray__product'></div>",
				)
					.data("product-id", productId)
					.append($img);
				const tray =
					type == PRODUCT_TYPES.RING
						? "#build-ring-tray__ring"
						: "#build-ring-tray__stone";
				$target.find(tray).find(".build-ring-tray__empty").remove();
				$target.find(tray).append($product);
				refresh(() => {
					parseData(res.data);
				});
			} else {
				window.alert(message);
			}
		});
	}

	$(document).on("facetwp-loaded", function () {
		initDragDrop();
	});

	$(".variations_form").on("submit", function (e) {
		e.preventDefault();
	});

	jQuery(".product-detail-build-ring .variations_form").on(
		"submit",
		function (e) {
			e.preventDefault();
			let formData = $(this).serialize();
			const formObj = parseQSToObject(formData);
			const productType = formObj.product_type;

			// remove add_to_cart name from formData
			formData = formData.replace(/add-to-cart=\d+/, "");
			delete formObj["add-to-cart"];

			selectProduct(
				formObj.product_id,
				productType,
				(res) => {
					if (res.isSuccess) {
						updateProductAttributes(formData, (res) => {
							if (res.isSuccess) {
								redirect();
							}
						});
					}
				},
				{
					...formObj,
					stone_option: formObj.stone_option,
				},
			);
		},
	);

	jQuery(".product-detail-build-ring .cart:not(.variations_form)").on(
		"submit",
		function (e) {
			e.preventDefault();
			const formData = $(this).serialize();
			const formObj = parseQSToObject(formData);
			const productType = formObj.product_type;
			const value = $(this).find(".single_add_to_cart_button").val();
			selectProduct(value, productType, (res) => {
				if (res.isSuccess) {
					redirect();
				} else {
					window.alert(res.message || "");
				}
			});
		},
	);

	jQuery(document).on("click", "#start-new-design", function (e) {
		e.preventDefault();
		setStep(1, () => {
			refresh();
		});
	});

	jQuery(document).on("click", "#continue-to-checkout", function (e) {
		e.preventDefault();
		const collections = jQuery(document).find(".build-ring-confirm");
		let isValid = true;
		jQuery.each(collections, function () {
			const form = jQuery(this).find(".variations_form");
			const variationId = parseInt(
				jQuery(form).find(".variation_id").val(),
			);
			const error = jQuery(form).find(".product-error-message").html();
			if (!variationId || error) {
				isValid = false;
			}
		});
		if (!isValid) {
			window.alert(
				"Please select select Metal and Ring Size before continuing",
			);
			return;
		}
		window.location.href = "/checkout/";
	});

	jQuery(document).on(
		"change",
		"#build-ring-tray-extra [name='stone_option']",
		function (e) {
			e.preventDefault();
			const value = $(this).val();
			console.log("value", value);
			if (value !== "custom_stone" && value !== "") {
				selectProduct(value, PRODUCT_TYPES.STONE, (res) => {
					if (res.isSuccess) {
						refresh();
					}
				});
			}
		},
	);

	jQuery(document).on("click", ".set-step", function (e) {
		e.preventDefault();
		const step = $(this).attr("data-step");
		setStep(step, () => {
			refresh();
		});
	});

	jQuery(document).on(
		"change",
		".build-ring-confirm .variations_form .variation_id",
		function (el) {
			const container = $(this).closest(".build-ring-confirm");
			const uuid = $(container).attr("data-uuid");
			const form = $(this).closest(".variations_form");
			const subtotalEl = $(document).find(".subtotal");
			const productId = parseInt($(this).val());
			const formData = $(form).serialize();
			const formObj = parseQSToObject(formData);
			const errorEl = $(container).find(".product-error-message");

			if (!productId) {
				return;
			}

			delete formObj["add-to-cart"];

			selectProduct(
				productId,
				PRODUCT_TYPES.RING,
				(res) => {
					const message = res.message || "";
					if (message) {
						errorEl.html(message);
					} else {
						errorEl.html("");
					}

					getSubtotal({
						success: (res) => {
							if (res.isSuccess) {
								subtotalEl.html(res.data);
							}
						},
					});
					refreshCollectionRing(uuid);
				},
				{
					...formObj,
				},
			);
		},
	);

	jQuery(document).on("click", ".build-ring-tray__item-remove", function (e) {
		e.preventDefault();
		const $item = $(this).closest(".build-ring-tray__item");
		$item.addClass("loading");
		const id = $(this).data("id");
		removeTrayItem(id, (res) => {
			if (res.isSuccess) {
				getInitData();
			} else {
				window.alert(res.message || "");
			}
			$item.removeClass("loading");
		});
	});

	init();
})(jQuery);
