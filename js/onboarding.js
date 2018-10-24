/* global jQuery, swal */
(function($) {

  $(window).load(function() {
    function neuUsernamePopup (title, text, icon) {
      swal({
        title              : title ? title : 'Welcome to Northeastern Commons!',
        text               : text ? text : 'Please create a username to use on the commons.',
        content            : 'input',
        closeOnClickOutside: false,
        closeOnEsc         : false,
        icon               : icon,
        button             : {
          text: "Save",
          closeModal: false
        }
      })
        .then(name => {
          wp.ajax.send( "neu_update_username", {
            success: neuUsernameSuccess,
            error: neuUsernameError,
            data: {
              username: name
            }
          });
        });

    }

    function neuUsernameSuccess (data) {
      swal( 'Success!', 'Your username was successfully set to "' + data + '". This page will now reload', 'success' );
      window.location.reload(true);
    }

    function neuUsernameError (data) {
      if ( data.retry ) {
        neuUsernamePopup('Please try again', data.message, 'error');
      } else {
        swal( "Something went wrong", data.message, "error" );
      }
    }

    neuUsernamePopup();
  });

})(jQuery);
