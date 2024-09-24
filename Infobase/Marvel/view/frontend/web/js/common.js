require(['jquery', 'slick'], function($) {
    $(document).ready(function() {
        $("#comics-carousel").slick({
            centerMode: true,
            centerPadding: '60px',
            infinite: true,
            slidesToShow: 3,
            arrows: true,
            prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
            nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
            responsive: [
              {
                breakpoint: 768,
                settings: {
                  slidesToShow: 1,
                  infinite: true,
                  arrows: true,
                  slidesToScroll: 1,
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow: 1,
                  infinite: true,
                  arrows: true,
                  slidesToScroll: 1,
                }
              }
            ]
        });

        $("#series-carousel").slick({
            slidesToShow: 3,
            infinite: true,
            centerMode: true,
            centerPadding: '60px',
            responsive: [
              {
                breakpoint: 768,
                settings: {
                  slidesToShow: 1,
                  arrows: false,
                  infinite: true,
                  slidesToScroll: 1,
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow: 1,
                  arrows: false,
                  infinite: true,
                  slidesToScroll: 1,
                }
              }
            ]
        });

        $("#stories-carousel").slick({
            slidesToShow: 1,
            infinite: true,
            arrows: true,
            slidesToScroll: 1,
            responsive: [
              {
                breakpoint: 768,
                settings: {
                  slidesToShow: 1,
                  arrows: false,
                  infinite: true,
                  slidesToScroll: 1,
                }
              },
              {
                breakpoint: 480,
                settings: {
                  slidesToShow: 1,
                  arrows: false,
                  infinite: true,
                  slidesToScroll: 1,
                }
              }
            ]
        });
    });
});