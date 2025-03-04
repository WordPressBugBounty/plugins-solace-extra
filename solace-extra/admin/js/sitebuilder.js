(function ($) {
    'use strict';

    var solaceConditions = {
        includeLabel: 'Include',
        excludeLabel: 'Exclude'
    };

    $('.status-switch').on('change', function() {
        var postId = $(this).data('post-id');
        var part = $(this).data('part');
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

    $('.all-status-switch').click(function(event) {
        // BULK CHANGE ALL EVERY PART TO DISABLE OR ENABLE
        var part = $(this).data('part-global');
        var vpart = $(this).data('part-global').toUpperCase();  


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

    $('.addnew').on('click', function(e) {
        e.preventDefault();
        var part = $(this).data('part');
        console.log('Add New Party:', part);

        if (part=='singleproduct'){
            $.ajax({
                url: solaceSitebuilderParams.ajaxurl, 
                method: 'POST',
                data: {
                    action: 'create_and_edit_page_single_product',
                    // part: part,
                    nonce: solaceSitebuilderParams.nonce,
                }, 
              success: function(response) {
                if (response.success) {
                    var postId = response.data.post_id;  
                    console.log('postID sukses:'+postId);
                    // window.location.href = response.data.edit_link;
    
                    var editUrl = solaceSitebuilderParams.edit_url.replace('{post_id}', postId).concat('&post_type=solace-sitebuilder&action=elementor');
                    console.log('editUrl:', editUrl);
                    window.location.href = editUrl;
                } else {
                  alert('failed to create new page.');
                }
              },
              error: function(error) {
                console.error(error);
                alert('Unknown Error, please try again.');
              }
            });
        } else if (part=='shopproduct'){
            $.ajax({
                url: solaceSitebuilderParams.ajaxurl, 
                method: 'POST',
                data: {
                    action: 'create_and_edit_page_shop_product',
                    // part: part,
                    nonce: solaceSitebuilderParams.nonce,
                }, 
              success: function(response) {
                if (response.success) {
                    var postId = response.data.post_id;  
                    console.log('postID sukses:'+postId);
                    // window.location.href = response.data.edit_link;
    
                    var editUrl = solaceSitebuilderParams.edit_url.replace('{post_id}', postId).concat('&post_type=solace-sitebuilder&action=elementor');
                    console.log('editUrl:', editUrl);
                    window.location.href = editUrl;
                } else {
                    alert('failed to create new page.');
                }
              },
              error: function(error) {
                console.error(error);
                alert('Unknown Error, please try again.');
              }
            });
        } else if (part=='blogsinglepost'){
            $.ajax({
                url: solaceSitebuilderParams.ajaxurl, 
                method: 'POST',
                data: {
                    action: 'create_and_edit_page_blogsingle_post',
                    // part: part,
                    nonce: solaceSitebuilderParams.nonce,
                }, 
              success: function(response) {
                if (response.success) {
                    var postId = response.data.post_id;  
                    console.log('postID sukses:'+postId);
                    // window.location.href = response.data.edit_link;
    
                    var editUrl = solaceSitebuilderParams.edit_url.replace('{post_id}', postId).concat('&post_type=solace-sitebuilder&action=elementor');
                    console.log('editUrl:', editUrl);
                    window.location.href = editUrl;
                } else {
                    alert('failed to create new page.');
                }
              },
              error: function(error) {
                console.error(error);
                alert('Unknown Error, please try again.');
              }
            });
        } else if (part=='blogarchive'){
            $.ajax({
                url: solaceSitebuilderParams.ajaxurl, 
                method: 'POST',
                data: {
                    action: 'create_and_edit_page_blogarchive',
                    // part: part,
                    nonce: solaceSitebuilderParams.nonce,
                }, 
              success: function(response) {
                if (response.success) {
                    var postId = response.data.post_id;  
                    console.log('postID sukses:'+postId);
                    // window.location.href = response.data.edit_link;
    
                    var editUrl = solaceSitebuilderParams.edit_url.replace('{post_id}', postId).concat('&post_type=solace-sitebuilder&action=elementor');
                    console.log('editUrl:', editUrl);
                    window.location.href = editUrl;
                } else {
                    alert('failed to create new page.');
                }
              },
              error: function(error) {
                console.error(error);
                alert('Unknown Error, please try again.');
              }
            });
        }else {

            $('#add-new-popup').attr('data-part', part); 
            $('#add-new-popup').attr('data-popup-type', 'add-new');
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

            $('#add-new-popup').fadeIn(400);
            $('#add-new-overlay').fadeIn(400); 
        }
        
    });

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
                    var editUrl = solaceSitebuilderParams.edit_url.replace('{post_id}', postId).concat('&post_type=solace-sitebuilder&action=elementor&part=' + part);
                    console.log('editUrl:', editUrl);
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
    
    $('.solace-list-item .delete').on('click', function(e) {
        e.preventDefault(); 

        var deleteLink = $(this).attr('href'); 

        
        var confirmDelete = confirm('Are you sure you want to delete this item?');
        
        if (confirmDelete) {
            window.location.href = deleteLink;
        }
        
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

    // When the rename button is clicked
    $('.solace-rename-button.rename').on('click', function(e) {
        e.preventDefault(); // Prevent default action of the link

        // Get post ID from data-post-id attribute
        selectedPostId = $(this).data('post-id'); 

        // Find the closest parent with class 'solace-list-item' and then find 'title-column' within it
        var currentTitle = $(this).closest('.solace-list-item').find('.title-column').text().trim();
        console.log('Current Title: ' + currentTitle);

        // Set current title in the input field
        $('#solace-rename-field').val(currentTitle); 

        // Show the popup
        $('#solace-rename-popup').fadeIn(400);
    });

    // When the save button is clicked in the popup
    $('#solace-save-rename').on('click', function() {

        var assetsUrl = solaceSitebuilderParams.assetsUrl; 
        
        $('#solace-save-rename').addClass('active');
        $('#solace-save-rename dotlottie-player').fadeIn(1000);


        $(this).addClass('active-button').css({
            'width': '250px',
            'padding-right': '85px',
            'transition': 'width .5s ease, padding-right .5s ease'
        });
        
        var newTitle = $('#solace-rename-field').val(); // Get the new title
        console.log ('new title:'+newTitle);

        if (selectedPostId && newTitle) {
            $.ajax({
                url: solaceSitebuilderParams.ajaxurl, 
                type: 'POST',
                data: {
                    action: 'rename_post_title', // Custom action to handle renaming
                    post_id: selectedPostId,
                    new_title: newTitle,
                    security: solaceSitebuilderParams.nonce, // Use existing nonce
                    nonce: solaceSitebuilderParams.nonce,
                },
                success: function(response) {
                    if (response.success) {
                        // Refresh the page after successful rename
                        location.reload();
                    } else {
                        alert('Failed to rename post. Please try again.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    alert('An error occurred while renaming. Please try again.');
                }
            });
        } else {
            alert('Please enter a new title.');
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
    

    // Close the popup when cancel is clicked
    $('.solace-close-popup').on('click', function() {
        $('#solace-rename-popup').fadeOut();
    });

    // document.addEventListener("DOMContentLoaded", function () {
    //     const iframe = document.querySelector("iframe");
    //     const headerContent = document.querySelector(".preview-content");

    //     const updateHeight = () => {
    //     if (iframe.contentWindow && iframe.contentWindow.document.body) {
    //         const iframeHeight = iframe.contentWindow.document.body.scrollHeight;
    //         headerContent.style.height = iframeHeight + "px";
    //     }
    //     };

    //     iframe.addEventListener("load", updateHeight);

    //     const observer = new MutationObserver(updateHeight);
    //     observer.observe(iframe.contentWindow.document.body, {
    //     childList: true,
    //     subtree: true,
    //     attributes: true,
    //     });
    // });

    // document.addEventListener("DOMContentLoaded", function () {
    //     const headerContents = document.querySelectorAll(".preview-content");

    //     headerContents.forEach((headerContent) => {
    //     const iframe = headerContent.querySelector("iframe");

    //     const updateHeight = () => {
    //         if (iframe.contentWindow && iframe.contentWindow.document.body) {
    //         const iframeHeight = iframe.contentWindow.document.body.scrollHeight;
    //         headerContent.style.height = iframeHeight + "px";
    //         }
    //     };

    //     iframe.addEventListener("load", updateHeight);

    //     iframe.addEventListener("load", () => {
    //         const observer = new MutationObserver(updateHeight);
    //         if (iframe.contentWindow.document.body) {
    //         observer.observe(iframe.contentWindow.document.body, {
    //             childList: true,
    //             subtree: true,
    //             attributes: true,
    //         });
    //         }
    //     });
    //     });
    // });

    

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

