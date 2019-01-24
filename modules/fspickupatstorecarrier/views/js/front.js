/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

var FSPASC = FSPASC || {};
FSPASC.map = null;
FSPASC.info_window = null;
FSPASC.markers = {};
FSPASC.select_link_clicked = false;

FSPASC.setPhone = function(selected_store) {
    if (selected_store.phone && FSPASC.display_phone) {
        $('#fspasc_store_phone_value').html(selected_store.phone);
        $('#fspasc_store_phone').show();
    } else {
        $('#fspasc_store_phone').hide();
    }
};

FSPASC.changeStore = function() {
    var fspasc_id_store = $('#fspasc_id_store').val();
    var selected_store = FSPASC.stores[fspasc_id_store];
    FSPASC.selected_id_store = fspasc_id_store;

    if (FSPASC.map_enable && !FSPASC.select_link_clicked) {
        FSPASC.map.setCenter(new google.maps.LatLng(parseFloat(selected_store.latitude), parseFloat(selected_store.longitude)));

        if (FSPASC.open_info_window) {
            setTimeout(function(){
                FSPASC.openInfoWindow(fspasc_id_store);
            }, 200);
        }
    }

    if (FSPASC.select_link_clicked) {
        FSPASC.select_link_clicked = false;
    }

    $('#fspasc_store_address_value').html(selected_store.full_address);
    FSPASC.setPhone(selected_store);

    var data = {};
    data.fspasc_id_store = fspasc_id_store;

    $.ajax({
        url: FSPASC.callback_url,
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',
        cache: false,
        success: function(json) {
            var event = {};
            event.id_store = fspasc_id_store;
            if (json.hasOwnProperty('payments')) {
                event.payments = json.payments;
            }
            prestashop.emit('fspascChangedStore', event);

            if (typeof json.calendar_html != 'undefined' && FSPASC.date_enable) {
                $('#fspasc-date-time-selector-wrapper').html(json.calendar_html);
                FSPASC.calendar.updateValue();
                FSPASC.calendar.formatDate();
            }

            if (json.hasOwnProperty('summary_address_html')) {
                FSPASC.setSummaryAddress(json.summary_address_html);
            }

            FSPASC.updateContinueButton();
        }
    });
};

FSPASC.reloadStore = function() {
    var fspasc_id_store = $('#fspasc_id_store').val();
    var selected_store = FSPASC.stores[fspasc_id_store];
    FSPASC.selected_id_store = fspasc_id_store;

    if (FSPASC.map_enable) {
        FSPASC.map.setCenter(new google.maps.LatLng(parseFloat(selected_store.latitude), parseFloat(selected_store.longitude)));

        if (FSPASC.open_info_window) {
            setTimeout(function(){
                FSPASC.openInfoWindow(FSPASC.selected_id_store);
            }, 200);
        }
    }
};

FSPASC.changeDate = function() {
    var fspasc_date_time = $('#fspasc_selected_date_time').val();

    var data = {};
    data.fspasc_date_time = fspasc_date_time;

    $.ajax({
        url: FSPASC.callback_url_dt,
        type: 'POST',
        data: data,
        async: true,
        dataType: 'json',
        cache: false,
        success: function(json) {
            var event = {};
            event.date_time = fspasc_date_time;
            prestashop.emit('fspascChangedDateTime', event);
        }
    });
};

FSPASC.initSelector = function() {
    var selected_store = FSPASC.stores[FSPASC.selected_id_store];

    $('#fspasc_id_store').val(FSPASC.selected_id_store);
    $('#fspasc_store_address_value').html(selected_store.full_address);
    FSPASC.setPhone(selected_store);
};

FSPASC.initMap = function() {
    FSPASC.info_window = new google.maps.InfoWindow();

    var selected_store = FSPASC.stores[FSPASC.selected_id_store];

    var map_options = {
        scrollwheel: false,
        zoom: FSPASC.map_zoom,
        center: new google.maps.LatLng(parseFloat(selected_store.latitude), parseFloat(selected_store.longitude))
    };

    if (FSPASC.map_enable && document.getElementById('fspasc_map')) {
        FSPASC.map = new google.maps.Map(document.getElementById('fspasc_map'), map_options);
        FSPASC.addMarkers();

        if (FSPASC.open_info_window) {
            setTimeout(function(){
                FSPASC.openInfoWindow(FSPASC.selected_id_store);
            }, 200);
        }

        FSPASC.map.addListener('dragstart', function() {
            FSPASC.map_reload = false;
        });

        FSPASC.map.addListener('zoom_changed', function() {
            FSPASC.map_reload = false;
        });
    }
};

FSPASC.reloadMap = function() {
    google.maps.event.trigger(FSPASC.map, 'resize');
    FSPASC.reloadStore();
};

