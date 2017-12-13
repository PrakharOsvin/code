/* MANAGE LOCATION */
jQuery(document).ready(function ($) {
    'use strict';
    var locations_number = $('.store-location-wrap').length;
    var markersArray = [];
    var Maps = [];
    var marker_start_id = 1;
    $('.marker_id').each(function () {
        var val = parseInt($(this).val());
        if (marker_start_id < val) {
            marker_start_id = val;
        }
    });

    $(document).on('click', '.add-new-store-location', function () {
        var html = $('.store-location-pattern').html();
        locations_number++;
        $(this).before(html.replace('data-count="0"', 'data-count="' + locations_number + '"'));
        start_map($('.store-location-wrap:last'));
    });

    $(document).on('click', '.remove-store-location', function () {
        $(this).parents('.store-location-wrap').remove();
    });

    $(document).on('change', '.store-location-wrap select', function () {
        var $this = $(this);
        var old = $this.attr('id');
        var val = $this.val();
        $this.attr('id', val);
        $this.prev().attr('for', val);
        $this.parents('.store-location-wrap').find('input[name^="store_location[' + old + ']"]').each(function () {
            var $$this = $(this);
            $$this.attr('name', $$this.attr('name').replace(old, val));
        });

    });

    $(document).on('click', '.add-new-store-marker', function () {
        var $parent = $(this).parents('.store-location-wrap');
        var term_slug = $parent.find('select').val();

        $(this).before($('.store-marker-pattern').html().replace(/X_ID_X/g, term_slug).replace(/\[0\]/g, '[' + $parent.find('.store-marker').length + ']'));
        marker_start_id++;
        $parent.find('.store-marker:last .marker_id').val(marker_start_id);
        bind_gsearch($parent);
    });

    $(document).on('click', '.remove-store-marker', function () {
        var $wrapper = $(this).parents('.store-location-wrap');
        var hasMarker = $wrapper.find('.marker_id').val();
        if (hasMarker) {
            markersArray[$wrapper.data('count')][hasMarker].setMap(null);
        }
        $(this).parents('.store-marker').remove();

        update_map($wrapper);
    });

    function start_map($wrapper) {
        var $markers = $wrapper.find('.store-marker');
        var count = $wrapper.data('count');
        if (!markersArray[count]) {
            markersArray[count] = [];
        }

        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {mapTypeId: google.maps.MapTypeId.ROADMAP};
        var map = new google.maps.Map($wrapper.find('.store-gmap')[0], mapOptions);
        Maps[count] = map;
        var location;
        if ($markers.length > 0) {
            $markers.each(function () {
                var $$this = $(this);
                var lat = $$this.find('.latitude').val();
                var lng = $$this.find('.longitude').val();
                var marker_id = $$this.find('.marker_id').val();
                if (lat && lng) {
                    location = new google.maps.LatLng(lat, lng);
                    bounds.extend(location);

                    var marker = new google.maps.Marker({
                        position: location,
                        map: Maps[count],
                        title: $$this.find('.store-gmap-location').val(),
                        marker_id: marker_id,
                        draggable: true
                    });

                    google.maps.event.addListener(
                        marker,
                        'drag',
                        function () {
                            $$this.find('.longitude').val(marker.position.lng());
                            $$this.find('.latitude').val(marker.position.lat());
                        }
                    );

                    markersArray[count][marker_id] = marker;

                }

            });

            map.fitBounds(bounds);

        }

        bind_gsearch($wrapper);
    }

    function update_map($wrapper) {
        var count = $wrapper.data('count');
        var map = Maps[count];
        var bounds = new google.maps.LatLngBounds();
        markersArray[count].forEach(function (marker) {
            marker.setMap(null);
        });
        $wrapper.find('.store-marker').each(function () {
            var $this = $(this);
            if (!$this.parent().hasClass('hidden')) {
                var location = new google.maps.LatLng($this.find('.latitude').val(), $this.find('.longitude').val());
                bounds.extend(location);

                var marker_id = parseInt($this.find('.marker_id').val());

                var marker = new google.maps.Marker({
                    position: location,
                    map: Maps[count],
                    title: $this.find('.store-gmap-location').val(),
                    marker_id: marker_id,
                    draggable: true
                });

                google.maps.event.addListener(
                    marker,
                    'drag',
                    function () {
                        $this.find('.longitude').val(marker.position.lng());
                        $this.find('.latitude').val(marker.position.lat());
                    }
                );

                markersArray[count][marker_id] = marker;
            }
        });

        map.fitBounds(bounds);
    }

    function bind_gsearch($wrapper) {
        var count = $wrapper.data('count');
        $wrapper.find('.store-gmap-location:not(.loaded)').each(function () {
            var $this = $(this);
            if ($this.parents('.hidden').length == 0) {
                var $parent = $this.parent();
                $this.addClass('loaded');
                var searchBox = new google.maps.places.SearchBox($this[0]);
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    if ($parent.find('.longitude').val()) {
                        markersArray[count][$parent.find('.marker_id').val()].setMap(null);
                    }

                    var marker = new google.maps.Marker({
                        map: Maps[count],
                        title: places[0].name,
                        position: places[0].geometry.location,
                        draggable: true
                    });

                    $parent.find('.longitude').val(marker.position.lng());
                    $parent.find('.latitude').val(marker.position.lat());

                    var marker_start_id = $parent.find('.marker_id').val();
                    markersArray[count][marker_start_id] = marker;


                    update_map($wrapper);

                    $('form#post').off('submit');

                });

                $this.val($this.val().replace(/\\/i, ''));
                $this.on('keypress', function () {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == 13) {
                        $('form#post').on('submit', function () {
                            return false;
                        });
                    }
                });
            }
        });
    }

    $('.store-location-wrap').each(function () {
        var $this = $(this);
        if (!$this.parent().hasClass('hidden')) {
            start_map($this);
        }
    });

    function check_checked($this) {
        var val = $this.val();
        if ($this.prop('checked')) {
            $('.' + $this.val()).prop('checked', true);
        }
        else {
            $('.' + $this.val()).prop('checked', false);
        }
    }

    function update_main_location(id, status) {
        $('#locationchecklist input[value="' + id + '"]').prop('checked', status);
    }

    $(document).on('change', '#locationchecklist input', function () {
        var $this = $(this);
        if ($('#offer_type-sm-field-0').val() == 'deal' && $('.store_location input[data-id="' + $this.val() + '"]').length > 0) {
            $('.store_location input[data-id="' + $this.val() + '"]').prop('checked', $this.prop('checked')).trigger('change');
        }
    });

    $(document).on('change', '.checkbox-child', function () {
        var $this = $(this);
        var $parent = $this.parents('.checkbox-group');
        var checkParent = false;
        $parent.find('.checkbox-child').each(function () {
            if ($(this).prop('checked')) {
                checkParent = true;
            }
        });

        var $main = $parent.find('.deal-location-main');
        if (checkParent) {
            update_main_location($main.data('id'), true);
            $main.prop('checked', true);
        }
        else {
            update_main_location($main.data('id'), false);
            $main.prop('checked', false);
        }
    });

    $(document).on('change', '.deal-location-main', function () {
        var $this = $(this);
        var $parent = $this.parents('.checkbox-group');
        check_checked($this);
        if ($this.prop('checked')) {
            update_main_location($this.data('id'), true);
            $parent.find('.checkbox-child').each(function () {
                $(this).prop('checked', true);
            });
        }
        else {
            update_main_location($this.data('id'), false);
            $parent.find('.checkbox-child').each(function () {
                $(this).prop('checked', false);
            });
        }
    });

    function check_offer_type(val) {
        if (val == 'coupon') {
            $('.store_location input').prop('checked', false).attr('disabled', 'disabled');
        }
        else {
            $('.store_location input').removeAttr('disabled');
        }
    }

    $(document).on('change', 'select[id^="offer_type"]', function () {
        check_offer_type($(this).val());
    });
    $('select[id^="offer_type"]').trigger('change');
});