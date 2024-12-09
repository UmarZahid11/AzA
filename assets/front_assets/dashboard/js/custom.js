$(function () {
    $('#menu').slicknav();
});

if($(window).width() < 1200){
    $('body').removeClass('side-bar-active')
    $('table.style-1').each(function(){
        let clone = $(this).clone();
        $('<div class="table-responsive"></div>').insertAfter($(this));
        $(this).next().html(clone);
        $(this).hide();
    })
}

// notification
$('body').on('click', '.notification-wrap > a', function (e) {
    e.preventDefault();
    var a = $(this).attr('open-box');
    if (a == "true") {
        $(this).attr('open-box', 'false');
        $(this).parent().removeClass('show-now');

        if ($(this).attr('id') == 'top-notification-anchor') {
            
            AjaxRequest.asyncRequest(base_url + 'dashboard/custom/seen_notification', {}, false).then(
                function(response) {
                    if (response.status) {
                        $("#top-notification").load(location.href + " #top-notification>*", function () {
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    }
                }
            )
        }
        if ($(this).attr('id') == 'top-message-anchor') {

            AjaxRequest.asyncRequest(base_url + 'dashboard/custom/seen_message', {}, false).then(
                function(response) {
                    if (response.status) {
                        $("#top-message").load(location.href + " #top-message>*", function () {
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    }
                }
            )
        }
    } else if (a == "false") {
        $('.notification-wrap > a').attr('open-box', 'false');
        $('.notification-wrap > a').parent().removeClass('show-now');
        
        $(this).attr('open-box', 'true');
        $(this).parent().addClass('show-now');
    }
});

$('body').on('click', function (e) {
    if (($(e.target).parent().attr('id') != 'top-notification-anchor' && $(e.target).parent().attr('id') != 'top-message-anchor') && ($('#top-notification-anchor').attr('open-box') == 'true' || $('#top-message-anchor').attr('open-box') == 'true') && $('.notification-wrap').hasClass('show-now')) {
        $('.notification-wrap').removeClass('show-now')
        $('.notification-wrap > a').attr('open-box', 'false')
    }
})

// Language
$('.language-slct>a').click(function (e) {
    e.preventDefault();
    $(this).next().toggleClass('open');
})

$('.profile-opt>a').click(function (e) {
    e.preventDefault();
    $(this).next().toggleClass('open');
});
$('.menu-toggle-btn').click(function (e) {
    e.preventDefault();
    $('body').toggleClass('side-bar-active');
})

// blogslider start
$('.blogslid').slick({
    dots: true,
    arrows: true,
    infinite: false,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 4,
    responsive: [{
        breakpoint: 1024,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: true
        }
    },
    {
        breakpoint: 600,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 2
        }
    },
    {
        breakpoint: 480,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    }]
});
// blogslider end

// product slider jas start
$('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav'
});
$('.slider-nav').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    dots: true,
    centerMode: true,
    focusOnSelect: true
});
// product slider jas end

// simple slick slider start
$(".regular").slick({
    dots: true,
    infinite: true,
    speed: 300,
    autoplay: true,
    slidesToShow: 3,
    slidesToScroll: 3
});
// simple slick slider end

// wow animation js
$(function () {
    new WOW().init();
});


// responsive menu js
$(function () {
    $('#menu').slicknav();
});

// slick slider in tabs js start
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace("active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += "active";
}

// slick slider in tabs js end
var str = location.href.toLowerCase();
$(".side-bar-menu>a").each(function () {
    if (str.indexOf(this.href.toLowerCase()) > -1) {
        $(".side-bar-menu>a").removeClass("active");
        $(this).addClass("active");
    }
});

$('.main-navigation-menu>li>ul>li>a').each(function () {
    if((str.localeCompare(this.href) == 0)) {
        console.log('match for: ' + str)
        $(this).parent().parent().parent().find('.side-caret-anchor').trigger('click')
        $(this).addClass('active')
        $(this).find('.title').addClass('text-white')
    }
})


$(".test-sli").slick({

    dots: true,
    infinite: false,
    arrows: false,
    speed: 300,
    autoplay: true,
    slidesToShow: 2.5,
    slidesToScroll: 1,

    responsive: [{
        breakpoint: 900,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 650,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    }]
});


$('.succes-slider').slick({

    dots: false,
    arrows: true,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,

    responsive: [{
        breakpoint: 1024,
        settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
        }
    },
    {
        breakpoint: 600,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 1
        }
    },
    {
        breakpoint: 480,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    }]
});
