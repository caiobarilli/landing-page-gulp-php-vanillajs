/**
 * File main.js.
 */

( function( $ ) {

  "use strict";

  // Smooth scrolling using jQuery easing
  $('a.scroll-trigger[href*="#"]:not([href="#tabs"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top - 65)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 85
  });

  // Collapse Navbar Desktop
  var navbarCollapse = function() {
    
    if ($("#mainNav").offset().top > 630) {
      $('#logo').attr('src','assets/img/logo.png');
      $('#mainNav').addClass('white-nav');

    } else {
      $('#logo').attr('src','assets/img/logo-white.png');
      $('#mainNav').removeClass('white-nav');

    };

  };

  // Collapse now if page is not at top
  navbarCollapse();

  // Collapse the navbar when page is scrolled
  $(window).scroll(navbarCollapse);

})(jQuery);