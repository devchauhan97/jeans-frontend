
  $menuLeft = $('.pushmenu-left');
  $nav_list = $('#nav_list');

$(document).ready(function() {
   
  $nav_list.click(function() {
  $(this).toggleClass('change');
    $('.pushmenu-push').toggleClass('pushmenu-push-toright');
    $menuLeft.toggleClass('pushmenu-open');
  });



// customer-logos slider

$(document).ready(function(){
  $('.customer-logos').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 1000,
    arrows: false,
    dots: false,
    pauseOnHover: false,
    responsive: [{
      breakpoint: 768,
      settings: {
        slidesToShow: 4
      }
    }, {
      breakpoint: 520,
      settings: {
        slidesToShow: 3
      }
    }]
  });
});


