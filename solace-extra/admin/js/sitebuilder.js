(function ($) {
    'use strict';

    var solaceConditions = {
        includeLabel: 'Include',
        excludeLabel: 'Exclude'
    };

    // $('.status-switch').on('change', function() {

    //     const parts = ['singleproduct', 'purchase-summary', 'blogsinglepost', 'blogarchive', '404', 'shopproduct'];

    //     parts.forEach(part => {
    //         $('.status-switch[data-part="' + part + '"]')
    //             .not(this)
    //             .prop('checked', false);
    //     });
            
    //     var postId = $(this).data('post-id');
    //     var part = $(this).data('part').toString();
    //     var status = $(this).is(':checked') ? 1 : 0;

    //     $.ajax({
    //         url: solaceSitebuilderParams.ajaxurl,
    //         type: 'POST',
    //         data: {
    //             // action: 'solace_update_header_status',
    //             action: 'solace_update_sitebuilder_status',
    //             post_id: postId,
    //             status: status,
    //             part: part,
    //             nonce: solaceSitebuilderParams.nonce,
    //             security: solaceSitebuilderParams.nonce,
    //         },
    //         success: function(response) {
    //             if(response.success) {
    //                 location.reload();  // Refresh the page after successful update
    //             } else {
    //                 alert('Failed to update status.');
    //             }
    //         }
    //     });
    // });

    $('.status-switch').on('change', function() {
        var postId = $(this).data('post-id');
        var part = $(this).data('part').toString();
        var status = $(this).is(':checked') ? 1 : 0;

                // singleproduct / shopproduct templates each have their own conditions,
        // so multiple may stay active at once — don't uncheck siblings.
        if (part !== 'header' && part !== 'footer' && part !== 'singleproduct' && part !== 'shopproduct') {
            
            const parts = ['singleproduct', 'purchase-summary', 'blogsinglepost', 'blogarchive', '404', 'shopproduct', 'cart', 'checkout', 'myaccount'];

            parts.forEach(p => {
                if (p === part) {
                    $('.status-switch[data-part="' + p + '"]')
                        .not(this)
                        .prop('checked', false);
                }
            });
        } else {
        }

        $.ajax({
            url: solaceSitebuilderParams.ajaxurl,
            type: 'POST',
            data: {
                action: 'solace_update_sitebuilder_status',
                post_id: postId,
                status: status,
                part: part,
                nonce: solaceSitebuilderParams.nonce,
                security: solaceSitebuilderParams.nonce,
            },
            success: function(response) {
                console.log('Respon Server:', response);
                if(response.success) {
                    location.reload(); 
                } else {
                    alert(response.data);
                    // The switches were changed optimistically: show the real state again.
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred during the AJAX request.');
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
                    quantity: quantity,
                    nonce: $form.find('input[name="nonce"]').val()
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
                    alert(response.data || 'Failed to update status.');
                    location.reload();
                }
            }
        });
    });

    /**
     * Condition options for the Shop Product Archive part, mirroring the
     * Elementor Theme Builder "Products Archive" conditions.
     *
     * @param {string} selectedValue Currently saved condition value.
     * @return {string} <optgroup>/<option> HTML.
     */
    function generateProductArchiveOptionsHtml(selectedValue) {
        // Normalize a saved value like `product|brand|37` down to its type so
        // the type select marks the right option; the actual term is handled
        // by the separate term dropdown (see buildProductArchiveTermSelect).
        var type = selectedValue || '';
        if (type.indexOf('product|brand|') === 0) {
            type = 'product|brand';
        } else if (type.indexOf('product|cat|') === 0) {
            type = 'product|cat';
        } else if (type.indexOf('product|tag|') === 0) {
            type = 'product|tag';
        }

        var html = '';
        html += '<optgroup label="' + (typeof solaceConditions !== 'undefined' && solaceConditions.shopArchive ? solaceConditions.shopArchive : 'Product Archive') + '">';
        html += '<option value="product|shop" ' + (type === 'product|shop' ? 'selected' : '') + '>Shop Page</option>';
        html += '<option value="product|all|archive" ' + (type === 'product|all|archive' ? 'selected' : '') + '>All Product Archive</option>';
        html += '<option value="product|search" ' + (type === 'product|search' ? 'selected' : '') + '>Search Results</option>';
        html += '<option value="product|brand" ' + (type === 'product|brand' ? 'selected' : '') + '>Product Brand</option>';
        html += '<option value="product|cat" ' + (type === 'product|cat' ? 'selected' : '') + '>Product Category</option>';
        html += '<option value="product|tag" ' + (type === 'product|tag' ? 'selected' : '') + '>Product Tag</option>';
        html += '</optgroup>';
        return html;
    }

    /**
     * Condition options for the Shop Single Product part, mirroring the
     * Elementor Theme Builder "Product Single" conditions.
     *
     * @param {string} selectedValue Currently saved condition value.
     * @return {string} <option> HTML.
     */
    function generateSingleProductOptionsHtml(selectedValue) {
        var type = selectedValue || '';
        var prefixes = ['product|product|', 'product|brandchild|', 'product|catchild|', 'product|brand|', 'product|cat|', 'product|tag|', 'product|author|'];
        for (var i = 0; i < prefixes.length; i++) {
            if (type.indexOf(prefixes[i]) === 0) {
                type = prefixes[i].slice(0, -1);
                break;
            }
        }

        var html = '';
        html += '<option value="product|all" ' + (type === 'product|all' ? 'selected' : '') + '>All Products</option>';
        html += '<option value="product|brand" ' + (type === 'product|brand' ? 'selected' : '') + '>In Brand</option>';
        html += '<option value="product|brandchild" ' + (type === 'product|brandchild' ? 'selected' : '') + '>In Child Product Brand</option>';
        html += '<option value="product|cat" ' + (type === 'product|cat' ? 'selected' : '') + '>In Category</option>';
        html += '<option value="product|catchild" ' + (type === 'product|catchild' ? 'selected' : '') + '>In Child Product Category</option>';
        html += '<option value="product|tag" ' + (type === 'product|tag' ? 'selected' : '') + '>In Tag</option>';
        html += '<option value="product|author" ' + (type === 'product|author' ? 'selected' : '') + '>Product by Author</option>';
        return html;
    }

    /**
     * Whether a condition type value requires a term or product picker.
     *
     * @param {string} typeVal Condition type select value.
     * @return {string|null} The type value when it needs a picker, else null.
     */
    function getProductArchiveTermType(typeVal) {
        if (typeVal === 'product|brand' || typeVal === 'product|brandchild' || typeVal === 'product|cat' || typeVal === 'product|catchild' || typeVal === 'product|tag' || typeVal === 'product|author' || typeVal === 'product|product') {
            return typeVal;
        }
        return null;
    }

    /**
     * Term list for a condition type (id => name map from PHP localize).
     *
     * @param {string} typeVal Condition type select value.
     * @return {object} id => name map, possibly empty.
     */
    function getProductArchiveTermListForType(typeVal) {
        var map = {
            'product|brand': 'product_brands',
            'product|brandchild': 'product_brands',
            'product|cat': 'product_categories',
            'product|catchild': 'product_categories',
            'product|tag': 'product_tags',
            'product|author': 'product_authors',
            'product|product': 'product_products'
        };
        var key = map[typeVal];
        return key && typeof solaceSitebuilderParams !== 'undefined' && solaceSitebuilderParams[key]
            ? solaceSitebuilderParams[key]
            : {};
    }

    /**
     * "All" option label for a term type on the Shop Product Archive part.
     *
     * A bare brand/category/tag type matches every archive of that kind, so the
     * term dropdown's first option is "All <Type>" instead of "— Select —".
     *
     * @param {string} termType Condition type (product|brand, product|cat, product|tag).
     * @return {string} Empty when the type has no ALL option.
     */
    function productArchiveAllLabel(termType) {
        if (termType === 'product|brand') {
            return 'All Brands';
        }
        if (termType === 'product|brandchild') {
            return 'All Child Brands';
        }
        if (termType === 'product|cat') {
            return 'All Categories';
        }
        if (termType === 'product|catchild') {
            return 'All Child Categories';
        }
        if (termType === 'product|tag') {
            return 'All Tags';
        }
        return '';
    }

    /**
     * Build the searchable term dropdown for the Shop Product Archive condition
     * row. Hidden until a term-type condition is selected.
     *
     * @param {string} savedValue Saved condition value (e.g. product|brand|37).
     * @param {string} part       Current part (shopproduct / singleproduct).
     * @return {string} <select> HTML.
     */
    function buildProductArchiveTermSelect(savedValue, part) {
        var type = '';
        var termId = '';
        ['product|brand|', 'product|brandchild|', 'product|cat|', 'product|catchild|', 'product|tag|', 'product|author|', 'product|product|'].forEach(function (prefix) {
            if ((savedValue || '').indexOf(prefix) === 0) {
                type = prefix.slice(0, -1);
                termId = savedValue.slice(prefix.length);
            }
        });
        // A bare term type (e.g. "product|cat") = "ALL" archives of that kind.
        if (!type && ['product|brand', 'product|brandchild', 'product|cat', 'product|catchild', 'product|tag', 'product|author', 'product|product'].indexOf(savedValue || '') !== -1) {
            type = savedValue;
        }

        var allLabel = (part === 'shopproduct' || part === 'singleproduct') ? productArchiveAllLabel(type) : '';
        var list = getProductArchiveTermListForType(type);
        var html = '<select name="condition_term" class="condition-select condition-term-select" style="display:none;" data-condition-type="' + type + '">';
        // The ALL option uses a non-empty value: an empty value would be
        // treated by Select2 as its placeholder and hidden from the open
        // dropdown, so the user could never switch back to "All <Type>".
        if (allLabel) {
            html += '<option value="all"' + (termId === '' || termId === 'all' ? ' selected' : '') + '>' + allLabel + '</option>';
        } else {
            html += '<option value="">' + (typeof solaceConditions !== 'undefined' && solaceConditions.selectTerm ? solaceConditions.selectTerm : '— Select —') + '</option>';
        }
        Object.keys(list).forEach(function (id) {
            html += '<option value="' + id + '"' + (termId === id ? ' selected' : '') + '>' + list[id] + '</option>';
        });
        html += '</select>';
        return html;
    }

    /**
     * Recompute the composed condition value into the row's hidden input.
     *
     * @param {jQuery} $item The .condition-item row.
     */
    function updateConditionValue($item) {
        var type = $item.find('.condition-select').first().val();
        var termType = getProductArchiveTermType(type);
        var value = type;
        if (termType) {
            var termId = $item.find('.condition-term-select').val();
            // "all" (the ALL option) composes back to the bare type, which the
            // matcher treats as "every archive of this kind".
            value = (termId && termId !== 'all') ? type + '|' + termId : type;
        }
        $item.find('.condition-value').val(value);
    }

    /**
     * Read the composed condition value of a row.
     *
     * Shop Product Archive rows keep the composed value in a hidden
     * `.condition-value` input; every other part reads the value select
     * directly.
     *
     * @param {jQuery} $item The .condition-item row.
     * @return {string} Condition value.
     */
    function solaceGetConditionRowValue($item) {
        var $value = $item.find('.condition-value');
        if ($value.length) {
            return $value.val();
        }
        return $item.find('.condition-select').val();
    }

    /**
     * Validate a composed condition value before saving.
     *
     * On the Shop Product Archive part a bare brand/category/tag type means
     * "ALL" (matches every brand/category/tag archive), so it is savable. On
     * every other part a bare term type would never match on the frontend, so
     * it stays blocked.
     *
     * @param {string} value Condition value.
     * @return {boolean} True when the value is savable.
     */
    function solaceValidateConditionValue(value) {
        var part = getParameterByName('part');
        if (part === 'shopproduct') {
            if (value === 'product|brand' || value === 'product|cat' || value === 'product|tag') {
                return true;
            }
        }
        // Shop Single Product: a bare brand/brandchild/category/child-category/tag
        // type = "ALL" (any brand/child brand/category/child-category/tag).
        if (part === 'singleproduct') {
            if (value === 'product|brand' || value === 'product|brandchild' || value === 'product|cat' || value === 'product|catchild' || value === 'product|tag') {
                return true;
            }
        }
        return value !== 'product|brand' && value !== 'product|brandchild' && value !== 'product|cat' && value !== 'product|catchild' && value !== 'product|tag' && value !== 'product|author' && value !== 'product|product';
    }

    /**
     * Build a condition row for the conditions popup.
     *
     * @param {object} condition Saved condition {type, value}.
     * @param {number} index     Row index (for name attributes).
     * @return {jQuery} The row element.
     */
    function solaceBuildConditionItem(condition, index) {
        var assetsUrl = solaceSitebuilderParams.assetsUrl;
        var hideClass = index === 0 ? ' hide' : '';
        var part = getParameterByName('part');
        var value = condition && condition.value ? condition.value : '';
        var type = condition && condition.type ? condition.type : 'include';

        var $item = $(
            '<div class="condition-item">' +
                '<span class="icon-container"><img src="' + assetsUrl + 'images/plus-include.svg" /></span>' +
                '<select name="condition_' + (index + 1) + '_exclude_include" class="condition-exclude-include-select">' +
                    '<option value="include"' + (type === 'include' ? ' selected' : '') + '>Include</option>' +
                    '<option value="exclude"' + (type === 'exclude' ? ' selected' : '') + '>Exclude</option>' +
                '</select>' +
                '<select name="condition_' + (index + 1) + '_value" class="condition-select">' +
                    generateOptionsHtml(value, part) +
                '</select>' +
                (part === 'shopproduct' || part === 'singleproduct' ? buildProductArchiveTermSelect(value, part) : '') +
                // Only the product-archive parts get this hidden input, because only
                // they have something keeping it in sync: their value is composed from
                // two selects and initProductArchiveConditionRow rewrites it on every
                // change. Nothing maintained it for the other parts, so it kept the
                // value the row was built with while the visible select moved on --
                // and solaceGetConditionRowValue prefers the input, so editing a
                // header or footer condition saved the value the user had replaced.
                (part === 'shopproduct' || part === 'singleproduct'
                    ? '<input type="hidden" class="condition-value" value="' + $('<div>').text(value).html() + '" />'
                    : '') +
                '<a href="#" class="delete-condition dashicons dashicons-trash' + hideClass + '" title="Delete Condition"></a>' +
            '</div>'
        );

        if (part === 'shopproduct' || part === 'singleproduct') {
            initProductArchiveConditionRow($item);
        }

        return $item;
    }

    /**
     * Wire the type -> term dropdown behaviour for one Shop Product Archive row
     * and initialise its searchable term select.
     *
     * @param {jQuery} $item The .condition-item row.
     */
    function initProductArchiveConditionRow($item) {
        var $type = $item.find('.condition-select').first();
        var $term = $item.find('.condition-term-select');
        var $value = $item.find('.condition-value');

        var refreshTermOptions = function () {
            var termType = getProductArchiveTermType($type.val());
            if (termType) {
                var part = getParameterByName('part');
                var allLabel = (part === 'shopproduct' || part === 'singleproduct') ? productArchiveAllLabel(termType) : '';
                var placeholder = allLabel || '— Select —';
                var list = getProductArchiveTermListForType(termType);
                // Shop Product Archive: the first option is "All <Type>" and is
                // selected by default, so choosing just the type matches every
                // brand/category/tag archive. Non-empty value so Select2 lists
                // it in the open dropdown (an empty value becomes its hidden
                // placeholder).
                var opts = allLabel
                    ? '<option value="all" selected>' + allLabel + '</option>'
                    : '<option value="">— Select —</option>';
                Object.keys(list).forEach(function (id) {
                    opts += '<option value="' + id + '">' + list[id] + '</option>';
                });
                $term.html(opts);
                if (typeof $term.select2 === 'function' && $term.data('select2')) {
                    $term.select2('destroy');
                }
                if (typeof $term.select2 === 'function') {
                    $term.select2({ placeholder: placeholder, minimumResultsForSearch: 1, dropdownParent: $term.parent() });
                }
                $term.show();
            } else {
                if (typeof $term.select2 === 'function' && $term.data('select2')) {
                    $term.select2('destroy');
                }
                $term.hide().val('');
            }
            updateConditionValue($item);
        };

        $type.on('change', refreshTermOptions);
        $term.on('change', function () { updateConditionValue($item); });

        // Show the term dropdown immediately when an existing saved value is a term type.
        if (getProductArchiveTermType($type.val())) {
            $term.show();
            if (typeof $term.select2 === 'function') {
                var termType = getProductArchiveTermType($type.val());
                var part = getParameterByName('part');
                var placeholder = ((part === 'shopproduct' || part === 'singleproduct') ? productArchiveAllLabel(termType) : '') || '— Select —';
                $term.select2({ placeholder: placeholder, minimumResultsForSearch: 1, dropdownParent: $term.parent() });
            }
        }

        updateConditionValue($item);
    }

    /**
     * Initialise every term-dropdown condition row after the popup content is
     * inserted into the DOM (Shop Product Archive and Shop Single Product).
     */
    function initProductArchiveConditionRows() {
        var part = getParameterByName('part');
        if (part !== 'shopproduct' && part !== 'singleproduct') {
            return;
        }
        $('#edit-conditions-container .condition-item').each(function () {
            initProductArchiveConditionRow($(this));
        });
    }

    /**
     * Convert the Include/Exclude and condition-type selects of a row to
     * Select2 so every dropdown in the condition form shares the same styling
     * (pill box, caret, and dropdown panel) as the searchable term dropdown.
     *
     * @param {jQuery} $item The .condition-item row.
     */
    function initConditionSelect2s($item) {
        if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.select2 !== 'function') {
            return;
        }

        var $exclude = $item.find('.condition-exclude-include-select');
        var $type = $item.find('.condition-select').first();

        [$exclude, $type].forEach(function ($sel) {
            if (!$sel.length || $sel.data('select2')) {
                return;
            }
            $sel.select2({
                dropdownParent: $item,
                minimumResultsForSearch: Infinity
            });
        });
    }

    function generateOptionsHtml(selectedValue, part) {
        if (typeof part === 'undefined' || part === null || part === '') {
            part = getParameterByName('part');
        }

        if (part === 'shopproduct') {
            return generateProductArchiveOptionsHtml(selectedValue || '');
        }

        if (part === 'singleproduct') {
            return generateSingleProductOptionsHtml(selectedValue || '');
        }

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
                        <option value="special-shop" ${selectedValue === 'special-shop' ? 'selected' : ''}>Shop Page</option>
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
            conditionsContainer.append(solaceBuildConditionItem(condition, index));
        });

        $('#edit-conditions-container').append(conditionsContainer);
        $('#edit-conditions-container .condition-item').each(function () {
            initConditionSelect2s($(this));
        });
        initProductArchiveConditionRows();
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
        const allowedTypes = ['header', 'footer', 'singleproduct', 'shopproduct', 'purchase-summary', 'blogsinglepost', 'blogarchive', '404', 'cart', 'checkout', 'myaccount'];

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
                    } else if (part === 'cart' || part === 'checkout' || part === 'myaccount') {
                        // Their widgets live in solace-extra-woocommerce; the
                        // cart and checkout categories are registered but empty.
                        editUrl += '&solace-extra-woocommerce=1';
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

        // The markup is id="new-conditions-container"; targeting it as a class
        // matched nothing, so the popup kept whatever rows were left from last time.
        $('#add-new-popup #new-conditions-container').html(`
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

        // Apply Select2 to the default row so the Add New popup dropdowns
        // match the Edit Conditions popup styling on every part (header,
        // footer, shop product archive, shop single product).
        initConditionSelect2s($('#add-new-popup #new-conditions-container .condition-item'));

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
        // $('#add-new-popup #new-conditions-container').html(`
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
        var hasInvalidTerm = false;

        $container.find('.condition-item').each(function(index, item) {
            var $item = $(item);
            var conditionType = $item.find('.condition-exclude-include-select').val();
            var conditionValue = solaceGetConditionRowValue($item);

            if (!solaceValidateConditionValue(conditionValue)) {
                hasInvalidTerm = true;
                return false;
            }

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

        if (hasInvalidTerm) {
            alert('Please choose a brand, category, or tag for the condition.');
            return;
        }

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
                    redirectToPartEditor(postId, part);
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
    
    /**
     * Send the user into Elementor for a freshly created part.
     *
     * This used to be reached through a second AJAX call to save_edit_conditions,
     * which rewrote the conditions save_conditions had already written one call
     * earlier. Only the redirect was ever needed.
     *
     * @param {number} postId
     * @param {string} part
     */
    function redirectToPartEditor(postId, part) {
        var editUrl = solaceSitebuilderParams.edit_url
            .replace('{post_id}', postId)
            .concat('&post_type=solace-sitebuilder&action=elementor&solace-extra=1&part=' + part);

        window.location.href = editUrl;
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
        var hasInvalidTerm = false;

        $container.find('.condition-item').each(function(index, item) {
            var $item = $(item);
            var conditionType = $item.find('.condition-exclude-include-select').val();
            var conditionValue = solaceGetConditionRowValue($item);

            if (!solaceValidateConditionValue(conditionValue)) {
                hasInvalidTerm = true;
                return false;
            }

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

        if (hasInvalidTerm) {
            alert('Please choose a brand, category, or tag for the condition.');
            return;
        }

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
        var hasInvalidTerm = false;

        $container.find('.condition-item').each(function(index, item) {
            var $item = $(item);
            var conditionType = $item.find('.condition-exclude-include-select').val();
            var conditionValue = solaceGetConditionRowValue($item);

            if (!solaceValidateConditionValue(conditionValue)) {
                hasInvalidTerm = true;
                return false;
            }

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

        if (hasInvalidTerm) {
            alert('Please choose a brand, category, or tag for the condition.');
            return;
        }

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
                    redirectToPartEditor(postId, part);
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
        if (conditionsContainer.length === 0) {
            return;
        }
        var conditionCount = conditionsContainer.find('.condition-item').length + 1;
        var part = getParameterByName('part');
        var conditionItem = solaceBuildConditionItem({ type: 'include', value: '' }, conditionCount);
        conditionsContainer.append(conditionItem);
        initConditionSelect2s(conditionItem);

        if (part === 'shopproduct' || part === 'singleproduct') {
            initProductArchiveConditionRow(conditionItem);
        }
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
        initConditionSelect2s(conditionItem);
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
        initConditionSelect2s(conditionItem);
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

        // Replace the title text with an input field, save button, and cancel button.
        //
        // Built with the DOM API, never by interpolating into an HTML string. The
        // title is stored content: .text() hands back the decoded characters, so a
        // title such as `"><img src=x onerror=...>` placed inside value="${...}"
        // closed the attribute and ran script in the browser of whoever clicked
        // Rename -- an administrator. .val() and .attr() set properties and
        // attributes directly, so nothing here is ever parsed as markup.
        const $input = $('<input>', {
            type: 'text',
            'class': 'rename-input',
            style: 'border-radius: 8px; border: 1px solid #f1f1f1;'
        }).val(currentTitle);

        const $saveButton = $('<button>', {
            'class': 'button save-rename',
            style: 'margin-left: 10px; padding: 0 20px;',
            text: 'Save'
        }).attr('data-post-id', postId);

        const $cancelButton = $('<button>', {
            'class': 'button cancel-rename',
            style: 'margin-left: 5px; padding: 0 20px;',
            text: 'Cancel'
        });

        $titleColumn.empty().append($input, $saveButton, $cancelButton);

        // Focus the input after it has been added to the DOM
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
                    const $titleCol = $saveBtn.closest('.title-column');

                    // Cart/Checkout/My Account: the page address under the title
                    // follows the slug the rename gave that page.
                    const pageUrl = response.data && response.data.page_url ? response.data.page_url : '';
                    if (pageUrl) {
                        const $url = $titleCol.siblings('.solace-part-url');
                        const shown = pageUrl.replace(/^https?:\/\//, '');
                        if ($url.is('a')) {
                            $url.attr('href', pageUrl).text(shown + ' \u2197');
                        } else if ($url.length) {
                            // Not live: plain text, no link.
                            $url.text(shown);
                        }
                    }

                    $titleCol
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
                    redirectToPartEditor(postId, part);
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

        // Check whether there is a control for background-color.
        if (typeof elementor !== 'undefined' && elementor.settings && elementor.settings.controls) {
            console.log("Elementor settings controls found");

            // Ensure the button background color control exists.
            if (elementor.settings.controls.button_background_color) {
                elementor.settings.controls.button_background_color.on('change', function(newColor) {
                    console.log("New background color from color picker: ", newColor);

                    // Apply the new color to the .woocommerce ul.products li.product .button element.
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
