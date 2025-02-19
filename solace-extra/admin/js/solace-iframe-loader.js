document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.getElementById('solaceIframe');

    function postMessageToIframe(data) {
        // iframe.contentWindow.postMessage(data, 'https://solacewp.com'); 
        iframe.contentWindow.postMessage(data, '*'); 
        // iframe.contentWindow.postMessage(data, 'https://stagging-solace.djavaweb.com'); 
    }

    // Mengambil semua tombol yang mengubah warna
    const changeStylesButtons = document.querySelectorAll('.change-styles-btn');
    changeStylesButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const colorstyles = button.getAttribute('data-styles');
            console.log(colorstyles);
            const data = { type: 'styles', value: colorstyles };
            postMessageToIframe(data);
        });
    });

    // Mengambil semua tombol yang mengubah font
    const changeFontStylesButtons = document.querySelectorAll('.change-font-styles-btn');
    changeFontStylesButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Hapus kelas 'active' dari semua tombol
            changeFontStylesButtons.forEach(btn => btn.classList.remove('active'));

            // Tambahkan kelas 'active' ke tombol yang diklik
            button.classList.add('active');
            const fontstyles = button.getAttribute('data-font-styles');
            console.log(fontstyles);
            const data = { type: 'fontStyles', value: fontstyles };
            postMessageToIframe(data);
        });
    });



    // Mengirim gaya default ketika iframe pertama kali dimuat
    iframe.onload = function() {
        const defaultStyles = '';
        postMessageToIframe({ type: 'styles', value: defaultStyles });
    };
});


