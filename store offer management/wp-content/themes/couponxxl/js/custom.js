jQuery(document).ready(function ($) {
    "use strict";

    /* Hf TABLES UNTIL THEY ARE FULLY LOADED */
    $(window).load(function () {
        $('.pretable-loading').hide();
        $('.bt-table').show();
    });

    $.fn.hasAttr = function (name) {
        return this.attr(name) !== undefined;
    };


    /* NAVIGATION */
    function sticky_nav() {
        var $admin = $('#wpadminbar');
        if ($admin.length > 0 && $admin.css('position') == 'fixed') {
            $sticky_nav.css('top', $admin.height());
        }
        else {
            $sticky_nav.css('top', '0');
        }
    }

    if ($('.navigation').length > 0 && $('.navigation').data('enable_sticky') == 'yes') {
        var $navigation_bar = $('.navigation');
        var $sticky_nav = $navigation_bar.clone().addClass('sticky-nav');
        $('body').append($sticky_nav);


        $(window).on('scroll', function () {
            sticky_nav()
            if ($(window).scrollTop() >= $navigation_bar.position().top + $navigation_bar.outerHeight(true) && $(window).width() > 769) {
                $sticky_nav.show();
            }
            else {
                $sticky_nav.hide();
            }
        });
        sticky_nav();
    }

    function handle_navigation() {
        if ($(window).width() >= 767) {
            $(document).on('mouseover', 'ul.nav li.dropdown, ul.nav li.dropdown-submenu', function () {
                var $this = $(this);
                if (!$this.hasClass('open')) {
                    $this.addClass('open').find(' > .dropdown-menu').stop(true, true).hide().show();
                }
            });
            $(document).on('mouseout', 'ul.nav li.dropdown, ul.nav li.dropdown-submenu', function () {
                var $this = $(this);
                setTimeout(function () {
                    if (!$this.is(":hover") && $this.hasClass('open')) {
                        $this.removeClass('open').find(' > .dropdown-menu').stop(true, true).show().hide();
                    }
                }, 200);

            });
        }
        else {
            $('ul.nav li.dropdown, ul.nav li.dropdown-submenu').unbind('mouseenter mouseleave');
        }

        if ($(window).width() >= 767) {
            $(document).on('mouseover', 'ul.nav li.mega_menu_li, ul.mega_menu', function () {
                $(this).addClass('open').find(' > .mega_menu').stop(true, true).hide().show();
            });
            $(document).on('mouseout', 'ul.nav li.mega_menu_li, ul.mega_menu', function () {
                $(this).removeClass('open').find(' > .mega_menu').stop(true, true).show().hide();
            });
        }
        else {
            $('ul.nav li.mega_menu_li, ul.mega_menu').unbind('mouseenter mouseleave');
            $(document).on('click', 'ul.nav li.mega_menu_li', function () {
                $(this).find('.mega_menu').slideToggle();
            });
        }
    }

    handle_navigation();

    $(document).on('click', 'a[data-toggle="dropdown"]', function () {
        if ($(this).attr('href').indexOf('http') > -1) {
            window.location.href = $(this).attr('href');
        }
    });

    $(window).resize(function () {
        setTimeout(function () {
            handle_navigation();
        }, 200);
    });

    /* ADD BUTTON CLASS */
    $('input#submit').addClass('btn');

    /* SUBMIT FORMS */
    $(document).on('click', '.form-submit', function () {
        $(this).parents('form').submit();
    });

    /* FEATURED SLIDER */
    function start_featured_sliders() {
        var $sliders = $('.featured-slider-wrap:not(.started)');
        if ($sliders.length > 0) {
            $sliders.each(function () {
                var $this = $(this);
                var $slider = $this.find('.featured-slider');
                $this.addClass('started');
                $this.find('.featured-slider-loader').hide();
                $slider.show();
                $slider.owlCarousel({
                    items: 1,
                    autoplayTimeout: $slider.data('slider_speed') ? $('.featured-slider').data('slider_speed') : 4000,
                    autoplay: $slider.data('slider_auto_rotate') == 'yes' ? true : false,
                    dots: false,
                    nav: $slider.find('li').length > 1 ? true : false,
                    pause: true,
                    navText: ['<i class="pline-angle-left"></i>', '<i class="pline-angle-right"></i>'],
                });
            });
        }
    }

    $(window).load(function () {
        start_featured_sliders()
    });

    /* CATEGORY SHORTCODE SLIDER */
    $('.category-shortcode-slider').each(function () {
        var $this = $(this);
        if ($this.parents('.col-md-12').length !== 0) {
            $this.parents('.category-shortcode').addClass('category-slider-two-columns');
        }
        $this.owlCarousel({
            items: 1,
            responsive: {
                1200: {
                    items: $this.parents('.col-md-12').length == 0 ? 1 : 2
                }
            }
        });
    });

    /* FEATURED SLIDER */
    $('.home-featured-slider').owlCarousel({
        nav: $('.home-featured-slider li').length > 0 ? true : false,
        navText: ['<i class="pline-angle-left"></i>', '<i class="pline-angle-right"></i>'],
        responsive: {
            900: {
                items: 3
            },
            600: {
                items: 2
            },
            0: {
                items: 1
            }
        }
    });

    /* LOCATIONS SHORTCODE SLIDER */
    $('.locations-shortcode-slider').each(function () {
        var $this = $(this);
        var items = $this.parents('.col-md-12').length == 0 ? 3 : 4
        if ($this.parent().find('.location-map-image').length == 0) {
            items += 1
        }
        $this.owlCarousel({
            items: 1,
            responsive: {
                1200: {
                    items: items
                },
                900: {
                    items: 3
                },
                500: {
                    items: 2
                },
                0: {
                    items: 1
                }
            }
        });
    });

    /* OWL THUMBS */
    $('.slider-thumbs').owlCarousel({
        responsive: {
            0: {
                items: 2
            },
            500: {
                items: 3
            },
            800: {
                items: 4
            }
        },
        margin: 20
    });

    $('.deal-img-slider').owlCarousel({
        items: 1,
        mouseDrag: false,
        nav: $('.deal-img-slider li').length == 1 ? false : true,
        navText: ['<i class="pline-angle-left"></i>', '<i class="pline-angle-right"></i>'],
        dots: false,
        animateOut: 'fadeOut'
        //autoHeight: true,
    });

    $(document).on('click', '.slider-thumbs a', function (e) {
        e.preventDefault();
        var index = $(this).parents('.owl-item').index();
        $('.deal-img-slider').trigger('to.owl.carousel', [index, 0, true]);
    });

    /* COUNTDOWN */
    function start_countdowns() {
        var $countdown = $('.deal-countdown:not(.started)');
        if ($countdown.length > 0) {
            $countdown.each(function () {
                $(this).addClass('started');
                $(this).kkcountdown({
                    dayText: $countdown.data('single'),
                    daysText: $countdown.data('multiple'),
                    displayZeroDays: true,
                    rusNumbers: false
                });
            });
        }
    }

    start_countdowns();

    /* GOOGLE MAPS */
    var map, infoWindow;

    function start_cluster_inspection(cluster) {
        var content = '<ul class="list-unstyled info-box-markers-list">';
        var markers = cluster.getMarkers();
        var addedMarkers = [];
        for (var i = 0; i < markers.length; i++) {
            if (addedMarkers.indexOf(markers[i].id) == -1) {
                content += '<li>' + markers[i].infoWindow + '</li>';
                addedMarkers.push(markers[i].id);
            }
        }
        content += '</ul>';
        closeInfoWindow();
        infoWindow.setContent(content);
        infoWindow.open(map, markers[markers.length - 1]);
        infoWindow.setOptions({
            pixelOffset: new google.maps.Size(0, 0),
        });
        infoWindow.setPosition(cluster.getCenter());

        updateInfoBoxScrollData();
    }

    function updateInfoBoxScrollData() {
        if ($('.infoBox').length > 0) {
            $(document).off('mousewheel', '.infoBox');
            var infoBoxHeight = $('.infoBox').height(),
                infoBoxscrollHeight = $('.infoBox').get(0).scrollHeight;
            $(document).on('mousewheel', '.infoBox', function (event) {
                var blockScrolling = this.scrollTop >= infoBoxscrollHeight - infoBoxHeight && event.originalEvent.wheelDelta < 0 || this.scrollTop === 0 && event.originalEvent.wheelDelta > 0;
                return !blockScrolling;
            });
        }
    }

    function closeInfoWindow() {
        infoWindow.close();
    }

    function start_map() {
        var $markers = $('.markers');
        var dealMap = false;

        var isMobile = $(window).width() <= 1024 ? true : false;

        if ($markers.hasClass('deal-map')) {
            dealMap = true;
        }
        if ($markers.length > 0 && $markers.html() !== '') {
            var markersArray = [];
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {mapTypeId: google.maps.MapTypeId.ROADMAP, scrollwheel: false};
            var markersJSON = JSON.parse($markers.html().trim());
            map = new google.maps.Map(document.getElementById("map-markers"), mapOptions);
            infoWindow = new InfoBox({
                content: '',
                maxWidth: 460,
                alignBottom: false,
                enableEventPropagation: true,
                disableAutoPan: true
            });
            if (markersJSON.length > 0) {
                for (var i = 0; i < markersJSON.length; i++) {
                    for (var p = 0; p < markersJSON[i].locations.length; p++) {
                        var latLng = new google.maps.LatLng(markersJSON[i].locations[p].latitude, markersJSON[i].locations[p].longitude);
                        var marker = new google.maps.Marker({
                            position: latLng,
                            infoWindow: markersJSON[i].windowHTML,
                            icon: couponxxl_data.marker_icon,
                            id: i
                        });

                        bounds.extend(latLng);

                        if (!dealMap) {
                            marker.addListener(isMobile ? 'click' : 'mouseover', function (marker) {
                                if (isMobile) {
                                    closeInfoWindow();
                                }
                                if ($('.infoBox').length == 0) {
                                    infoWindow.setContent(this.infoWindow);
                                    infoWindow.open(map, this);
                                    infoWindow.setOptions({
                                        pixelOffset: new google.maps.Size(-15, -5),
                                    });
                                }
                            });
                            if (!isMobile) {
                                marker.addListener('mouseout', function (cluster) {
                                    if ($('.infoBox').length > 0 && !$('.infoBox').is(':hover')) {
                                        closeInfoWindow();
                                    }
                                });
                            }
                        }

                        markersArray.push(marker);
                    }
                }

                map.fitBounds(bounds);

                google.maps.event.addListener(infoWindow, "closeclick", function () {
                    markerCluster.setZoomOnClick(true);
                });

                google.maps.event.addListener(infoWindow, "infoWindowDrawn", function () {
                    updateInfoBoxScrollData();
                });

                var listener = google.maps.event.addListener(map, "idle", function () {
                    if (dealMap && couponxxl_data.deal_single_zoom) {
                        map.setZoom(parseInt(couponxxl_data.deal_single_zoom));
                    }
                    else if (couponxxl_data.listing_map_zoom) {
                        map.setZoom(parseInt(couponxxl_data.listing_map_zoom));
                    }
                    google.maps.event.removeListener(listener);
                });

                var markerCluster = new MarkerClusterer(map, markersArray, {
                    styles: [
                        {
                            height: 55,
                            url: couponxxl_data.url + "/images/m1.png",
                            width: 55,
                            textColor: '#ffffff'
                        },
                        {
                            height: 65,
                            url: couponxxl_data.url + "/images/m2.png",
                            width: 65,
                            textColor: '#ffffff'
                        },
                        {
                            height: 75,
                            url: couponxxl_data.url + "/images/m3.png",
                            width: 75,
                            textColor: '#ffffff'
                        },
                        {
                            height: 85,
                            url: couponxxl_data.url + "/images/m4.png",
                            width: 85,
                            textColor: '#ffffff'
                        },
                        {
                            height: 95,
                            url: couponxxl_data.url + "/images/m5.png",
                            width: 95,
                            textColor: '#ffffff'
                        }
                    ],
                    calculator: function (markers, numStyles) {
                        if (markers.length >= 12) return {text: markers.length, index: 5};
                        if (markers.length >= 9) return {text: markers.length, index: 4};
                        if (markers.length >= 6) return {text: markers.length, index: 3};
                        if (markers.length >= 3) return {text: markers.length, index: 2};
                        return {text: markers.length, index: 1};
                    }
                });

                if (!dealMap) {
                    if (!isMobile) {
                        google.maps.event.addListener(markerCluster, 'mouseover', function (cluster) {
                            if ($('.infoBox').length == 0) {
                                start_cluster_inspection(cluster);
                            }
                        });
                    }

                    google.maps.event.addListener(markerCluster, 'click', function (cluster) {
                        var markers = cluster.getMarkers();
                        closeInfoWindow();
                        var pos;
                        var allSame = true;
                        for (var i = 0; i < markers.length; i++) {
                            if (!pos) {
                                pos = markers[i].position;
                            }
                            else if (!pos.equals(markers[i].position)) {
                                allSame = false;
                            }
                        }
                        if (allSame) {
                            start_cluster_inspection(cluster);
                            markerCluster.setZoomOnClick(false);
                        }
                    });

                    if (!isMobile) {
                        google.maps.event.addListener(markerCluster, 'mouseout', function (cluster) {
                            setTimeout(function () {
                                if ($('.infoBox').length > 0 && !$('.infoBox').is(':hover')) {
                                    closeInfoWindow();
                                }
                            }, 200);
                        });
                        $(document).on('mouseleave', '.infoBox', function () {
                            closeInfoWindow();
                        });
                    }
                }
            }
        }
    }

    /* START BIG MAP ON HOME PAGE */
    if ($('.big-map').length > 0 || $('.deal-map').length > 0) {
        start_map();
    }

    /* SHOW CODE */
    function show_code_modal(offer_id) {
        $('.coupon-print-image').remove();
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'show_code',
                offer_id: offer_id,
            },
            dataType: "HTML",
            success: function (response) {
                $('#showCode .coupon_modal_content').html(response);
                $('body').append($('.coupon-print-image').clone());
                $('#showCode .coupon-print-image').remove();
                $('#showCode').modal('show');
                if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
                    prepare_copy();
                }
                else {
                    $('.coupon-code-modal.print').attr('href', $('.coupon-print-image').attr('src'));
                    $('.coupon-code-copied').hide();
                }
            }
        });
    }

    if (window.location.hash && window.location.hash.indexOf('cpn') > 0) {
        show_code_modal(window.location.hash.split('cpn-')[1]);
    }

    $(document).on('click', '.show-code', function (e) {
        var $this = $(this);
        var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
        if ($this.data('affiliate') != '' && !isSafari) {
            window.location.href = $this.data('affiliate');
        }
        else {
            e.preventDefault();
            var offer_id = $this.data('offer_id');
            show_code_modal(offer_id);
        }
    });

    ZeroClipboard.config({swfPath: couponxxl_data.url + "/js/ZeroClipboard.swf"});
    function prepare_copy() {
        var $code = $('.coupon-code-modal');
        if ($code.length > 0 && !$code.hasClass('print')) {
            var client = new ZeroClipboard($code);
            if (/MSIE|Trident/.test(window.navigator.userAgent)) {
                (function ($) {
                    var zcContainerId = ZeroClipboard.config('containerId');
                    $('#' + zcContainerId).on('focusin', false);
                })(window.jQuery);
            }
            client.on('ready', function (event) {
                client.on('copy', function (event) {
                    event.clipboardData.setData('text/plain', $code.val());
                });

                client.on('aftercopy', function (event) {
                    $('.coupon-code-copied').text($('.coupon-code-copied').data('aftertext'));
                });
            });

            client.on('error', function (event) {
                ZeroClipboard.destroy();
            });
        }
    }

    if ($('.copy-voucher').length) {
        $('.copy-voucher').each(function () {
            var $this = $(this);
            var client = new ZeroClipboard($this);
            if (/MSIE|Trident/.test(window.navigator.userAgent)) {
                (function ($) {
                    var zcContainerId = ZeroClipboard.config('containerId');
                    $('#' + zcContainerId).on('focusin', false);
                })(window.jQuery);
            }

            client.on('ready', function (event) {
                client.on('copy', function (event) {
                    event.clipboardData.setData('text/plain', $this.parents('td').find('.voucher-code').text());
                });

                client.on('aftercopy', function (event) {
                    $this.animate({opacity: 1}, 1000);
                });

                client.on('beforecopy', function (event) {
                    $this.css('opacity', '0.5');
                });
            });

            client.on('error', function (event) {
                ZeroClipboard.destroy();
            });
        });
    }

    /* SHOW OPTIONS FOR DIFFERENT COUPON TYPES */
    $(document).on('change', '#coupon_type', function () {
        $('.group_code_type').addClass('hidden');
        $('.group_' + $(this).val()).removeClass('hidden');
    });

    /* VALIDATE FORM */
    function validate_form($container) {
        var valid = true;
        $container.find('small.error').remove();
        $container.find('select, input, textarea').each(function () {
            var $$this = $(this);
            $$this.removeClass('error')
            if ($$this.hasAttr('data-validation') && ( $$this.is(':visible') || ( $$this.attr('type') == 'hidden' && $$this.parents('.input-group').is(':visible') ) )) {
                var validations = $$this.data('validation').split('|');
                for (var i = 0; i < validations.length; i++) {
                    switch (validations[i]) {
                        case 'length_conditional' :
                            if ($$this.val() !== '') {
                                var num = parseInt($($$this.data('field_number_val')).val());
                                if ($$this.val().split(/\r*\n/).length != num) {
                                    valid = false;
                                }
                            }
                            break;
                        case 'conditional' :
                            if ($$this.val() == '' && $('#' + $$this.data('conditional-field')).val() == '') {
                                valid = false;
                            }
                            break;
                        case 'required' :
                            if ($$this.val() == '') {
                                valid = false;
                            }
                            break;
                        case 'number' :
                            if (isNaN(parseInt($$this.val()))) {
                                valid = false;
                            }
                            break;
                        case 'email' :
                            if (!/\S+@\S+\.\S+/.test($$this.val())) {
                                valid = false;
                            }
                            break;
                        case 'match' :
                            if ($$this.val() !== $('input[name="' + $$this.data('match') + '"]').val()) {
                                valid = false;
                            }
                            break;
                        case 'checked' :
                            if (!$$this.prop('checked')) {
                                valid = false;
                            }
                            break;
                    }
                }
                if (!valid) {
                    if ($$this.attr('type') == 'checkbox') {
                        $$this.parent().before('<small class="no-margin error">' + $$this.data('error') + '</small><br />');
                    }
                    else {
                        $$this.before('<small class="error">' + $$this.data('error') + '</small>');
                    }
                }
            }
        });
        if ($container.find('#offer_description').length > 0) {
            var $desc_label = $('label[for="offer_description"]');
            $desc_label.parent().find('.error').remove();
            if (typeof tinyMCE !== 'undefined' && tinyMCE.get('offer_description')) {
                var tiny = tinyMCE.get('offer_description').getContent();
                var description = $('#offer_description').val(tiny);
            }
            else {
                var tiny = $('#offer_description').val();
            }
            if (tiny == '') {
                valid = false;
                $desc_label.after('<small class="error">' + $desc_label.data('error') + '</small>');
            }
        }
        return valid;
    }

    $(document).on('click', '.submit-form', function () {
        var $this = $(this);
        var $form = $this.parents('form');
        var $html = $this.html();
        if ($form.find('.tmce-active').length > 0) {
            var tiny_id = $form.find('.tmce-active textarea').attr('id');
            if (typeof tinyMCE !== 'undefined' && tinyMCE.get(tiny_id)) {
                var tiny = tinyMCE.get(tiny_id).getContent();
                $('#' + tiny_id).val(tiny);
            }
        }

        var can_submit = validate_form($form);

        if (can_submit) {
            if ($this.hasClass('register-form') || $this.hasClass('ajax-return')) {
                $this.append('<i class="fa fa-spin fa-spinner" style="margin-left: 10px;"></i>');
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: $this.parents('form').serialize(),
                    dataType: 'JSON',
                    success: function (response) {
                        $('.ajax-response').html(response.message);
                        if (response.url) {
                            window.location.href = response.url;
                        }
                        if (response.message.indexOf('alert-success') > -1 && $this.hasClass('register-form')) {
                            $form.after($form.find('.ajax-response').clone());
                            $this.parents('form').remove();
                        }
                    },
                    complete: function () {
                        $this.html($html);
                    }
                });
            }
            else {
                $form.submit();
            }
        }
        else {
            var error_message = $('.submit-form').data('form-error');
            if (typeof error_message !== 'undefined') {
                $('.submit-form').after('<small class="submit-form-error error"><br />' + $('.submit-form').data('form-error') + '</small>');
            }
        }
    });

    /* SWITCH TO RECOVER PASSWORD MODAL */
    $(document).on('click', '.recover-modal', function () {
        $('#login').on('hidden.bs.modal', function (e) {
            $('#recover').modal('show');
            $('#login').off('hidden.bs.modal');
        });
    });

    /* SEND CONTACT */
    $(document).on('click', '.submit-form-contact', function (e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            method: "POST",
            data: $(this).parents('form').serialize(),
            dataType: "JSON",
            success: function (response) {
                if (!response.error) {
                    $('.send_result').html('<div class="alert alert-success" role="alert"><span class="fa fa-check-circle"></span> ' + response.success + '</div>');
                }
                else {
                    $('.send_result').html('<div class="alert alert-danger" role="alert"><span class="fa fa-times-circle"></span> ' + response.error + '</div>');
                }
            }
        })
    });

    /* MAIL SHARE */
    $(document).on('click', '.mail-share', function () {
        var email = prompt(couponxxl_data.email_friend, "");
        if (email !== '') {
            window.location.href = "mailto:" + email + "?subject=" + $(this).data('subject') + "&body=" + $(this).data('body');
        }
        else {
            alert('Email is empty');
        }
    });


    /* DATES RANGE */
    function start_date_time_pickers() {
        if ($('#offer_start').length > 0) {
            $('#offer_start').datetimepicker({
                format: 'Y-m-d',
                onShow: function (ct) {
                    var start = $('#offer_expire').val();
                    var maxDate = false;
                    var minDate = false;
                    var range = $('#offer_start').data('range');
                    if (start !== '') {
                        var date = new Date(start);
                        date.setDate(date.getDate() - 1);
                        maxDate = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate();
                        if (range !== '') {
                            date.setDate(date.getDate() - range);
                            minDate = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate();
                        }
                    }
                    this.setOptions({
                        maxDate: maxDate,
                        minDate: minDate
                    });
                },
                timepicker: false
            });


            $('#offer_expire').datetimepicker({
                format: 'Y-m-d',
                onShow: function (ct) {
                    var start = $('#offer_start').val();
                    var maxDate = false;
                    var minDate = false;
                    var range = $('#offer_expire').data('range');
                    if (start !== '') {
                        var date = new Date(start);
                        date.setDate(date.getDate() + 1);
                        minDate = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate();
                        if (range !== '') {
                            date.setDate(date.getDate() + range);
                            maxDate = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate();
                        }
                    }

                    this.setOptions({
                        maxDate: maxDate,
                        minDate: minDate
                    });
                },
                timepicker: false
            });
        }
        if ($('#deal_voucher_expire').length > 0) {
            /* VOUCHER EXPIRE DATE */
            $('#deal_voucher_expire').datetimepicker({
                format: 'Y-m-d',
                onShow: function (ct) {
                    this.setOptions({
                        minDate: $('#deal_voucher_expire').data('min-date')
                    });
                },
                timepicker: false
            });
        }
    }


    /* MASONRY ITEMS */
    var $container = $('.masonry');
    var has_masonry = false;
    // initialize
    function start_masonry() {
        if ($(window).width() < 768 && has_masonry) {
            $container.masonry('destroy');
            has_masonry = false;
        }
        else if ($(window).width() >= 768 && !has_masonry) {
            $container.imagesLoaded(function () {
                $container.masonry({
                    itemSelector: '.masonry-item',
                    columnWidth: '.masonry-item',
                });
                has_masonry = true;
            });
        }
    }

    if ($container.length > 0) {
        start_masonry();
        $(window).resize(function () {
            setTimeout(function () {
                start_masonry();
            }, 500);
        });
    }

    /* EQUAL WIDGET HEIGHT FOR THE MEGAMENU */
    function is_ie() {

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            return true;
        }
        else {
            return false;
        }
    }

    if (is_ie()) {
        $('.mega_menu').width($('.nav.navbar-nav').width());
    }


    var $mega_menu = $('.mega_menu');
    var mega_menu_height = $mega_menu.outerHeight(true);

    function update_mega_menu() {
        if ($(window).width() > 768) {
            $mega_menu.height(mega_menu_height);
        }
        else {
            $mega_menu.height('auto');
        }
    }

    update_mega_menu();
    $(window).resize(function () {
        update_mega_menu();
    });


    /* CONTACT MAP */
    var $contact_map = $('.contact_map');
    if ($contact_map.length > 0) {
        var markers = [];
        $('.contact_map_marker').each(function () {
            var temp = $(this).val().split(',');
            markers.push({
                longitude: temp[0].trim(),
                latitude: temp[1].trim()
            })
        });
        var markersArray = [];
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            scrollwheel: $('.contact_map_scroll_zoom').length > 0 ? false : true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        var location;
        if (markers.length > 0) {
            for (var i = 0; i < markers.length; i++) {
                location = new google.maps.LatLng(markers[i].longitude, markers[i].latitude);
                bounds.extend(location);

                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
            }

            map.fitBounds(bounds);

            if (couponxxl_data.contact_map_max_zoom) {
                var listener = google.maps.event.addListener(map, "idle", function () {
                    map.setZoom(parseInt(couponxxl_data.contact_map_max_zoom));
                    google.maps.event.removeListener(listener);
                });
            }

        }
    }

    /* DEAL TYPE EXPLANATION */
    $(document).on('change', 'select[name="deal_type"]', function () {
        var $this = $(this);
        var val = $this.val();
        $('.shared_info').addClass('hidden');
        $('.not_shared_info').addClass('hidden');
        $('.' + val + '_info').removeClass('hidden');
        var deal_sale = $('input[name="deal_sale_price"]').val();
        var unit = $this.data('unit');
        var unit_position = $this.data('unit_position');
        var charged = '';
        if (val == 'shared') {
            var shared = $this.data('shared').toString();
            if (shared.indexOf('%') > -1) {
                shared = shared.replace('%', '');
                charged = ( parseInt(deal_sale) * parseInt(shared) ) / 100;
            }
            else {
                charged = parseInt(shared);
            }
            if (unit_position == 'front') {
                charged = unit + charged;
            }
            else {
                charged = charged + unit;
            }
            $('.shared_info .charged').html(charged);
        }
        else {
            var not_shared = $this.data('not_shared').toString();
            if (not_shared.indexOf('%') > -1) {
                not_shared = not_shared.replace('%', '');
                charged = ( parseInt(deal_sale) * parseInt(not_shared) ) / 100;
            }
            else {
                charged = parseInt(not_shared);
            }
            if (unit_position == 'front') {
                charged = unit + charged;
            }
            else {
                charged = charged + unit;
            }
            $('.not_shared_info .charged').html(charged);
        }
    });

    /* STEPS */
    var wizard = $("#wizard");
    if (wizard.length > 0) {
        wizard.steps({
            titleTemplate: '<span class="number">#index#</span> #title#',
            enableKeyNavigation: false,
            onStepChanging: function (event, currentIndex, newIndex) {
                if (currentIndex > newIndex) {
                    return true;
                }

                var $group = $('#wizard-p-' + currentIndex);
                var can_submit = validate_form($group);
                if (can_submit) {
                    return true;
                }
                else {
                    return false;
                }

            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                start_date_time_pickers();
            },
            onFinishing: function (event, currentIndex) {
                event.preventDefault();
                var $group = $('#wizard-p-' + currentIndex);
                var can_submit = validate_form($group);
                if (can_submit) {
                    return true;
                }
                else {
                    return false;
                }
            },
            onFinished: function () {
                var $form = $('#wizard').parents('form');
                var $finish = $('a[href="#finish"]');
                $finish.append('<i class="fa fa-spin fa-spinner" style="margin-left: 10px;"></i>');
                $finish.addClass('disabled');
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        $('.ajax-response').html(response);
                    },
                    complete: function () {
                        $finish.find('i').remove();
                    }
                });

            },
            labels: {
                cancel: couponxxl_data.steps_cancel,
                finish: couponxxl_data.steps_finish,
                next: couponxxl_data.steps_next,
                previous: couponxxl_data.steps_previous,
                loading: couponxxl_data.steps_loading,
            }
        });
    }


    /* SWITCH PAYMENTS */
    $(document).on('change', 'select[name="seller_payout_method"]', function () {
        $('.payout-connect').addClass('hidden');
        if ($(this).val() !== '') {
            $('.payout-' + $(this).val()).removeClass('hidden');
        }
    });


    /* AJAX SEARCH*/
    $(document).on('change', '.advanced-search input', function () {
        start_ajax_search();
        $.ajax({
            url: $('.advanced-search').attr('action'),
            data: $('.advanced-search').serialize(),
            success: function (response) {
                handle_ajax_search_response(response);
            },
            complete: function () {
                end_ajax_search();
            }
        });
    });

    $(document).on('click', '.category-filter a', function (e) {
        e.preventDefault();
        start_ajax_search();
        $('.offer_cat').val($(this).data('cat'));
        $.ajax({
            url: $('.advanced-search').attr('action'),
            data: $('.advanced-search').serialize(),
            success: function (response) {
                handle_ajax_search_response(response);
            },
            complete: function () {
                end_ajax_search();
            }
        });
    });

    $(document).on('change', '.modal-filter-wrap input', function () {
        var $this = $(this);
        var $visible = $('.advanced-search input[value="' + $this.val() + '"]');
        if ($visible.length) {
            $visible.prop('checked', $this.prop('checked'));
        }
    });
    $(document).on('click', '.modal-filter', function () {
        var $this = $(this);
        var filter = $this.data('filter');
        var $target = $('.filter-' + filter + '-modal');
        $('.modal-filter-wrap.' + filter + ' input').each(function () {
            var $$this = $(this);
            if ($$this.prop('checked')) {
                $target.append($$this.clone());
            }
        });
        $('.advanced-search input:first').trigger('change');
    });

    $(document).on('click', '.show-map', function () {
        $('.search-map').removeClass('hidden');
        start_map();
        $(this).addClass('hidden');
    });

    $(document).on('click', '.hide-map', function () {
        $('#map-markers').after('<div id="map-markers"></div>').remove();
        $('.show-map').removeClass('hidden');
        $('.search-map').addClass('hidden');
    });
    $(document).on('click', '.page-template-page-tpl_search .pagination a', function (e) {
        e.preventDefault();
        start_ajax_search();
        $.ajax({
            url: $(this).attr('href'),
            success: function (response) {
                handle_ajax_search_response(response);
            },
            complete: function () {
                end_ajax_search();
            }
        });
    });

    $(document).on('click', '.reset-search', function () {
        start_ajax_search();
        $.ajax({
            url: $(this).parents('form').attr('action'),
            success: function (response) {
                handle_ajax_search_response(response);
            },
            complete: function () {
                end_ajax_search();
            }
        });
    })

    function start_ajax_search() {
        $('.advanced-search .overlay').show();
    }

    function handle_ajax_search_response(response) {
        var $response = $('<div>' + response + '</div>');
        $('.ajax-results').html($response.find('.ajax-results').html());
        $('.ajax-sidebar').html($response.find('.ajax-sidebar').html());
        start_map();
        start_countdowns();
        start_featured_sliders();
    }

    function end_ajax_search() {
        $('.advanced-search .overlay').hide();
    }

    /* CART MANAGEMENET */
    var calling_cart = false;
    $(document).on('click', '.add-to-cart', function () {
        if (!calling_cart) {
            calling_cart = true;
            var $this = $(this);
            $this.addClass('disabled');
            var data = {
                action: 'update_cart',
                items: [$this.data('id')],
                cart_action: 'update'
            };
            data['qty_' + $this.data('id')] = 1;
            $.ajax({
                url: ajaxurl,
                data: data,
                method: 'POST',
                dataType: 'JSON',
                success: function (response) {
                    $('.count-items').html(response.small).removeClass('no-cart-items');
                    if (response.small == 0) {
                        $('.count-items').addClass('no-cart-items');
                    }
                    if (response.added_to_cart) {
                        if ($this.parent('.deal-single-add-to-cart').length == 0) {
                            if ($this.next().hasClass('view-cart')) {
                                $this.next().remove();
                            }
                            $this.after(response.added_to_cart);
                        }
                        $this.find('i').attr('class', 'pline-check');
                        $this.attr('title', couponxxl_data.in_cart_title);
                        $this.addClass('in-cart');
                    }
                },
                complete: function () {
                    calling_cart = false;
                    $this.removeClass('disabled');
                }
            });
        }
    });

    $(document).on('click', '.update-cart', function () {
        if (!calling_cart) {
            calling_cart = true;
            var $this = $(this);
            $this.addClass('disabled');
            $.ajax({
                url: ajaxurl,
                data: $('.cart-form').serialize(),
                method: 'POST',
                dataType: 'JSON',
                success: function (response) {
                    $('.count-items').html(response.small).removeClass('no-cart-items');
                    if (response.small == 0) {
                        $('.count-items').addClass('no-cart-items');
                    }
                    $('.ajax-cart-wrap').html(response.cart);
                },
                complete: function () {
                    calling_cart = false;
                    $this.removeClass('disabled');
                }
            });
        }
    });

    $(document).on('click', '.remove-item', function () {
        if (!calling_cart) {
            calling_cart = true;
            var $this = $(this);
            $this.addClass('disabled');
            var data = {
                action: 'update_cart',
                product_id: $this.data('id'),
                cart_action: 'delete',
                cart: 1
            };
            $.ajax({
                url: ajaxurl,
                data: data,
                method: 'POST',
                dataType: 'JSON',
                success: function (response) {
                    $('.count-items').html(response.small).removeClass('no-cart-items');
                    if (response.small == 0) {
                        $('.count-items').addClass('no-cart-items');
                    }
                    $('.ajax-cart-wrap').html(response.cart);
                },
                complete: function () {
                    calling_cart = false;
                    $this.removeClass('disabled');
                }
            });
        }
    });

    /* THUMBS UP AND THUMBS DOWN */
    $(document).on('click', '.thumb-rate', function () {
        var $this = $(this);
        if ($this.hasClass('can-rate')) {
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'thumb_rate',
                    offer_id: $(this).data('offer_id'),
                    value: $(this).data('value')
                },
                success: function (response) {
                    $this.parents('.thumbs-wrap').html(response);
                }
            });
        }
        else {
            alert($this.data('error'));
        }
    });

    /* DROPDOWN OF STORES */
    function open_store_suggestions($this) {
        $this.parents('form').find('.keyword_suggest_stores').show();
    }

    function close_store_suggestions($this) {
        $this.parents('form').find('.keyword_suggest_stores').hide();
    }

    var timeout;
    $(document).on('keyup', '.top-bar .keyword, .home-search-form input', function () {
        var $this = $(this);
        var val = $this.val();
        var search_chars = couponxxl_data.quick_search_chars ? couponxxl_data.quick_search_chars : 3;

        if (val !== '' && val.length >= search_chars) {
            timeout = setTimeout(function () {
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: 'stores_dropdown',
                        val: val
                    },
                    success: function (response) {
                        $this.parents('form').find('.keyword_suggest_stores').html(response);
                        open_store_suggestions($this);
                    },
                    complete: function () {
                        if ($this.val() == '') {
                            close_store_suggestions($this);
                        }
                    }
                });
            }, 200);
        }
        else {
            clearTimeout(timeout);
            close_store_suggestions($this);
        }
    });

    $(document).on('focus', '.top-bar .keyword, .home-search-form input', function () {
        var $this = $(this);
        if ($this.val() !== '') {
            open_store_suggestions($this);
        }
    });

    $(document).on('blur', '.top-bar .keyword, .home-search-form input', function () {
        var $this = $(this);
        if (!$this.parents('form').find('.keyword_suggest_stores').is(':hover')) {
            close_store_suggestions($this);
        }
    });

    /* SINGLE STORE FILTER */
    $(document).on('change', '.store-type-filter input', function () {
        window.location.href = $(this).val();
    });

    /* UPDATE VOUCHER STATUS FROM FRONT END */
    $(document).on('click', '.update-voucher', function () {
        var $this = $(this);
        $this.html('<i class="fa fa-spin fa-spinner"></i>');
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            dataType: 'JSON',
            data: {
                action: 'update_voucher_from_form',
                voucher_id: $this.data('voucher_id'),
                status: $this.data('status')
            },
            success: function (response) {
                $this.parents('form').find('.ajax-response').html(response.message);
            }
        })
    });

    /* PROMPT BEFORE HREF ACTION */
    $(document).on('click', '.confirm-action', function (e) {
        e.preventDefault();
        var $this = $(this);
        var response = confirm($this.data('confirm'));
        if (response) {
            window.location.href = $this.attr('href');
        }
    });

    /* REBUILD QUERY FOR THE PAYMENT GATEWAYS ON PACKAGE SELECT */
    function prepare_url(url, package_id) {
        if (url.indexOf('package') > -1) {
            url = url.split('&package')[0]
        }

        return url + '&package=' + package_id;
    }

    $(document).on('click', '.purchase-package', function (e) {
        var $this = $(this);
        var package_id = $this.data('package_id');
        if ($this.attr('href').indexOf('http') > -1) {
            e.preventDefault();
            window.location.href = prepare_url($this.attr('href'), package_id);
        }
        else {
            $('#gateways ul a').each(function () {
                $(this).attr('href', prepare_url($(this).attr('href'), package_id));
            });
        }
    });

    /* TRIGGER MENU */

    function calc_side_panels_height() {
        if ($(window).width() < 769) {
            $('.small-device-wrapper').css('max-height', $(window).height() - 70);
        }
        else {
            $('.small-device-wrapper').parent().css('max-height', 'none');
        }
    }

    calc_side_panels_height();
    $(window).resize(function () {
        calc_side_panels_height();
    });

    $(document).on('click', '.menu-trigger', function () {
        $(this).parent().toggleClass('open');
        $('.categories-trigger').parent().removeClass('open');
    });

    $(document).on('click', '.categories-trigger', function () {
        $(this).parent().toggleClass('open');
        $('.menu-trigger').parent().removeClass('open');
    });

    /* POPUP */
    function start_galleries() {
        $('.gallery').each(function () {
            var $this = $(this);
            $this.magnificPopup({
                type: 'image',
                delegate: 'a',
                gallery: {enabled: true},
                image: {
                    verticalFit: false
                }
            });
        });
    }

    start_galleries();

});