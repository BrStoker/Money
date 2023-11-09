(function ($) {
    "use strict";
    // variables
    var header = $(".header"),
        layout = $('.layout');
    // preloader
    preloader();
    function preloader() {
        layout.on("click", ".nav__link", function (event) {
            layout.removeClass('layout_ready-load');
            event.preventDefault();
            var linkLocation = this.href;
            setTimeout(function () {
                window.location = linkLocation;
            }, 500);
        });
        setTimeout(function () {
            layout.addClass('layout_ready-load');
        }, 0);
    }
    // setTimeout(function () {
    // $('.masonry__list').masonry({
    //     // options
    //     itemSelector: '.masonry__item',
    //     masonry: {
    //         percentPosition: true,
    //         columnWidth: '.masonry__item',
    //     }
    // });
    // }, 1000);



    // Sliders init
    // Single slider init
    if ($(".slider").length) {
        $(".slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
            speed: 1000,
            fade: true,
            dots: true,
            prevArrow: '<div class="slick-prev"><svg  class="arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 7H3.83L9.42 1.41L8 0L0 8L8 16L9.41 14.59L3.83 9H16V7Z" fill="#021533"/></svg></div>',
            nextArrow: '<div class="slick-next"><svg class="arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 0L6.59 1.41L12.17 7H0V9H12.17L6.59 14.59L8 16L16 8L8 0Z" fill="#021533"/></svg></div>',
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        arrows: false,
                        dots: true
                    }
                }
            ]
        });
    }
    // Menu
    navInit();
    function navInit() {
        header.find(".burger").on("click", function () {
            $(this).closest(header).toggleClass("header_menu-active");
        });
    }

    // Submenu
    dropDownInit();
    function dropDownInit() {
        header.find(".nav__ico").on("click", function () {
            $(this).closest(".nav__item_dropdown").toggleClass("nav__item_active");
        });
    }

    /// Scroll functions
    $(window).on('load resize scroll', function () {
        let h = $(".layout__main").height();
        scrollHeader(h);
        scrollSection(h);
        scrollImage(h);
    });
    $(".layout__main").on('load resize scroll', function () {
        let h = $(".layout__main").height();
        scrollHeader(h);
        scrollSection(h);
        scrollImage(h);
    });
    function scrollHeader(h){
        if ($(".layout__main").scrollTop() >= 1) {
            header.addClass('site-header_animation');
        } else {
            header.removeClass('site-header_animation');
        }
    }
    function scrollSection(h){
        let section = $(".section");
        section.each(function () {
            if (($(".layout__main").scrollTop() + h) >= $(this).offset().top) {
                $(this).addClass("section_animation");
            }
        });
    }
    function scrollImage(h){
        // Image initialization
        let img = $("img");

        img.each(function () {
            if (($(".layout__main").scrollTop() + h + h) >= $(this).offset().top && this.getAttribute('data-src') && this.src !== this.getAttribute('data-src')) {
                this.src = this.getAttribute('data-src');
            }
        });
    }


    // Tabs init
    if ($(".tabs").length) {
        tabsInit();
    }

    function tabsInit() {
        let position,
            tabsActive = ("tabs__item_active");
        $('.tabs__header').on("click", ".tabs__item", function () {
            position = $(this).index();
            $(this).addClass(tabsActive).siblings().removeClass(tabsActive);
            console.log(position);
            $(this).closest(".tabs").find(">.tabs__body").find(">.tabs__list").find(">.tabs__item").eq(position).addClass(tabsActive).siblings().removeClass(tabsActive);
        });

    }

    // Validation & customize form
    if ($("form").length) {

        // select init
        jcf.setOptions('Select', {
            wrapNative: false,
            wrapNativeOnMobile: false,
            fakeDropInBody: false,
            maxVisibleItems: 5

        });
        jcf.replaceAll();
    }




    // Scroll
    linkScroll();
    function linkScroll() {
        $('a[href^="#"]:not([href="#"])').click(function (e) {
            e.preventDefault();
            var target = $($(this).attr('href'));
            if (target.length) {
                var scrollTo = target.offset().top;
                $('body, html').animate({scrollTop: scrollTo + 'px'}, 800);
            }
        });
    }


    if ($(".avatar").length) {
        avatar()
    }

    function avatar() {
        let attach = $('.avatar'),
            attachInput = attach.find(".avatar__input"),
            attachImage = attach.find(".avatar__img");

        attach.on("click", ".avatar", function () {
            attachInput.click();
        });
        attach.on("change", attachInput, function () {
            if (
                attachInput.prop('files')[0].type.match('image.*') &&
                attachInput.prop('files')[0].type.match('image.*').indexOf('image.*') === -1) {
                attachImage.attr("src", URL.createObjectURL(attachInput.prop('files')[0]));
            } else {
                attachImage.attr("src", "../img/content/avatar/avatar.png");
            }
        });
    }
    if ($(".box").length) {
        boxFunctions()
    }
    function boxFunctions() {
        $(".box").on('click', '.actions__preview', function () {
            $(this).closest(".actions__item").toggleClass("actions__item_active");
        });
    }

    // subsection__modal-init
    if ($(".section__subsection_modal").length) {
        subsectionModal()
    }
    function subsectionModal() {
        $(".layout__section").on('click', '.subsection__modal-init', function () {
            $(this).closest(".layout__section").find(".section__subsection_modal").addClass("modal_active");
        });
        $(".section__subsection").on('click', '.modal__remove', function () {
            $(this).closest(".section__subsection_modal").removeClass("modal_active");
        });
    }


    // MODAL INIT
    modalInit();
    function modalInit() {
        let modalName;
        // modal show
        $(document).on('click', '.modal-init', function () {
            layout.addClass("layout_modal-active").find(".modal__layout").removeClass("modal__layout_active");
            modalName = $(this).data("modalname");
            layout.find("." + modalName + "").addClass("modal__layout_active");
        });
        // modal hide
        $(document).mouseup(function (e) {
            if ($(".modal__main.active").length) {
                var div = $(".modal__main");
                if (!div.is(e.target) && div.has(e.target).length === 0) {
                    modalHide();
                }
            }
        });
        // modal hide
        $(document).on('click', '.modal__action, .modal__remove', function () {
            modalHide();
        });
        // modal hide
        $(window).keydown(function(e){
            if (e.key === "Escape") {
                modalHide();
            }
        });

        function modalHide() {
            layout.removeClass("layout_modal-active").find(".modal__main").removeClass("modal__layout_active");
        }
    }

    /* Calendar */
    if ($(".datepicker").length) {
        datepickerInit();
    }
    function datepickerInit() {
        /* Датапикер */
        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            currentText: 'Сегодня',
            prevText: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M13 4L7 10L13 16" stroke="#7A828A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            nextText: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7 16L13 10L7 4" stroke="#7A828A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
            dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
            dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            weekHeader: 'Не',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);
        /* Датапикер Одиночный*/
        $(".datepicker").datepicker({
            showOtherMonths: true,
            minDate: 0
        });

        /* Датапикер период*/
        $(".datepicker_range").datepicker({
            onSelect: function (selectedDate) {
                if (!$(this).data().datepicker.first) {
                    $(this).data().datepicker.inline = true;
                    $(this).data().datepicker.first = selectedDate;
                } else {
                    if (selectedDate > $(this).data().datepicker.first) {
                        $(this).val($(this).data().datepicker.first + " - " + selectedDate);
                    } else {
                        $(this).val(selectedDate + " - " + $(this).data().datepicker.first);
                    }
                    $(this).data().datepicker.inline = false;
                }
            },
            onClose: function () {
                delete $(this).data().datepicker.first;
                $(this).data().datepicker.inline = false;
            },
        });
    }

    // STRUCTURE
    if ($(".structure").length) {
        structureToggle()
    }
    function structureToggle() {
        $(".structure").on('click', '.structure__action', function () {
            $(this).closest(".structure__item").toggleClass("structure__item_active");
        });
    }

    // CHAT
    $(window).on('load resize scroll', function () {
        if ($(".chats__dialog").length) {
            chat();
        }
    });
    function chat() {
        if($(window).width() > 780) {
            $(".chats__dialog").css("height", $(window).height() - $(".chats__dialog").offset().top - $(".layout__footer").outerHeight() - 32);
        }
    }
})(jQuery);