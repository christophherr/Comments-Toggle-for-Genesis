jQuery(function( $ ){
  $(".show-comments").click(function(){
      $(this).attr('aria-pressed', function(change_aria_presssed, state) {
        return state === 'false' ? 'true' : 'false';
      });
      $(this).html(function(change_button_text, text) {
        return text === 'Show Comments' ? 'Hide Comments' : 'Show Comments';
      });
      $(this).next(".entry-comments").slideToggle('slow', function() {
        $(this).attr('aria-expanded', function(change_aria_expanded, val) {
          return val === 'false' ? 'true' : 'false';
        });
      });
  });
  $(".entry-comments-link").click(function() {
      $(".show-comments").get(0).scrollIntoView();
  });
});