FSPASC.addMarkers = function() {
    for (i in FSPASC.stores) {
        var marker = null;
        if (FSPASC.marker_type == 'image') {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(FSPASC.stores[i].latitude, FSPASC.stores[i].longitude),
                info: FSPASC.stores[i].id_store,
                map: FSPASC.map,
                icon: FSPASC.marker_url
            });
        } else {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(FSPASC.stores[i].latitude, FSPASC.stores[i].longitude),
                info: FSPASC.stores[i].id_store,
                map: FSPASC.map
            });
        }

        FSPASC.markers[FSPASC.stores[i].id_store] = marker;

        google.maps.event.addListener(marker, 'click', function () {
            FSPASC.openInfoWindow(this.info);
        });
    }
};

FSPASC.selectFromMarker = function(selected_id_store) {
    FSPASC.select_link_clicked = true;
    $('#fspasc_id_store').val(selected_id_store).change();
};

FSPASC.openInfoWindow = function(id_store) {
    FSPASC.info_window.setContent(FSPASC.stores[id_store].info_box_html)
    FSPASC.info_window.open(FSPASC.map, FSPASC.markers[id_store]);
};

FSPASC.updateContinueButton = function() {
    if (FSPASC.module_id_carrier == FSPASC.selected_id_carrier) {
        if (FSPASC.selected_id_store > 0) {
            $('button[name="confirmDeliveryOption"]').removeAttr('disabled');
        } else {
            $('button[name="confirmDeliveryOption"]').attr('disabled', 'disabled');
        }
    } else {
        $('button[name="confirmDeliveryOption"]').removeAttr('disabled');
    }
};

FSPASC.geocodeAddress = function() {
    FSPASC.showSearchLoader();
    $.ajax({
        url: FSPASC.geocode_url,
        type: 'GET',
        data: {address: $('#fspasc-locator-value').val(), mode: 'address'},
        async: true,
        dataType: 'json',
        cache: false,
        success: function(json) {
            if (!json.hasOwnProperty('error')) {
                if (json.hasOwnProperty('id_store')) {
                    FSPASC.foundStore(json.id_store);
                }
            } else {
                console.log(json.error);
            }
            FSPASC.hideSearchLoader()
        },
        error: function() {
            FSPASC.hideSearchLoader();
        }
    });
};

FSPASC.foundStore = function(id_store) {
    var selected_store = FSPASC.stores[id_store];
    FSPASC.map.setCenter(new google.maps.LatLng(parseFloat(selected_store.latitude), parseFloat(selected_store.longitude)));

    setTimeout(function(){
        FSPASC.openInfoWindow(id_store);
    }, 200);
};

FSPASC.showSearchLoader = function() {
    $('.fspasc-search-button').addClass('fspasc-loading');
};

FSPASC.hideSearchLoader = function() {
    $('.fspasc-search-button').removeClass('fspasc-loading');
};

FSPASC.setSummaryAddress = function(address) {
    var selector = '#order-summary-content .addresshead';
    if ($(selector).html()) {
        var address_html = $(selector).first().clone().wrap('<div/>').parent().html();
        address_html += address;
        $(selector).first().parent().html(address_html);
    }
};

FSPASC.initUI = function() {
    FSPASC.initSelector();

    if (FSPASC.map_enable) {
        FSPASC.initMap();
    }

    if (FSPASC.date_enable) {
        FSPASC.calendar.setDate();
        FSPASC.calendar.updateValue();
        FSPASC.calendar.formatDate();
    }

    FSPASC.updateContinueButton();

    if (FSPASC.selected_store_formatted_address && FSPASC.module_id_carrier == FSPASC.selected_id_carrier) {
        FSPASC.setSummaryAddress(FSPASC.selected_store_formatted_address);
    }
};

$(document).ready(function(){
    if (!FSPASC.async_ui_init) {
        FSPASC.initUI();
    }

    prestashop.on('updatedDeliveryForm', function (event) {
        if (FSPASC.map_enable) {
            FSPASC.reloadMap();
        }
        FSPASC.selected_id_carrier = parseInt(event.deliveryOption.context.value);
        FSPASC.updateContinueButton();
    });

    prestashop.on('changedCheckoutStep', function (event) {
        try {
            if (FSPASC.map_enable && (event.event.currentTarget.id == 'checkout-delivery-step')) {
                if ($(event.event.target).hasClass('step-title') || $(event.event.target).hasClass('step-edit')) {
                    FSPASC.reloadMap();
                }

                if ($(event.event.target).is('img')) {
                    $('#checkout-delivery-step').addClass('-current js-current-step');
                }
            }
        }
        catch(err) { console.error(err); }
    });
});