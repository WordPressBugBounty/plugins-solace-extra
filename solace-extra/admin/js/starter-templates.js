(function( $ ) {
	'use strict';

	// Utility: Get URL parameter by name
	function getParameterByName(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
		var results = regex.exec(url);
		if (!results) return '';
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

	// Utility: Get URL parameter
	function getUrlParameter(name) {
		name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
		var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
		var results = regex.exec(location.search);
		return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
	}

	// Get demo type from URL
	let demoType = getParameterByName('type');
	demoType = demoType.toLowerCase().replace(/\s+/g, '-');

	// Get the 'type' parameter from the URL
	var getSolaceType = getUrlParameter('type');
	var listType = ['elementor', 'gutenberg'];
	var step2 = pluginUrl.admin_url + 'admin.php?page=dashboard-video';

	// Check if 'type' is empty or not in the list of valid types
	if (getSolaceType === '' || listType.indexOf(getSolaceType) === -1) {
		window.location.href = step2;
		return;
	}

	// Clean up localStorage and cookies
	var currentDate = new Date();
	currentDate.setTime(currentDate.getTime() - 24 * 60 * 60 * 1000);
	localStorage.removeItem('solace_step5_font');
	localStorage.removeItem('solace_step5_color');
	localStorage.removeItem('solace_step5_logo');
	document.cookie = "solace_step5_font=; expires=" + currentDate.toUTCString() + "; path=/";
	document.cookie = "solace_step5_color=; expires=" + currentDate.toUTCString() + "; path=/";
	document.cookie = "solace_step5_logoid=; expires=" + currentDate.toUTCString() + "; path=/";

	// Get filter license value
	function getFilterLicense() {
		var $filterLicense = $('#filter_license');
		return $filterLicense.length ? $filterLicense.val() || 'all' : 'all';
	}

	// Get current keyword from search input
	function getCurrentKeyword() {
		var keyword = $('section.start-templates .content-main aside .mycontainer .box-search input').val().trim();
		return keyword.length === 0 ? 'empty' : keyword;
	}

	// Get checked categories
	function getCheckedCategories() {
		var checkedValues = [];
		$('section.start-templates aside .box-checkbox input[type="checkbox"]:checked').each(function() {
			checkedValues.push($(this).val());
		});
		return checkedValues.length > 0 ? checkedValues.join(', ') : 'show-all-demos';
	}

	// Common AJAX data builder - includes all filter parameters
	function getAjaxData(additionalData) {
		return $.extend({
			nonce: ajax_object.nonce,
			getType: demoType,
			filter_license: getFilterLicense(),
			keyword: getCurrentKeyword(),
			checked: getCheckedCategories()
		}, additionalData || {});
	}

	// Get cookie value
	function getCookie(name) {
		var value = "; " + document.cookie;
		var parts = value.split("; " + name + "=");
		if (parts.length === 2) return parts.pop().split(";").shift();
		return null;
	}

	// Set cookie value
	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
	}

	// Get cookie key for pagination (single cookie for page number)
	function getPaginationCookieKey() {
		return 'solaceLoadMore_page';
	}

	// Get current page from cookie
	function getCurrentPage() {
		var cookieKey = getPaginationCookieKey();
		var page = getCookie(cookieKey);
		return page ? parseInt(page, 10) : 1; // Default page 1
	}

	// Set current page to cookie
	function setCurrentPage(page) {
		var cookieKey = getPaginationCookieKey();
		setCookie(cookieKey, page, 1); // 1 day
	}

	// Check if load more button should be shown
	function shouldShowLoadMore(currentPage, totalCount, postsPerPage) {
		if (!totalCount) {
			return false;
		}
		var currentItems = currentPage * postsPerPage;
		var remaining = totalCount - currentItems;
		return remaining >= postsPerPage; // Only show if remaining >= posts_per_page
	}

	// Common success handler for updating demo container
	function updateDemoContainer(data, showLoadMore) {
		var $container = $('section.start-templates main .mycontainer');
		var $loadMoreBtn = $('section.start-templates .content-main main .box-load-more button');
		var $loadMoreContainer = $('section.start-templates .content-main main .box-load-more');
		var postsPerPage = 9;

		$container.html('');
		$container.append(data);
		$container.css('display', 'flex');

		// Get total filtered count from hidden span
		var $totalCountSpan = $container.find('.total-filtered-count');
		var totalFiltered = $totalCountSpan.length ? parseInt($totalCountSpan.text(), 10) : 0;

		// Get current page (should be 1 after filter/search/checkbox change)
		var currentPage = getCurrentPage();
		var currentCount = $container.find('.demo').length;
		
		// Update button attribute with current page
		$loadMoreBtn.attr('data-page', currentPage);

		// Check if load more should be shown
		if (showLoadMore && totalFiltered > 0) {
			var shouldShow = shouldShowLoadMore(currentPage, totalFiltered, postsPerPage);
			$loadMoreContainer.css('display', shouldShow ? 'flex' : 'none');
		} else {
			$loadMoreContainer.css('display', 'none');
		}
	}

	// Get Type - Page builder click handler
	$('section.page-builder .mycontainer .boxes a .mybox').on("click", function(event) {
		event.preventDefault();
		let getType = $(this).attr('data-type');
		window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-starter-templates&type=' + getType;
	});

	// Get Link - Demo click handler
	$('section.start-templates .content-main main .mycontainer').on("click", ".demo", function() {
		let demoName = $(this).attr('data-name');
		demoName = demoName.toLowerCase().replace(/\s+/g, '-');
		let demoUrl = 'https://solacewp.com/' + demoName;

		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: {
				action: 'continue-page-access',
				nonce: ajax_object.nonce,
			},
			success: function(response) {
				localStorage.setItem('solaceInfo', demoUrl);
				localStorage.setItem('solaceDemoName', demoName);
				let adminUrl = pluginUrl.admin_url + `admin.php?page=dashboard-step5&type=${demoType}&demo=${demoName}&nonce=${ajax_object.nonce}`;
				window.location.replace(adminUrl);
			},
			error: function(xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});
	});

	// Debounce function
	function debounce(func, wait) {
		var timeout;
		return function() {
			var context = this;
			var args = arguments;
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				func.apply(context, args);
			}, wait);
		};
	}

	// Ajax Checkbox Handler
	$('.start-templates .box-checkbox input[type="checkbox"]').change(function () {
		// DON'T clear search - keep all filters active
		// Reset pagination to page 1 when filter changes
		setCurrentPage(1);

		var $container = $('section.start-templates main .mycontainer');
		var $loadMoreContainer = $('section.start-templates .content-main main .box-load-more');

		// Add skeleton class when AJAX starts
		$container.addClass('skeleton');

		// Make AJAX request with all current filter parameters
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: getAjaxData({
				action: 'action_ajax_checkbox',
			}),
			success: function (data) {
				// Remove skeleton class when AJAX completes
				$container.removeClass('skeleton');

				// Check if response is valid
				if (!data || (typeof data === 'string' && (data.trim() === '' || data === '0'))) {
					$container.html('');
					$container.append('<span class="not-found" style="font-size:17px;">No demo found...</span>');
					$container.css('display', 'flex');
					$loadMoreContainer.css('display', 'none');
					return;
				}

				// Update container
				$container.html('');
				$container.append(data);
				$container.css('display', 'flex');

				// Get total filtered count
				var $totalCountSpan = $container.find('.total-filtered-count');
				var totalFiltered = $totalCountSpan.length ? parseInt($totalCountSpan.text(), 10) : 0;
				var currentPage = getCurrentPage();
				var postsPerPage = 9;

				// Update button attribute with current page
				var $loadMoreBtn = $loadMoreContainer.find('button');
				$loadMoreBtn.attr('data-page', currentPage);

				// Show/hide load more button - works for all filter combinations
				if (totalFiltered > 0) {
					var shouldShow = shouldShowLoadMore(currentPage, totalFiltered, postsPerPage);
					$loadMoreContainer.css('display', shouldShow ? 'flex' : 'none');
				} else {
					$loadMoreContainer.css('display', 'none');
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				// Remove skeleton class when AJAX error
				$container.removeClass('skeleton');
				console.log('Error:', errorThrown);
				console.log('Response:', xhr.responseText);
			}
		});
	});

	// Ajax Search Handler with Debounce
	var searchHandler = debounce(function() {
		var $container = $('section.start-templates main .mycontainer');
		var $loadMoreContainer = $('section.start-templates .content-main main .box-load-more');
		
		// DON'T clear checkboxes - keep all filters active
		// Reset pagination to page 1 when search changes
		setCurrentPage(1);

		// Add skeleton class when AJAX starts
		$container.addClass('skeleton');

		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: getAjaxData({
				action: 'action_ajax_search',
			}),
			success: function (data) {
				// Remove skeleton class when AJAX completes
				$container.removeClass('skeleton');

				// Check if response is valid
				if (!data || (typeof data === 'string' && (data.trim() === '' || data === '0'))) {
					$container.html('');
					$container.append('<span class="not-found" style="font-size:17px;">No demo found...</span>');
					$container.css('display', 'flex');
					$loadMoreContainer.css('display', 'none');
					return;
				}

				// Update container
				$container.html('');
				$container.append(data);
				$container.css('display', 'flex');

				// Get total filtered count
				var $totalCountSpan = $container.find('.total-filtered-count');
				var totalFiltered = $totalCountSpan.length ? parseInt($totalCountSpan.text(), 10) : 0;
				var currentPage = getCurrentPage();
				var postsPerPage = 9;

				// Update button attribute with current page
				var $loadMoreBtn = $loadMoreContainer.find('button');
				$loadMoreBtn.attr('data-page', currentPage);

				// Show/hide load more button - works for all filter combinations
				if (totalFiltered > 0) {
					var shouldShow = shouldShowLoadMore(currentPage, totalFiltered, postsPerPage);
					$loadMoreContainer.css('display', shouldShow ? 'flex' : 'none');
				} else {
					$loadMoreContainer.css('display', 'none');
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				// Remove skeleton class when AJAX error
				$container.removeClass('skeleton');
				console.log('Error:', errorThrown);
				console.log('Response:', xhr.responseText);
			}
		});
	}, 500); // 500ms debounce delay

	$('section.start-templates .content-main aside .mycontainer .box-search input').on('keyup', searchHandler);

	// Filter License Change Handler
	$('#filter_license').on('change', function() {
		// DON'T clear search and checkboxes - keep all filters active
		// Reset pagination to page 1 when filter changes
		setCurrentPage(1);

		var $container = $('section.start-templates main .mycontainer');
		var $loadMoreContainer = $('section.start-templates .content-main main .box-load-more');

		// Add skeleton class when AJAX starts
		$container.addClass('skeleton');

		// Make AJAX request with all current filter parameters
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: getAjaxData({
				action: 'action_ajax_checkbox',
			}),
			success: function (data) {
				// Remove skeleton class when AJAX completes
				$container.removeClass('skeleton');

				// Check if response is valid
				if (!data || (typeof data === 'string' && (data.trim() === '' || data === '0'))) {
					$container.html('');
					$container.append('<span class="not-found" style="font-size:17px;">No demo found...</span>');
					$container.css('display', 'flex');
					$loadMoreContainer.css('display', 'none');
					return;
				}

				// Update container
				$container.html('');
				$container.append(data);
				$container.css('display', 'flex');

				// Get total filtered count
				var $totalCountSpan = $container.find('.total-filtered-count');
				var totalFiltered = $totalCountSpan.length ? parseInt($totalCountSpan.text(), 10) : 0;
				var currentPage = getCurrentPage();
				var postsPerPage = 9;

				// Update button attribute with current page
				var $loadMoreBtn = $loadMoreContainer.find('button');
				$loadMoreBtn.attr('data-page', currentPage);

				// Show/hide load more button - works for all filter combinations
				if (totalFiltered > 0) {
					var shouldShow = shouldShowLoadMore(currentPage, totalFiltered, postsPerPage);
					$loadMoreContainer.css('display', shouldShow ? 'flex' : 'none');
				} else {
					$loadMoreContainer.css('display', 'none');
				}
			},
			error: function(xhr, textStatus, errorThrown) {
				// Remove skeleton class when AJAX error
				$container.removeClass('skeleton');
				console.log('Error:', errorThrown);
				console.log('Response:', xhr.responseText);
			}
		});
	});

	// Ajax Load More Handler
	var isLoadingMore = false;
	var postsPerPage = 9;

	$('section.start-templates .content-main main .box-load-more button').on("click", function(event) {
		event.preventDefault();

		var $button = $(this);
		var $loadMoreContainer = $button.closest('.box-load-more');
		var $container = $('section.start-templates main .mycontainer');

		if (isLoadingMore) {
			return;
		}
		isLoadingMore = true;

		// Get current page from cookie
		var currentPage = getCurrentPage();

		// Add skeleton class when AJAX starts
		$container.addClass('skeleton');

		// Show loading state
		$button.addClass('active active-button').css({
			'width': '200px',
			'padding-right': '85px',
			'transition': 'width 1s ease, padding-right 1s ease'
		});
		$button.find('dotlottie-player').show();

		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: getAjaxData({
				action: 'action_load_more',
			}), // getAjaxData already includes keyword, checked, filter_license
			success: function (data) {
				// Remove skeleton class when AJAX completes
				$container.removeClass('skeleton');

				// Trim to detect empty payload
				var payload = (typeof data === 'string') ? data.trim() : '';

				// Append if there is content
				if (payload.length > 0) {
					$container.append(payload);
					
					// Get new page number from response
					var $newPageSpan = $container.find('.current-page');
					var newPage = $newPageSpan.length ? parseInt($newPageSpan.text(), 10) : (currentPage + 1);
					
					// Get total filtered count
					var $totalCountSpan = $container.find('.total-filtered-count');
					var totalFiltered = $totalCountSpan.length ? parseInt($totalCountSpan.text(), 10) : 0;

					// Update button attribute with new page
					$button.attr('data-page', newPage);

					// Update cookie with new page
					setCurrentPage(newPage);

					// Check if load more should still be shown
					if (totalFiltered > 0) {
						var shouldShow = shouldShowLoadMore(newPage, totalFiltered, postsPerPage);
						$loadMoreContainer.css('display', shouldShow ? 'flex' : 'none');
					} else {
						$loadMoreContainer.css('display', 'none');
					}
				} else {
					// No more data, hide button
					$loadMoreContainer.css('display', 'none');
				}

				// Hide loading state
				$button.removeClass('active active-button').css({
					'width': 'auto',
					'padding-right': '30px',
					'transition': 'width 1s ease, padding-right 1s ease'
				});
				$button.find('dotlottie-player').hide();
				isLoadingMore = false;
			},
			error: function(xhr, textStatus, errorThrown) {
				// Remove skeleton class when AJAX error
				$container.removeClass('skeleton');
				console.log('Error:', errorThrown);
				console.log('Response:', xhr.responseText);
				$button.removeClass('active active-button');
				$button.find('dotlottie-player').hide();
				isLoadingMore = false;
			}
		});
	});
})( jQuery );
