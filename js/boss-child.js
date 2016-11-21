(function($) {

  var joinleave_group_change_handler = function() {
    // if the join/leave group button was clicked and ajax call is over (no spinner),
    // refresh the page so that we see the success message & email settings
    if (
      $(this).children().length > 0 &&
      $(this).find('a[class$=-group]').length > 0 &&
      $(this).find('.fa-spin').length === 0
    ) {
      // we really want to see #message, but the top bar covers it so aim a little higher
      window.location.replace(window.location.pathname + window.location.search + '#main');
      // unless we reload, browser simply scrolls up to the anchor.
      // we want to see the email options so refresh everything.
      window.location.reload();
    }
  };

  $(document).ready(function(){

    // we need live() to affect pages of groups loaded via ajax.
    $('#groups-dir-list .group-button').live('DOMSubtreeModified', joinleave_group_change_handler);

    // groups directory does not run a new query if "back" button was clicked due to browser cache, so force refresh
    // (without this, results on page can be from the wrong tab despite which is "selected")
    if ($('#groups-dir-list').length > 0) {
      $('.item-list-tabs .selected a').trigger('click');
    }

    // disable this since it breaks in safari and isn't really useful anyway
    $.fn.jRMenuMore = function () {}

    $('form#hc-terms-acceptance-form input[type=submit][name=hc_accept_terms_continue]').on('click', function(){
            if ( $('form#hc-terms-acceptance-form input[type=checkbox][name=hc_accept_terms]').is(':checked') ) {
                    $('#hc-terms-acceptance-form').submit();
            } else {
                    alert('Please agree to the terms by checking the box next to "I agree".');
            }
    });

  });

})(jQuery);
