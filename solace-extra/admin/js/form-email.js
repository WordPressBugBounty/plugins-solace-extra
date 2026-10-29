// Scope wizard storage to the current site: on multisite (subdirectory) all subsites share one
// origin, so unscoped keys and path=/ cookies leak between parallel imports on different subsites.
window.solaceStorageKey = window.solaceStorageKey || function (key) {
	return key + '@' + ( window.location.pathname.split( '/wp-admin' )[0] || '/' );
};
window.solaceCookiePath = window.solaceCookiePath || function () {
	return ( window.location.pathname.split( '/wp-admin' )[0] || '' ) + '/wp-admin/';
};

(function ($) {
    'use strict';

    // Get paramter in URL.
    function getParameterByName(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(window.location.search);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }    

    // Button submit.
    $('#submit-button').on('click', function () {
        const firstname = $('.firstname').val();
        const email = $('.email').val();
        const agreement = $('#agreement').is(':checked') ? 1 : 0;
        const listId = 'EWbwOPUKmvf0mfrH6W8o7w';
        const apiKey = 'UrMQxMjGtuRSR9RCfUYr';

        if ( firstname.length === 0 || email.length === 0 || agreement === 0 ) {
            swal({
                title: "Error!",
                text: "Please complete all fields in the form.",
                icon: "error"
            });
        } else {
            sendDataToSendy(firstname, email, agreement, listId, apiKey);
            localStorage.setItem(solaceStorageKey('solaceStep6'), 'success');

            const demoType = getParameterByName('type');
            const demoUrl = getParameterByName('url');
            const demoName = getParameterByName('demo');

            // Function to get the current time
            function getCurrentTime() {
                var now = new Date();
                return now.toLocaleString(); // Using local date and time format
            }

            if (localStorage.getItem(solaceStorageKey('solaceDuration'))) {
                // If it exists, remove it first
                localStorage.removeItem(solaceStorageKey('solaceDuration'));
            }

            // Get the time when the button is clicked
            var currentTime = getCurrentTime();

            // Save the time value to local storage with the name 'solaceDuration'
            localStorage.setItem(solaceStorageKey('solaceDuration'), currentTime);

            // Check if the 'solaceListDemo' key already exists in local storage
            if (localStorage.getItem(solaceStorageKey('solaceRemoveDataDemo'))) {
                // Retrieve the existing value
                let existingValue = localStorage.getItem(solaceStorageKey('solaceRemoveDataDemo'));

                // Combine the existing value with the new data, separated by a comma
                let combinedValue = existingValue + ',' + demoName;

                // Save the combined value back to local storage
                localStorage.setItem(solaceStorageKey('solaceRemoveDataDemo'), combinedValue);
            } else {
                // If 'solaceRemoveDataDemo' doesn't exist in local storage, create it and store the new data
                localStorage.setItem(solaceStorageKey('solaceRemoveDataDemo'), demoName);
            }                

            window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();            
        }

    });

    // Skip this step.
    $('button.skip-this-step').on('click', function () {

        const demoType = getParameterByName('type');
        const demoUrl = getParameterByName('url');
        const demoName = getParameterByName('demo');

        window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();         
    });

    // Skip no thanks with expiration date and created date
    $('button.skip-no-thanks').on('click', function () {
        const expirationDays = 7;
        const now = new Date();
        const expirationDate = new Date(now.getTime() + expirationDays * 24 * 60 * 60 * 1000);
        
        const data = {
            created: now.toISOString(),
            expiry: expirationDate.getTime(),
            value: 'success'
        };

        localStorage.setItem(solaceStorageKey('solace-skip-no-thanks'), JSON.stringify(data));

        const demoType = getParameterByName('type');
        const demoUrl = getParameterByName('url');
        const demoName = getParameterByName('demo');        

        window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();  
    });

    function sendDataToSendy(firstname, email, agreement, listId, apiKey) {
        const demoType = getParameterByName('type');
        const demoUrl = getParameterByName('url');
        const demoName = getParameterByName('demo');
        var sendy_url = 'https://mailist.detheme.com/subscribe';

        var data = {
            name: firstname,
            email: email,
            list: listId,
            api_key: apiKey
        };

        $.ajax({
            type: 'POST',
            url: sendy_url,
            data: data,
            success: function (response) {
                if (response == '1') {
                    window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();
                } else {
                    window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&err=1&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();
                }
            },
            error: function (xhr, status, error) {
                window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&err=1&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();
                // console.error(xhr.responseText);
            }
        });
    }

    // function sendDataToSendy(firstname, email, agreement, listId, apiKey) {
    //     var sendy_url = 'https://mailist.detheme.com/subscribe';

    //     var data = {
    //         name: firstname,
    //         email: email,
    //         list: listId, 
    //         api_key: apiKey 
    //     };

    //     $.ajax({
    //         type: 'POST',
    //         url: sendy_url,
    //         data: data,
    //         success: function(response) {
    //             if (response == '1') {
    //                 window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress';
    //             } else {
    //                 window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&err=1';
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('AJAX error:', error);
    //             window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&err=1';
    //         }
    //     });
    // }

    function checkFormValidity() {
        const firstname = $('.firstname').val().trim();
        const email = $('.email').val().trim();
        const agreementChecked = $('#agreement').is(':checked');

        if (firstname !== '' && email !== '' && agreementChecked) {
            $('#submit-button').prop('disabled', false);
        } else {
            $('#submit-button').prop('disabled', true);
        }
    }

    $(document).ready(function () {
        checkFormValidity();

        $('.firstname, .email, #agreement').on('input change', function () {
            checkFormValidity();
        });
    });

})( jQuery );
