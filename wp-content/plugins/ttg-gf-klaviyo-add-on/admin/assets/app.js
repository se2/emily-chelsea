(function ($) {
	const validateEmail = (email) => {
		return email.match(
			/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
		);
	};
	function validation(values) {
		var erros = [];
		for (let index = 0; index < values.length; index++) {
			var field = values[index];
			switch (field.name) {
				case "list-name": {
					if (!field.value) {
						erros.push({
							name: field.name,
							message: "List Name is required",
						});
					}
					break;
				}
				case "contact-email": {
					if (field.value && validateEmail(field.value)) {
						erros.push({
							name: field.name,
							message: "Contact Emmail is invalid format",
						});
					}
					break;
				}
			}
		}
		return erros;
	}
	function renderError(errors) {
		$("#klaviyo-list-form").find(".error").remove();
		if (errors.length) {
			for (let index = 0; index < errors.length; index++) {
				var error = errors[index];
				var html = '<span class="error">' + error.message + "</span>";
				var $target = $("#klaviyo-list-form").find(
					'[name="' + error.name + '"]',
				);
				if ($target.length) {
					$(html).insertAfter($target);
				}
			}
		}
	}

	$("#add-klaviyo-list, #close-klaviyo-list-form").on("click", function () {
		$("#klaviyo-list-form-wrapper").toggleClass("active");
	});

	$("#klaviyo-list-form").on("submit", function (e) {
		e.preventDefault();
		var $form = $(this);
		var errors = validation($(this).serializeArray());
		renderError(errors);
		$form.addClass("loading");
		if (!errors.length) {
			$.ajax({
				url: ajaxObject.ajaxUrl,
				data: $(this).serialize(),
				dataType: "json",
				success: function (res) {
					if (res) {
						$("#table-klaviyo-list")
							.find("tbody")
							.prepend(
								`<tr><td>${res.data.list_id}</td><td>${$(
									"#klaviyo-list-form [name='list-name']",
								).val()}</td></tr>`,
							);
						$("#klaviyo-list-form-wrapper").removeClass("active");
					} else {
						$form.append(
							'<span class="error">Can\'t create list</span>',
						);
					}
					$form.removeClass("loading");
				},
				error: function () {
					$form.removeClass("loading");
					$form.append(
						'<span class="error">Can\'t create list</span>',
					);
				},
			});
		}
	});
})(jQuery);
