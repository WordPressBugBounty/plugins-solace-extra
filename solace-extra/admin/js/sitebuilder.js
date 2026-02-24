(function ($) {
    'use strict';

    var solaceConditions = {
        includeLabel: 'Include',
        excludeLabel: 'Exclude'
    };

    $('.status-switch').on('change', function() {

        const parts = ['singleproduct', 'purchase-summary', 'blogsinglepost', 'blogarchive', '404', 'shopproduct'];

        parts.forEach(part => {
            $('.status-switch[data-part="' + part + '"]')
                .not(this)
                .prop('checked', false);
        });
            
        var postId = $(this).data('post-id');
        var part = $(this).data('part').toString();
        var status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: solaceSitebuilderParams.ajaxurl,
            type: 'POST',
            data: {
                // action: 'solace_update_header_status',
                action: 'solace_update_sitebuilder_status',
                post_id: postId,
                status: status,
                part: part,
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
            },
            success: function(response) {
                if(response.success) {
                    location.reload();  // Refresh the page after successful update
                } else {
                    alert('Failed to update status.');
                }
            }
        });
    });

    jQuery(document).ready(function($) {
        $('.solace-extra-add-to-cart').on('submit', 'form.cart', function(e) {
            e.preventDefault();
        
            var $form = $(this);
            var $button = $form.find('.ajax_add_to_cart');
            var product_id = $button.data('product_id');
            var quantity = $form.find('.quantity').val();
            var $viewCartButton = $form.siblings('.view_cart_button');
        
            $.ajax({
                type: 'POST',
                url: solaceSitebuilderParams.ajax_url,
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: product_id,
                    quantity: quantity
                },
                beforeSend: function() {
                    $button.addClass('loading').text('Adding...');
                },
                success: function(response) {
                    $button.removeClass('loading');
        
                    if (response.success) {
                        $button.hide();
                        $viewCartButton.show();
                    } else {
                        alert(response.message || 'Failed to add product to cart.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });
        
    });

    

    
    
    
    

    $('.all-status-switch').click(function(event) {
        // BULK CHANGE ALL EVERY PART TO DISABLE OR ENABLE
        var part = $(this).data('part-global').toString();
        var vpart = $(this).data('part-global').toString().toUpperCase();  


        if ($(this).hasClass('disabled-checkbox')) {
            event.preventDefault();
            alert('There is no ' + vpart + ' part that can be activated yet.');
            return false;

        }
        var status = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'solace_update_sitebuilder_all_status',
                status: status,
                part: part,
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
            },
            success: function(response) {
                if(response.success) {
                    location.reload();  // Refresh the page after successful update
                } else {
                    alert('Failed to update status.');
                }
            }
        });
    });

    function generateOptionsHtml(selectedValue) {
        // Debugging: Check if solaceSitebuilderParams is defined
        console.log('solaceSitebuilderParams:', solaceSitebuilderParams);

        if (typeof solaceSitebuilderParams !== 'undefined') {
            console.log('WooCommerce active:', solaceSitebuilderParams.woocommerce);

            var options = `
                <optgroup label="Basic">
                    <option value="basic-global" ${selectedValue === 'basic-global' ? 'selected' : ''}>Entire Website</option>
                    <option value="basic-singulars" ${selectedValue === 'basic-singulars' ? 'selected' : ''}>All Singulars</option>
                    <option value="basic-archives" ${selectedValue === 'basic-archives' ? 'selected' : ''}>All Archives</option>
                </optgroup>
                <optgroup label="Special Pages">
                    <option value="special-404" ${selectedValue === 'special-404' ? 'selected' : ''}>404 Page</option>
                    <option value="special-search" ${selectedValue === 'special-search' ? 'selected' : ''}>Search Page</option>
                    <option value="special-blog" ${selectedValue === 'special-blog' ? 'selected' : ''}>Blog / Posts Page</option>
                    <option value="special-front" ${selectedValue === 'special-front' ? 'selected' : ''}>Front Page</option>
                    <option value="special-date" ${selectedValue === 'special-date' ? 'selected' : ''}>Date Archive</option>
                    <option value="special-author" ${selectedValue === 'special-author' ? 'selected' : ''}>Author Archive</option>
                </optgroup>
                <optgroup label="Posts">
                    <option value="post|all" ${selectedValue === 'post|all' ? 'selected' : ''}>All Posts</option>
                    <option value="post|all|taxarchive|category" ${selectedValue === 'post|all|taxarchive|category' ? 'selected' : ''}>All Categories Archive</option>
                    <option value="post|all|taxarchive|post_tag" ${selectedValue === 'post|all|taxarchive|post_tag' ? 'selected' : ''}>All Tags Archive</option>
                </optgroup>
                <optgroup label="Pages">
                    <option value="page|all" ${selectedValue === 'page|all' ? 'selected' : ''}>All Pages</option>
                </optgroup>
            
            `;

        // Check if WooCommerce is active (passed from PHP)
        if (solaceSitebuilderParams.woocommerce == true || solaceSitebuilderParams.woocommerce == 1) {
            console.log('Adding WooCommerce options...');
                options += `
                    <optgroup label="Products">
                        <option value="product|all" ${selectedValue === 'product|all' ? 'selected' : ''}>All Products</option>
                        <option value="product|all|taxarchive|product_cat" ${selectedValue === 'product|all|taxarchive|product_cat' ? 'selected' : ''}>All Product Categories Archive</option>
                        <option value="product|all|taxarchive|product_tag" ${selectedValue === 'product|all|taxarchive|product_tag' ? 'selected' : ''}>All Product Tags Archive</option>
                    </optgroup>
                `;
            } else {
                console.log('WooCommerce options not added.');
            }
            return options;
        } else {
            console.log('solaceSitebuilderParams is undefined.');
        }
    }

    function generateConditionPopup(postId, conditionsData) {

        var conditionsContainer = $('<div class="conditions-container rico1" data-post-id="' + postId + '" ></div>');
        var assetsUrl = solaceSitebuilderParams.assetsUrl;

        if (!conditionsData || conditionsData.length === 0) {
            conditionsData = [{
                type: 'include',
                value: ''
            }];
        }

        conditionsData.forEach(function(condition, index) {
            var hideClass = index === 0 ? ' hide' : '';  

            var conditionItem = `
                <div class="condition-item">
                    <span class="icon-container">
                        <img src="${assetsUrl}images/plus-include.svg" />
                    </span>
                    <select name="condition_${index + 1}_exclude_include" class="condition-exclude-include-select">
                        <option value="include" ${condition.type === 'include' ? 'selected' : ''}>Include</option>
                        <option value="exclude" ${condition.type === 'exclude' ? 'selected' : ''}>Exclude</option>
                    </select>
                    <select name="condition_${index + 1}_value" class="condition-select">
                        ${generateOptionsHtml(condition.value)}
                    </select>
                    <a href="#" class="delete-condition dashicons dashicons-trash${hideClass}" title="Delete Condition"></a>

                </div>`;
            
            conditionsContainer.append(conditionItem);
        });

        $('#edit-conditions-container').append(conditionsContainer);
    }

    $('.condition-part-select').change(function() {
        var selectedValue = $(this).val();

        if (selectedValue === '404') {
            $('.solace-popup-content #all-new-conditions-container').hide();
            $('.solace-popup-content .solace-popup-footer').hide();

            $('.solace-popup-content .chooseparts').addClass('part404');
        } else {
            $('.solace-popup-content #all-new-conditions-container').show();
            $('.solace-popup-content .solace-popup-footer').show();

            $('.solace-popup-content .chooseparts').removeClass('404');
        }
    });

    $('.edit-conditions-button').on('click', function(e) {
        $('#edit-conditions-container').find('.conditions-container').remove();
        e.preventDefault();
        var postId = $(this).data('post-id');
        $('.conditions-container').fadeIn(400);
        $('.conditions-container[data-post-id="' + postId + '"]').show();

        $('#edit-conditions-popup').fadeIn(400);
        $('#edit-conditions-overlay').fadeIn(400);
        var conditionsData = $(this).data('conditions');

        if (typeof conditionsData === 'string') {
            conditionsData = JSON.parse(conditionsData.replace(/&quot;/g, '"'));
        }
        generateConditionPopup(postId, conditionsData);

    });

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function showPopup(popupSelector, overlaySelector) {
        $(overlaySelector).fadeIn(400); // Fade in overlay
        $(popupSelector).fadeIn(400);  // Fade in popup
    }

    // Function to hide popup and overlay with fade out effect
    function hidePopup(popupSelector, overlaySelector) {
        $(popupSelector).fadeOut(400, function() { // Fade out popup
            $(overlaySelector).fadeOut(400); // Fade out overlay after popup is hidden
        });
    }
    var partValue = getParameterByName('part');

    $('.button.addnew').attr('data-part', partValue);

    // Event handler for 'Add New' button click
    $('.addnew').on('click', function(e) {
        e.preventDefault();

        const part = String($(this).data('part')).trim();
        // Allowed page types for creation
        const allowedTypes = ['header', 'footer', 'singleproduct', 'shopproduct', 'purchase-summary', 'blogsinglepost', 'blogarchive', '404'];

        // Validate if the provided type is allowed
        if ( ! allowedTypes.includes(part) ) {
            console.warn('Invalid type provided:', part);
            return;
        }

        // Disable button to prevent multiple clicks during request
        const $button = $(this);
        $button.prop('disabled', true);

        // If 'part' exists, initiate AJAX request to create a new page
        if ( part && part !== 'header' && part !== 'footer' ) {
            createAndRedirect(part, $button);
        } else if ( part === 'header' || part === 'footer' ) {
            showAddNewPopup(part);
            $button.prop('disabled', false);
        }
    });

    /**
     * AJAX call to create a new custom post and redirect to Elementor editor.
     * @param {string} part - The type of page to create.
     * @param {object} $button - The jQuery button element to re-enable after completion.
     */
    function createAndRedirect(part, $button) {
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl,
            method: 'POST',
            data: {
                action: 'create_and_edit_page',
                type: part,
                nonce: solaceSitebuilderParams.nonce,
            },
            success: function(response) {
                if (response.success) {
                    const postId = response.data.post_id;
                    let editUrl = solaceSitebuilderParams.edit_url
                        .replace('{post_id}', postId)
                        .concat('&post_type=solace-sitebuilder&action=elementor');

                    // Add conditional GET parameters based on part type
                    if (part === 'header' || part === 'footer' || part === '404') {
                        editUrl += '&solace-extra=1';
                    } else if (part === 'singleproduct') {
                        editUrl += '&solace-extra-single=1';
                    } else if (part === 'shopproduct' || part === 'purchase-summary') {
                        editUrl += '&solace-extra-woocommerce=1';
                    } else if (part === 'blogsinglepost') {
                        editUrl += '&solace-extra-single-post=1';
                    } else if (part === 'blogarchive') {
                        editUrl += '&solace-extra-archive=1';
                    }

                    // Append part parameter if not empty
                    if (part && part.trim() !== '') {
                        editUrl += '&part=' + encodeURIComponent(part);
                    }

                    // Redirect to the constructed URL
                    window.location.href = editUrl;
                } else {
                    alert('Failed to create new page.');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                let errorMsg = 'Unknown Error, please try again.';

                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response?.data?.error) {
                        errorMsg = response.data.error;
                    }
                } catch (e) {
                    // Ignore JSON parse errors
                }

                alert(errorMsg);
            },
            complete: function() {
                // Always re-enable the button regardless of success/failure
                $button.prop('disabled', false);
            }
        });
    }

    /**
     * Displays the 'Add New' popup with default condition selectors.
     * @param {string} part - The type of page to associate with the popup (may be empty).
     */
    function showAddNewPopup(part) {
        $('#add-new-popup')
            .attr('data-part', part)
            .attr('data-popup-type', 'add-new');

        $('#add-new-popup .new-conditions-container').html(`
            <div class="condition-item">
                <select name="condition_1_exclude_include" class="condition-exclude-include-select">
                    <option value="include">${solaceConditions.includeLabel}</option>
                    <option value="exclude">${solaceConditions.excludeLabel}</option>
                </select>
                <select name="condition_1_value" class="condition-select">
                    ${generateOptionsHtml()}
                </select>
                <a href="#" class="delete-condition dashicons dashicons-trash" title="Delete Condition"></a>
            </div>
        `);

        $('#add-new-popup, #add-new-overlay').fadeIn(400);
    }

    $('.newpart').on('click', function(e) {
        $('#all-add-new-popup').fadeIn(400);
        $('#all-add-new-overlay').fadeIn(400); 
    });
    
    // This 404 go for redirect to elementor
    $('#save-404').on('click', function(e) {
        // alert('save404');
        e.preventDefault();
        var part = $(this).data('part');
        console.log('Add New Party:', part);

        $('#add-404-popup').attr('data-part', part); 
        $('#add-404-popup').attr('data-popup-type', 'add-new');
        // $('#add-new-popup .new-conditions-container').html(`
        //     <div class="condition-item">
        //         <select name="condition_1_exclude_include" class="condition-exclude-include-select">
        //             <option value="include">${solaceConditions.includeLabel}</option>
        //             <option value="exclude">${solaceConditions.excludeLabel}</option>
        //         </select>
        //         <select name="condition_1_value" class="condition-select">
        //             ${generateOptionsHtml()}
        //         </select>
        //         <a href="#" class="delete-condition dashicons dashicons-trash" title="Delete Condition"></a>
        //     </div>
        // `);
        
        $('#add-404-popup').fadeIn(400);
        $('#add-404-popup-overlay').fadeIn(400); 

    }); 

    // Attach a click event listener to elements with class 'delete-column' inside the specified selector
    $('.solace-list-item .group.action .delete-column .delete').on('click', function(e) {
        e.preventDefault();
        const id = Number($(this).attr('data-id'));
        const listItem = $(this).parent().parent().parent().parent();

        Swal.fire({
            title: solaceSitebuilderI18n.delete_confirm_title,
            text: solaceSitebuilderI18n.delete_confirm_text,
            icon: "warning",
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: solaceSitebuilderI18n.delete_confirm_button,
            cancelButtonText: solaceSitebuilderI18n.delete_cancel_button,
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm btn btn-danger',
                cancelButton: 'swal2-cancel btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Send an AJAX request to delete the item
                $.ajax({
                    url: solaceSitebuilderParams.ajaxurl,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'delete_action',
                        id: id,
                        nonce: solaceSitebuilderParams.nonce
                    },
                    success: function(response) {
                        console.log('AJAX Response:', response);

                        if (response.success) {
                            // Animate the removal of the item
                            listItem.fadeOut(350, function() {
                                $(this).remove(); // Remove from DOM after fade out
                            });
                        } else {
                            alert(response.data?.error || solaceSitebuilderI18n.delete_failed_message);
                            $(this).css('cursor', 'pointer'); // Reset cursor if failed
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown);
                        alert(solaceSitebuilderI18n.delete_error_message);
                        $(this).css('cursor', 'pointer'); // Reset cursor on error
                    }
                });
            }
        });       
    });

    $('#save-new-conditions').on('click', function(e) {
        e.preventDefault();
        var assetsUrl = solaceSitebuilderParams.assetsUrl;
    
        var $container = $('#new-conditions-container');
        if ($container.length === 0) {
            alert('No conditions container found.');
            return;
        }
    
        var conditions = [];
        var selectedValues = new Set(); 
        var postId = 0;
        var part = $('#add-new-popup').data('part');
    
        var hasDuplicate = false; 
    
        $container.find('.condition-item').each(function(index, item) {
            var $item = $(item);
            var conditionType = $item.find('.condition-exclude-include-select').val();
            var conditionValue = $item.find('.condition-select').val();
    
            if (selectedValues.has(conditionValue)) {
                hasDuplicate = true;
                return false; 
            }
    
            selectedValues.add(conditionValue); 
            conditions.push({
                type: conditionType,
                value: conditionValue
            });
        });
    
        if (hasDuplicate) {
            alert('Condition on same instance, please select other conditions');
            return;
        }
    
        $('#save-new-conditions').addClass('active');
        $('#save-new-conditions dotlottie-player').fadeIn(1000);
    
        $(this).addClass('active-button').css({
            'width': '300px',
            'padding-right': '85px',
            'transition': 'width .5s ease, padding-right .5s ease'
        });
    
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl, 
            method: 'POST',
            dataType: 'json', 
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',       
            data: {
                action: 'save_conditions',
                part: part,
                conditions: conditions,
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
                from: $('#add-new-popup').attr('data-popup-type')
            },
            success: function(response) {
                console.log('AJAX Response #save-new-conditions:', response); // Debugging
                if (response.success) {
                    postId = response.data.post_id; 
                    console.log('postID sukses:' + postId);
                    saveEditConditions(postId, part, conditions);
                } else {
                    alert('Failed to save conditions: ' + (response.data || 'Unknown error.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                console.error('Response Text:', jqXHR.responseText); 
                alert('An error occurred while saving. Please try again.');
            }
        });
    
    });
    
    function saveEditConditions(postId, part, conditions) {
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl,
            method: 'POST',
            data: {
                action: 'save_edit_conditions',
                post_id: postId,
                conditions: conditions,
                post_type: 'solace-sitebuilder',
                nonce: solaceSitebuilderParams.nonce, 
            },
            success: function(response) {
                console.log('AJAX response saveEditConditions:', response);

                if (response.success) {
                    var editUrl = solaceSitebuilderParams.edit_url.replace('{post_id}', postId).concat('&post_type=solace-sitebuilder&action=elementor&solace-extra=1&part=' + part);
                    window.location.href = editUrl;
                } else {

                    alert('Failed to save conditions. Please try again.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred while saving. Please try again.');
            }
        });
    }

    $('#save-conditions').on('click', function(e) {
        e.preventDefault();
    
        var $container = $('.conditions-container');
        if ($container.length === 0) {
            alert('No conditions container found.');
            return;
        }
    
        var conditions = [];
        var selectedValues = new Set(); 
        var postId = $('.conditions-container').data('post-id');
        var part = $('.addnew').data('part');
        
        var hasDuplicate = false; 

        $container.find('.condition-item').each(function(index, item) {
            var $item = $(item);
            var conditionType = $item.find('.condition-exclude-include-select').val();
            var conditionValue = $item.find('.condition-select').val();
    
            if (selectedValues.has(conditionValue)) {
                hasDuplicate = true;
                return false; 
            }
    
            selectedValues.add(conditionValue); 
            conditions.push({
                type: conditionType,
                value: conditionValue
            });
        });
    
        if (hasDuplicate) {
            alert('Condition on same instance, please select other conditions');
            return;
        }


        $('#save-conditions').addClass('active');
        $('#save-conditions dotlottie-player').fadeIn(1000);

        $(this).addClass('active-button').css({
            'width': '300px',
            'padding-right': '85px',
            'transition': 'width .5s ease, padding-right .5s ease'
        });
    
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl, 
            method: 'POST',
            dataType: 'json', 
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',       
            data: {
                action: 'save_conditions',
                post_id: postId,
                part: part,
                conditions: conditions,
                from: 'edit',
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = solaceSitebuilderParams['part_' + part];
                } else {
                    alert('Failed to save conditions: ' + (response.data || 'Unknown error.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred while saving. Please try again.');
            }
        });
    
    });

    $('#partnew-save-new-conditions').on('click', function(e) {
        e.preventDefault();
        var assetsUrl = solaceSitebuilderParams.assetsUrl; 
    
        var $container = $('#all-new-conditions-container');
        if ($container.length === 0) {
            alert('No conditions container found.');
            return;
        }
    
        var conditions = [];
        var selectedValues = new Set(); 
        var part = $('.condition-part-select').val();
        var postId = 0;
    
        var hasDuplicate = false; 
    
        $container.find('.condition-item').each(function(index, item) {
            var $item = $(item);
            var conditionType = $item.find('.condition-exclude-include-select').val();
            var conditionValue = $item.find('.condition-select').val();
    
            if (selectedValues.has(conditionValue)) {
                hasDuplicate = true;
                return false; 
            }
    
            selectedValues.add(conditionValue); 
            conditions.push({
                type: conditionType,
                value: conditionValue
            });
        });
    
        if (hasDuplicate) {
            alert('Condition on same instance, please select other conditions');
            return;
        }
    
        $('#partnew-save-new-conditions').addClass('active');
        $('#partnew-save-new-conditions dotlottie-player').fadeIn(1000);
    
        $(this).addClass('active-button').css({
            'width': '300px',
            'padding-right': '85px',
            'transition': 'width .5s ease, padding-right .5s ease'
        });
    
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl, 
            method: 'POST',
            dataType: 'json', 
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',       
            data: {
                action: 'save_conditions',
                part: part,
                conditions: conditions,
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
                from: 'add-new'
            },
            success: function(response) {
                console.log('AJAX Response:', response); 
                if (response.success) {
                    postId = response.data.post_id; 
                    console.log('postID sukses:' + postId);
                    saveEditConditions(postId, part, conditions);
                } else {
                    alert('Failed to save conditions: ' + (response.data || 'Unknown error.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                console.error('Response Text:', jqXHR.responseText); 
                alert('An error occurred while saving. Please try again.');
            }
        });

    });
    
    $('#add-condition').on('click', function() {
        var conditionsContainer = $('.conditions-container:visible');
        var conditionCount = conditionsContainer.find('.condition-item').length + 1;
        var assetsUrl = solaceSitebuilderParams.assetsUrl;

        var conditionItem = $(`
            <div class="condition-item">
                <span class="icon-container">
                    <img src="${assetsUrl}images/plus-include.svg" />
                </span>
                <select name="condition_${conditionCount}_exclude_include" class="condition-exclude-include-select">
                    <option value="include">${solaceConditions.includeLabel}</option>
                    <option value="exclude">${solaceConditions.excludeLabel}</option>
                </select>
                <select name="condition_${conditionCount}_value" class="condition-select">
                    ${generateOptionsHtml()}
                </select>
                <a href="#" class="delete-condition dashicons dashicons-trash" title="Delete Condition"></a>
            </div>
        `);
        conditionsContainer.append(conditionItem);
        
    });

    $('#addnew-add-condition').on('click', function() {
        var conditionsContainer = $('#new-conditions-container:visible');
        var conditionCount = conditionsContainer.find('.condition-item').length + 1;
        var assetsUrl = solaceSitebuilderParams.assetsUrl;

        var conditionItem = $(`
            <div class="condition-item">
                <span class="icon-container">
                    <img src="${assetsUrl}images/plus-include.svg" />
                </span>
                <select name="condition_${conditionCount}_exclude_include" class="condition-exclude-include-select">
                    <option value="include">${solaceConditions.includeLabel}</option>
                    <option value="exclude">${solaceConditions.excludeLabel}</option>
                </select>
                <select name="condition_${conditionCount}_value" class="condition-select">
                    ${generateOptionsHtml()}
                </select>
                <a href="#" class="delete-condition dashicons dashicons-trash" title="Delete Condition"></a>
            </div>
        `);
        conditionsContainer.append(conditionItem);
        
    });

    $('#partnew-add-condition').on('click', function() {
        var conditionsContainer = $('#all-new-conditions-container:visible');
        var conditionCount = conditionsContainer.find('.condition-item').length + 1;
        var assetsUrl = solaceSitebuilderParams.assetsUrl;

        var conditionItem = $(`
            <div class="condition-item">
                <span class="icon-container">
                    <img src="${assetsUrl}images/plus-include.svg" />
                </span>
                <select name="condition_${conditionCount}_exclude_include" class="condition-exclude-include-select">
                    <option value="include">${solaceConditions.includeLabel}</option>
                    <option value="exclude">${solaceConditions.excludeLabel}</option>
                </select>
                <select name="condition_${conditionCount}_value" class="condition-select">
                    ${generateOptionsHtml()}
                </select>
                <a href="#" class="delete-condition dashicons dashicons-trash" title="Delete Condition"></a>
            </div>
        `);
        conditionsContainer.append(conditionItem);
        
    });

    $('#edit-conditions-popup').on('click', '.delete-condition', function(e) {
        e.preventDefault();
        $(this).closest('.condition-item').remove();
    });

    $('#add-new-popup').on('click', '.delete-condition', function(e) {
        e.preventDefault();
        $(this).closest('.condition-item').remove();
    });

    $('#all-add-new-popup').on('click', '.delete-condition', function(e) {
        e.preventDefault();
        $(this).closest('.condition-item').remove();
    });

    function hidePopup() {
        $('#edit-conditions-overlay').fadeOut(400);
        $('#edit-conditions-popup').fadeOut(400);
        $('#add-new-overlay').fadeOut(400);
        $('#add-new-popup').fadeOut(400);
        $('#all-add-new-overlay').fadeOut(400);
        $('#all-add-new-popup').fadeOut(400);
    }

    $('#edit-conditions-popup .close-popup, #edit-conditions-overlay').on('click', function() {
        hidePopup();
    });

    $('#add-new-popup .close-popup, #add-new-overlay').on('click', function() {
        hidePopup();
    });

    $('#all-add-new-popup .close-popup, #all-add-new-overlay').on('click', function() {
        hidePopup();
    });

    $('#add-include-condition').on('click', function() {
        var includeHtml = `
            <select name="solace_sitebuilder_conditions[include][]">
                ${generateOptionsHtml()}
            </select>`;
        $('#include-conditions-container').append(includeHtml);
    });

    $('#add-exclude-condition').on('click', function() {
        var excludeHtml = `
            <select name="solace_sitebuilder_conditions[exclude][]">
                ${generateOptionsHtml()}
            </select>`;
        $('#exclude-conditions-container').append(excludeHtml);
    });

    var selectedPostId = null; // To keep track of the selected post

    // When the Rename button is clicked
    $('.solace-rename-button').on('click', function(e) {
        e.preventDefault();

        const $button = $(this);
        const postId = $button.data('post-id');
        const $item = $button.closest('.solace-list-item');
        const $titleColumn = $item.find('.title-column');

        // Prevent action if the rename input is already active in this item
        if ($titleColumn.find('.rename-input').length) {
            return;
        }

        // Reset all title columns back to their original titles (if any)
        $('.solace-list-item .title-column').each(function() {
            const $col = $(this);
            const originalTitle = $col.data('original-title');

            // Restore the original title only if it was previously saved
            if (originalTitle) {
                $col.text(originalTitle);
            }
        });

        // Store the current title as a data attribute for possible reset later
        const currentTitle = $titleColumn.text().trim();
        $titleColumn.data('original-title', currentTitle);

        // Replace the title text with an input field, save button, and cancel button
        $titleColumn.html(`
            <input style="border-radius: 8px; border: 1px solid #f1f1f1;" type="text" class="rename-input" value="${currentTitle}" />
            <button style="margin-left: 10px; padding: 0 20px;" class="button save-rename" data-post-id="${postId}">Save</button>
            <button style="margin-left: 5px; padding: 0 20px;" class="button cancel-rename">Cancel</button>
        `);

        // Focus the input after it has been added to the DOM
        const $input = $titleColumn.find('.rename-input');
        $input.focus();

        // Move cursor to the end of the text
        const inputEl = $input.get(0);
        inputEl.setSelectionRange(inputEl.value.length, inputEl.value.length);

    });

    // When the Save button is clicked
    $(document).on('click', '.save-rename', function(e) {
        e.preventDefault();

        const $saveBtn = $(this);
        const postId = $saveBtn.data('post-id');
        const $input = $saveBtn.siblings('.rename-input');
        const newTitle = $input.val();

        // Send AJAX request to WordPress to update the post title
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'solace_rename_post_title',
                post_id: postId,
                new_title: newTitle,
                nonce: solaceSitebuilderParams.nonce
            },
            success: function(response) {
                if (response.success) {
                    $saveBtn.closest('.title-column')
                        .text(newTitle)
                        .data('original-title', newTitle); // Update the original title

                    Swal.fire({
                        icon: 'success',
                        title: solaceSitebuilderI18n.rename_success_title,
                        text: solaceSitebuilderI18n.rename_success_text,
                        showCloseButton: true,
                        customClass: {
                            confirmButton: 'swal2-confirm-orange'
                        },
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: solaceSitebuilderI18n.rename_failed_title,
                        text: solaceSitebuilderI18n.rename_failed_text,
                        showCloseButton: true,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: solaceSitebuilderI18n.ajax_error_title,
                    text: solaceSitebuilderI18n.ajax_error_text,
                    showCloseButton: true,
                });
            }
        });
    });

    // When the Cancel button is clicked
    $(document).on('click', '.cancel-rename', function(e) {
        e.preventDefault();

        const $cancelBtn = $(this);
        const $titleColumn = $cancelBtn.closest('.title-column');
        const originalTitle = $titleColumn.data('original-title');

        if (originalTitle) {
            $titleColumn.text(originalTitle);
        }
    });

    // Trigger save on Enter key in rename input
    $(document).on('keydown', '.rename-input', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            $(this).siblings('.save-rename').trigger('click');
        }
    });

    $('#solace-save-404').on('click', function(e) {
        e.preventDefault();
        
        $('#solace-save-404').addClass('active');
        $('#solace-save-404 dotlottie-player').fadeIn(1000);
    
        $(this).addClass('active-button').css({
            'width': '300px',
            'padding-right': '85px',
            'transition': 'width .5s ease, padding-right .5s ease'
        });
    
        var part = 404;
        var postId=0;

        var conditions = [{
            type: '',    
            value: ''    
        }];
    
        console.log('Conditions to be sent:', conditions);
    
        $.ajax({
            url: solaceSitebuilderParams.ajaxurl, 
            method: 'POST',
            dataType: 'json', 
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',       
            data: {
                action: 'save_conditions',
                part: part,
                conditions: conditions,
                from: 'add-new',
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
            },
            success: function(response) {
                console.log('AJAX Response:', response); 
                if (response.success) {
                    postId = response.data.post_id; 
                    console.log('postID sukses:'+postId);
                    saveEditConditions(postId, part, conditions);
                } else {
                    alert('Failed to save conditions: ' + (response.data || 'Unknown error.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                console.error('Response Text:', jqXHR.responseText); 
                alert('An error occurred while saving. Please try again.');
            }
        });
    });
    
    document.addEventListener("DOMContentLoaded", function() {
        const iframe = document.getElementById("responsive-iframe");
        const customHeaderContent = document.getElementById("custom-header-content");
        const previewContainer = document.querySelector(".preview-container");
        

        function adjustWidths() {
            if (!previewContainer) {
                console.warn("Skipping adjustWidths: .preview-container not found.");
                return;
            }

            const containerWidth = previewContainer.offsetWidth;
            const minWidth = 1025; // Minimum width in pixels
        
            const adjustedWidth = containerWidth < minWidth ? minWidth : containerWidth;
        
            // Calculate scale factor based on ratio between custom-header-content width and iframe width
            const screenWidth = window.innerWidth;
            const customHeaderWidth = screenWidth - 479; // Custom header content width
            const iframeWidth = 1400; // Fixed iframe width
            const scaleFactor = customHeaderWidth / iframeWidth; // Calculate scale ratio
        
            iframe.style.width = `${iframeWidth}px`; // Fixed iframe width
            iframe.style.height = `${iframeWidth * 0.1819375}px`; // Maintain aspect ratio (254.7125 / 1400)
            iframe.style.transform = `scale(${scaleFactor})`;
            iframe.style.transformOrigin = "top left";
        
            // Apply calculated width to customHeaderContent
            customHeaderContent.style.width = `${customHeaderWidth}px`;
        
            // Debugging logs
            // console.log("Container Width:", containerWidth);
            // console.log("Adjusted Width:", adjustedWidth);
            // console.log("Scale Factor:", scaleFactor);
            // console.log("Iframe Width:", iframe.style.width);
            // console.log("Iframe Height:", iframe.style.height);
            // console.log("Screen Width:", screenWidth);
            // console.log("Custom Header Content Width:", customHeaderContent.style.width);
        }

        
        
        // Function to check if the URL contains the parameter "part"
        function hasPartParameter() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.has("part"); // Returns true if "part" exists in the URL
        }

        // Run adjustWidths only if the URL contains "&part"
        if (hasPartParameter() && previewContainer) {
            adjustWidths();
            window.addEventListener("resize", adjustWidths);
        } else {
            // console.log("Skipping adjustWidths: URL parameter 'part' not found.");
        }
        
    });

    $(window).on('elementor/frontend/init', function() {
        console.log("Elementor frontend initialized");

        // Mengecek apakah ada control untuk background-color
        if (typeof elementor !== 'undefined' && elementor.settings && elementor.settings.controls) {
            console.log("Elementor settings controls found");

            // Memastikan kontrol button background color ada
            if (elementor.settings.controls.button_background_color) {
                elementor.settings.controls.button_background_color.on('change', function(newColor) {
                    console.log("New background color from color picker: ", newColor);

                    // Terapkan warna baru pada elemen .woocommerce ul.products li.product .button
                    if (newColor) {
                        $('.woocommerce ul.products li.product .button').css('background-color', newColor);
                        console.log("New color applied to WooCommerce button:", newColor);
                    }
                });
            }
        } else {
            console.warn("Elementor settings or controls not found");
        }
    });



})(jQuery);

document.addEventListener("DOMContentLoaded", function() {
    const upgradeUrl = solaceSitebuilderParams.upgradeUrl;

    document.querySelectorAll(".lock button").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            window.open(upgradeUrl, "_blank");
        });
    });
});
