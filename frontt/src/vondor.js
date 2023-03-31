import './assets/vendor/jquery/dist/jquery.min.js';
import './assets/vendor/jquery-migrate/dist/jquery-migrate.min.js';
// import './assets/vendor/popper.js/dist/umd/popper.min.js';
import './assets/vendor/bootstrap/bootstrap.min.js';
// import './assets/vendor/appear.js';
import './assets/vendor/jquery.countdown.min.js';
import './assets/vendor/hs-megamenu/src/hs.megamenu.js';
import './assets/vendor/svg-injector/dist/svg-injector.min.js';
import './assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js';
import './assets/vendor/jquery-validation/dist/jquery.validate.min.js';
import './assets/vendor/fancybox/jquery.fancybox.min.js';
// import './assets/vendor/typed.js/lib/typed.min.js';
import './assets/vendor/slick-carousel/slick/slick.js';
import './assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js';
import './assets/js/hs.core.js';
import './assets/js/components/hs.countdown.js';
import './assets/js/components/hs.header.js';
import './assets/js/components/hs.hamburgers.js';
import './assets/js/components/hs.unfold.js';
import './assets/js/components/hs.focus-state.js';
import './assets/js/components/hs.malihu-scrollbar.js';
import './assets/js/components/hs.validation.js';
import './assets/js/components/hs.fancybox.js';
import './assets/js/components/hs.onscroll-animation.js';
import './assets/js/components/hs.slick-carousel.js';
import './assets/js/components/hs.show-animation.js';
import './assets/js/components/hs.svg-injector.js';
import './assets/js/components/hs.go-to.js';
import './assets/js/components/hs.selectpicker.js';
$(window).on('load', function () {
    // initialization of HSMegaMenu component
    $('.js-mega-menu').HSMegaMenu({
        event: 'hover',
        direction: 'horizontal',
        pageContainer: $('.container'),
        breakpoint: 767.98,
        hideTimeOut: 0
    });
    // initialization of svg injector module
    $.HSCore.components.HSSVGIngector.init('.js-svg-injector');
});

$(document).on('ready', function () {
    // initialization of header
    $.HSCore.components.HSHeader.init($('#header'));

    // initialization of animation
    $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');

    // initialization of unfold component
    $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
        afterOpen: function () {
            $(this).find('input[type="search"]').focus();
        }
    });

    // initialization of popups
    $.HSCore.components.HSFancyBox.init('.js-fancybox');

    // initialization of countdowns
    var countdowns = $.HSCore.components.HSCountdown.init('.js-countdown', {
        yearsElSelector: '.js-cd-years',
        monthsElSelector: '.js-cd-months',
        daysElSelector: '.js-cd-days',
        hoursElSelector: '.js-cd-hours',
        minutesElSelector: '.js-cd-minutes',
        secondsElSelector: '.js-cd-seconds'
    });

    // initialization of malihu scrollbar
    $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

    // initialization of forms
    $.HSCore.components.HSFocusState.init();

    // initialization of form validation
    $.HSCore.components.HSValidation.init('.js-validate', {
        rules: {
            confirmPassword: {
                equalTo: '#signupPassword'
            }
        }
    });

    // initialization of show animations
    $.HSCore.components.HSShowAnimation.init('.js-animation-link');

    // initialization of fancybox
    $.HSCore.components.HSFancyBox.init('.js-fancybox');

    // initialization of slick carousel
    $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

    // initialization of go to
    $.HSCore.components.HSGoTo.init('.js-go-to');

    // initialization of hamburgers
    $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

    // initialization of unfold component
    $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
        beforeClose: function () {
            $('#hamburgerTrigger').removeClass('is-active');
        },
        afterClose: function() {
            $('#headerSidebarList .collapse.show').collapse('hide');
        }
    });

    $('#headerSidebarList [data-toggle="collapse"]').on('click', function (e) {
        e.preventDefault();

        var target = $(this).data('target');

        if($(this).attr('aria-expanded') === "true") {
            $(target).collapse('hide');
        } else {
            $(target).collapse('show');
        }
    });

    // initialization of unfold component
    $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

    // initialization of select picker
    $.HSCore.components.HSSelectPicker.init('.js-select');
});
