jQuery(document).ready(function($) {
    $("#hide_login_as_box").click(function() {
        $("#loginas_user_customer").hide();
        setTimeout(function() {
                $("#loginas_user_customer").show();
            },
            5000);
    });

    $("#logout_login_as").on("click", function(event) {
        event.preventDefault();
		if (typeof loginas_ajax_object == 'undefined') {console.log("not valid action"); return;}
		$.ajax(console.log(loginas_ajax_object.login_as_nonce));
        $.ajax({
            url: loginas_ajax_object.ajax_url,
            type: 'post',
            data: {
                'action': 'loginas_return_admin',
				'login_as_nonce': loginas_ajax_object.login_as_nonce, // Include nonce in the request
            },
            success: function(response) {
				console.log("response = " + response);
				//return;
                if (localStorage.getItem('login_as_back_to') != '' && response == "loginas_return_admin_done") {
					var login_as_back_to = localStorage.getItem('login_as_back_to').replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");

                    window.location.replace(login_as_back_to);
                }
            },
        });
    });
});


/*
jQuery(document).ready(function($) {
    $("#hide_login_as_box").click(function() {
        $("#loginas_user_customer").hide();
        setTimeout(function() {
            $("#loginas_user_customer").show();
        }, 5000);
    });

    $("#logout_login_as").on("click", function(event) {
        event.preventDefault();

        // Add a nonce to the AJAX request
        var login_as_nonce = loginas_ajax_object.nonce;

        $.ajax({
            url: loginas_ajax_object.ajax_url,
            type: 'post', // Use POST request for security
            data: {
                'action': 'loginas_return_admin',
                'login_as_nonce': login_as_nonce, // Include nonce in the request
            },
            success: function(response) {
                if (localStorage.getItem('login_as_back_to') != '') {
                    var login_as_back_to = localStorage.getItem('login_as_back_to').replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
                    window.location.replace(login_as_back_to);
                } else {
                    window.location.replace(loginas_ajax_object.home_url);
                }
            },
        });
    });
});
*/