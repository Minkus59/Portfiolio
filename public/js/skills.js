$(document).ready(function() {
    $(document).scroll(function(event) {
      var topPos = $(this).scrollTop() + 500;
      var windowHeight = $(this).height();
      var docHeight = $(document).height();
  
      if (topPos >= $('.progress').position().top) {
        $('.progress >.progress-bar').animate({
            'max-width': '100%'
          }, 3000)
          .attr('aria-valuenow', 100)
          .find('span.title').text('100%');
      } else if (topPos < $('.progress').position().top) {
        $('.progress >.progress-bar').css({
            'max-width': '0%'
          })
          .attr('aria-valuenow', 0)
          .find('span.title').text('0%');
      }
    });
  })