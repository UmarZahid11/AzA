$(function () {
    $("#menu").slicknav();
});

document.addEventListener("DOMContentLoaded", function () {
    // Get all paragraph elements with the class "clickable"
    const paragraphs = document.querySelectorAll("p.clickable");
    // Get the input element
    const input = document.getElementById("myInput");

    // Loop through each paragraph
    paragraphs.forEach((paragraph) => {
        // Add a click event listener to each paragraph
        paragraph.addEventListener("click", function () {
            // Set the value of the input field to the paragraph's text
            input.value = paragraph.textContent;
        });
    });
});

$(".load-more>a").click(function (e) {
    e.preventDefault();

    $(this).parent().addClass("loadImg");

    setTimeout(function () {
        $(".additional_job").removeClass("d-none");
        $(".load-more").addClass("d-none");
    }, 1000);
});

$(".test-sli").slick({
    dots: true,

    infinite: false,

    speed: 300,

    autoplay: true,

    slidesToShow: 3,

    slidesToScroll: 1,

    responsive: [
        {
            breakpoint: 900,

            settings: {
                slidesToShow: 2,

                slidesToScroll: 1,
            },
        },

        {
            breakpoint: 650,

            settings: {
                slidesToShow: 1,

                slidesToScroll: 1,
            },
        },
    ],
});

$(".succes-slider").slick({
    dots: false,

    arrows: true,

    infinite: true,

    speed: 300,

    slidesToShow: 3,

    slidesToScroll: 1,

    responsive: [
        {
            breakpoint: 1024,

            settings: {
                slidesToShow: 3,

                slidesToScroll: 1,
            },
        },

        {
            breakpoint: 600,

            settings: {
                slidesToShow: 2,

                slidesToScroll: 1,
            },
        },

        {
            breakpoint: 480,

            settings: {
                slidesToShow: 1,

                slidesToScroll: 1,
            },
        },
    ],
});

// blogslider start

$(".partner-slider").slick({
    lazyLoad: "progressive",

    dots: false,

    arrows: false,

    infinite: true,

    speed: 3000,

    cssEase: "Linear",

    autoplay: true,

    autoplaySpeed: 100,

    slidesToShow: 6,

    slidesToScroll: 1,

    responsive: [
        {
            breakpoint: 1024,

            settings: {
                slidesToShow: 5,

                slidesToScroll: 1,
            },
        },

        {
            breakpoint: 600,

            settings: {
                slidesToShow: 3,

                slidesToScroll: 1,
            },
        },

        {
            breakpoint: 480,

            settings: {
                slidesToShow: 2,

                slidesToScroll: 1,
            },
        },
    ],
});

// blogslider end

// product slider jas start

$(".slider-for").slick({
    slidesToShow: 1,

    slidesToScroll: 1,

    arrows: false,

    fade: true,

    asNavFor: ".slider-nav",
});

$(".slider-nav").slick({
    slidesToShow: 3,

    slidesToScroll: 1,

    asNavFor: ".slider-for",

    dots: true,

    centerMode: true,

    focusOnSelect: true,
});

// product slider jas end

// simple slick slider start

$(".regular").slick({
    dots: true,

    infinite: true,

    speed: 300,

    autoplay: true,

    slidesToShow: 3,

    slidesToScroll: 3,
});

// simple slick slider end

// wow animation js

$(function () {
    new WOW().init();
});

// responsive menu js

$(function () {
    $("#menu").slicknav();
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

(function () {
    const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

    //I'm adding this section so I don't have to keep updating this pen every year :-)
    //remove this if you don't need it
    let today = new Date(),
        dd = String(today.getDate()).padStart(2, "0"),
        mm = String(today.getMonth() + 1).padStart(2, "0"),
        yyyy = today.getFullYear(),
        nextYear = yyyy + 1,
        dayMonth = "08/1/",
        birthday = dayMonth + yyyy;

    today = mm + "/" + dd + "/" + yyyy;
    if (today > birthday) {
        birthday = dayMonth + nextYear;
    }
    //end

    const countDown = new Date(birthday).getTime(),
        x = setInterval(function () {
            const now = new Date().getTime(),
                distance = countDown - now;

            if (document.getElementById("days") != null)
                document.getElementById("days").innerText =
                    Math.floor(distance / day) ?? "";

            if (document.getElementById("hours") != null)
                document.getElementById("hours").innerText =
                    Math.floor((distance % day) / hour) ?? "";

            if (document.getElementById("minutes") != null)
                document.getElementById("minutes").innerText =
                    Math.floor((distance % hour) / minute) ?? "";

            // document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached

            //seconds
        }, 0);
})();

$(document).ready(function () {
    $("select").addClass("form-select");
});

$(
    '<p class="typewrite form-title" data-period="1500" data-type=\'["DONATION","DONATE TODAY","MAKE DONATE"]\'><span class="wrap">DONATE TODAY</span></p>'
).insertAfter(".give-form-title");
window.onload = function () {
    var elements = document.getElementsByClassName("typewrite");
    for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute("data-type");
        var period = elements[i].getAttribute("data-period");
        if (toRotate) {
            new TxtType(elements[i], JSON.parse(toRotate), period);
        }
    }
    // INJECT CSS
    var css = document.createElement("style");
    css.type = "text/css";
    css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #FFFFFF}";
    document.body.appendChild(css);
};

$(document).ready(function () {
    class TypeWriter {
        constructor(element, phrases, period) {
            this.element = $(element);
            this.phrases = phrases;
            this.period = parseInt(period, 10);
            this.txt = "";
            this.phraseIndex = 0;
            this.isDeleting = false;
            this.tick();
        }

        tick() {
            const currentPhrase = this.phrases[this.phraseIndex];
            this.txt = this.isDeleting
                ? currentPhrase.substring(0, this.txt.length - 1)
                : currentPhrase.substring(0, this.txt.length + 1);

            this.element.find(".wrap").text(this.txt);

            let delta = 200 - Math.random() * 100;
            if (this.isDeleting) {
                delta /= 2;
            }

            if (!this.isDeleting && this.txt === currentPhrase) {
                delta = this.period;
                this.isDeleting = true;
            } else if (this.isDeleting && this.txt === "") {
                this.isDeleting = false;
                this.phraseIndex = (this.phraseIndex + 1) % this.phrases.length;
                delta = 500;
            }

            setTimeout(() => this.tick(), delta);
        }
    }

    const elements = $(".typewrite");
    elements.each(function () {
        const element = $(this);
        const phrases = JSON.parse(element.attr("data-type"));
        const period = element.attr("data-period");
        new TypeWriter(this, phrases, period);
    });
});

$(document).ready(function () {
    // Handle click event on donation level buttons
    $(".give-donation-level-btn").click(function () {
        const value = $(this).attr("value"); // Get the value attribute of the clicked button

        // Check if the value is 'custom'
        if (value === "custom") {
            // Clear the input or handle custom amount separately
            $("#give-amount").val("");
            $("#give-amount").focus(); // Optionally focus on the input field for user to enter custom amount
        } else {
            // Update the input field with the value of the clicked button
            $("#give-amount").val(value);
        }
    });
});

$(".counter").each(function () {
    $(this)
        .prop("Counter", 0)
        .animate(
            {
                Counter: $(this).text(),
            },
            {
                duration: 3000,
                easing: "swing",
                step: function (now) {
                    $(this).text(Math.ceil(now));
                },
            }
        );
});