jQuery(document).ready(function($) {
    let mediaUploader;

    function clearStoredStyles() {
        localStorage.removeItem('appliedStyles');
        localStorage.removeItem('appliedFontStyles');
        document.body.style.cssText = ''; // Reset style di elemen body
        console.log('Stored styles cleared from LocalStorage');
    }

    const iframex = document.getElementById('solaceIframe');

    function postMessageToIframex(data) {
        // Mengirim pesan ke iframe dengan data yang ditentukan
        iframex.contentWindow.postMessage(data, 'https://solacewp.com'); // Sesuaikan URL sesuai dengan domain iframe
        // iframe.contentWindow.postMessage(data, 'https://stagging-solace.djavaweb.com'); // Sesuaikan URL sesuai dengan domain iframe
        console.log('Post message sent to iframe:', data);

    }

    clearStoredStyles();
    $(".logo_default").on("click", function(e) {
        const defa = "";
        const data = { type: 'defaultImageSrc', value: defa };
        postMessageToIframex(data);
        console.log('Default logo button clicked, message sent:', data);
        $(".palette-buttons img.logo").css("display", "none");
        $(".palette-buttons .logo_default").css("display", "none");
        localStorage.removeItem('solace_step5_logo');
 
        var currentDate = new Date();
        currentDate.setTime(currentDate.getTime() - (7 * 24 * 60 * 60 * 1000)); 
        document.cookie = "solace_step5_logoid=; expires=" + currentDate.toUTCString() + "; path=/";

        if (document.cookie.indexOf('solace_step5_logoid') === -1) {
            // alert('Cookie solace_step5_logoid berhasil dihapus!');
        } else {
            // alert('Cookie solace_step5_logoid masih ada!');
        }
    });

    // // // DELETE ALL TAB- FROM LIVE
    // const defax = "";
    // const datax = { type: 'deleteLocal', value: defax };

    // postMessageToIframex(datax);
    setTimeout(() => {
        postMessageToIframex(datax);
    }, 5000); // 3000 milidetik = 3 detik
    
    var attachment_id;  


    // $('#upload-media-button').click(function(e) {
    //     e.preventDefault();
    //     console.log('Upload Media button clicked!'); // Debugging log

    //     // Jika media uploader sudah ada, buka kembali.
    //     if (mediaUploader) {
    //         console.log('Opening existing media uploader...');
    //         mediaUploader.open();
    //         return;
    //     }

    //     console.log('Creating new media uploader...');
    //     // Buat instance baru wp.media
    //     mediaUploader = wp.media({
    //         title: 'Choose Image',
    //         button: {
    //             text: 'Choose Image'
    //         },
    //         multiple: false
    //     });

    //     // Ketika sebuah gambar dipilih, dapatkan URL-nya
    //     mediaUploader.on('select', function() {
    //         const attachment = mediaUploader.state().get('selection').first().toJSON();
    //         const imageUrl = attachment.url;
    //         attachment_id = attachment.id;

    //         console.log('Selected image URL:', imageUrl); // Debugging log

    //         $(".logo").attr("src",imageUrl);
    //         $(".logo_default").css("display", "flex");
    //         $(".logo-buttons input[type='submit']").val("Change Your Logo");
    //         $(".palette-buttons img.logo").css("display", "block");

    //         // Kirim URL gambar ke iframe untuk memperbarui src
    //         const iframe = document.getElementById('solaceIframe');
    //         iframe.contentWindow.postMessage({
    //             action: 'updateImageSrc',
    //             url: imageUrl
    //         }, '*'); // Ganti '*' dengan 'https://solacewp.com' untuk keamanan
    //     });

    //     // Buka uploader media
    //     mediaUploader.open();
    // });

    $('#upload-media-button').click(function(e) {
        e.preventDefault();

        var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            console.log("Upload filenya:");
            console.log(uploaded_image);
            attachment_id = uploaded_image.id;
            // alert(attachment_id);
            var image_url = uploaded_image.toJSON().url;
            $(".logo").attr("src",image_url);
            $(".logo_default").css("display", "flex");
            $(".logo-buttons input[type='submit']").val("Change Your Logo");
            $(".palette-buttons img.logo").css("display", "block");

            if (attachment_id){
                setLogoURL(attachment_id);
                console.log('setlogourl');
            }

            // Kirim URL gambar ke iframe untuk memperbarui src
            const iframe = document.getElementById('solaceIframe');
            iframe.contentWindow.postMessage({
                action: 'updateImageSrc',
                url: image_url
            }, '*'); // Ganti '*' dengan 'https://solacewp.com' untuk keamanan

            
        });
    });

    

    function setLogoURL(logo_url) {
        // alert(logo_url);
        // AJAX request to save the logo URL
        var logoString = logo_url;
        localStorage.setItem('solace_step5_logo', logoString);
        // var logoValue = localStorage.getItem('solace_step5_logo');
        // document.cookie = "solace_step5_logo=; expires=" + currentDate.toUTCString() + "; path=/";
        document.cookie = "solace_step5_logoid=" + (logoString || "") + "; path=/";
                
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_logo2',
                logo_url: logo_url
            },
            success: function(response) {
                console.log('Logo URL updated successfully.');
            },
            error: function(error) {
                console.error('Error updating logo URL:', error);
            }
        });
    }

    // Tangani postMessage di dalam iframe
    window.addEventListener('message', function(event) {
        // Hanya jika pengirim adalah sumber yang sah
        // if (event.origin === 'https://localhost') { // Ganti dengan domain asal yang benar
            const message = event.data;
            if (message.action === 'updateImageSrc' && message.url) {
                console.log('Updating image src in iframe to:', message.url); // Debugging log
                // Hapus elemen gambar lama
                const oldLogo = document.querySelector('.site-logo .brand img.solace-site-logo');
                if (oldLogo) {
                    oldLogo.remove();
                }

            }
        // }
    }, false);
});


// window.addEventListener('load', function() {
//     const iframe = document.getElementById('solaceIframe');

//     function adjustIframeScale() {
//         if (iframe.contentWindow) {
//             const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
//             iframe.style.transform = "scale(1)";

//             if (window.innerWidth < 1024) {
//                 const scaleFactor = window.innerWidth / 1024;
//                 iframe.style.transform = `scale(${scaleFactor})`;
//             }
//         }
//     }

//     iframe.onload = adjustIframeScale;
//     window.addEventListener('resize', adjustIframeScale);
// });
