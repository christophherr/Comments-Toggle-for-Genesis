jQuery(function( $ ){
  $(".show-comments").click(function(){
      $(this).next(".entry-comments").slideToggle();
      $(this).html(function(i, text) {
        return text === 'Show Comments' ? 'Hide Comments' : 'Show Comments';
      });
    });
});
