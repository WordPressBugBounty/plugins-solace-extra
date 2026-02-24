(function($){
    $(window).on('elementor:init', function () {
        
        $(document).on('click', '#custom-preview-apply', function (e) {
            e.preventDefault();

            const previewproduct_id = elementor.settings.page.model.get('preview_settings_product');
            const product_id = elementor.config.document.id;
            // const selectedText = $('#elementor-control-preview_settings_product') .find('select[data-setting="preview_settings_product"] option:selected').text();

            $.post(ajaxurl, {
                action: 'solace_save_custom_preview_id',
                product_id: product_id,
                previewproduct_id: previewproduct_id,
            }, function(response) {
                if (response.success) {
                    if (typeof elementor.reloadPreview === 'function') {
                        elementor.reloadPreview();
                    } else {
                        location.reload(); // fallback
                    }
                }
            });
        });


    });
})(jQuery);

(function($){
    $(window).on('elementor:init', function () {
        
        $(document).on('click', '#custom-preview-apply', function (e) {
            e.preventDefault();

            const previewIdpostId = elementor.settings.page.model.get('preview_settings_post');
            const postId = elementor.config.document.id;
            // console.log('previewIdpostId'+previewIdpostId);
            // console.log('postId'+postId);

            $.post(ajaxurl, {
                action: 'solace_save_custom_preview_id',
                post_id: postId,
                previewpostId: previewIdpostId,
            }, function(response) {
                if (response.success) {
                    if (typeof elementor.reloadPreview === 'function') {
                        elementor.reloadPreview();
                    } else {
                        location.reload(); // fallback
                    }
                }
            });
        });


    });
})(jQuery);

(function($){
    $(window).on('elementor:init', function () {
        $(document).on('click', '#category-blog-preview-apply', function (e) {
            e.preventDefault();

            const categoryId = elementor.settings.page.model.get('preview_settings_category');
            const postId = elementor.config.document.id;

            $.post(ajaxurl, {
                action: 'solace_save_blogarchive_preview_category',
                post_id: postId,
                preview_blog_category: categoryId,
            }, function(response) {
                if (response.success) {
                    if (typeof elementor.reloadPreview === 'function') {
                        elementor.reloadPreview();
                    } else {
                        location.reload(); // fallback
                    }
                }
            });
        });
    });
})(jQuery);

(function($){
    $(window).on('elementor:init', function () {
        $(document).on('click', '#category-shop-preview-apply', function (e) {
            e.preventDefault();

            const categoryId = elementor.settings.page.model.get('preview_settings_product_category');
            const postId = elementor.config.document.id;

            $.post(ajaxurl, {
                action: 'solace_save_shopproduct_preview_category',
                post_id: postId,
                preview_shop_category: categoryId,
            }, function(response) {
                if (response.success) {
                    if (typeof elementor.reloadPreview === 'function') {
                        elementor.reloadPreview();
                    } else {
                        location.reload(); // fallback
                    }
                }
            });
        });
    });
})(jQuery);
