/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

var FSPASC = FSPASC || {};
FSPASC.selected_carrier = null;

$(document).ready(function(){
    $('#delivery_option').on('change', function(){
        FSPASC.selected_carrier = parseInt($('#delivery_option').val());
        FSPASC.changeDeliveryOption();
    });

    FSPASC.deliveryOptionChecker();
});

FSPASC.deliveryOptionChecker = function() {
    setTimeout(function(){
        FSPASC.selected_carrier = parseInt($('#delivery_option').val());
        if (isNaN(FSPASC.selected_carrier)) {
            FSPASC.deliveryOptionChecker();
        } else {
            FSPASC.changeDeliveryOption();
        }
    }, 1000);
};

FSPASC.changeDeliveryOption = function() {
    if (FSPASC.id_carrier && FSPASC.selected_carrier && (FSPASC.id_carrier == FSPASC.selected_carrier)) {
        $('#delivery_option').parent().parent().after(FSPASC.store_selector_content);

        if (FSPASC.date_time_mode == 'date') {
            $('#fspasc_date_pickup').datepicker({
                prevText: '',
                nextText: '',
                dateFormat: 'yy-mm-dd',
                currentText: FSPASC.dateTimePickerCurrentText,
                closeText: FSPASC.dateTimePickerCloseText
            });
        }

        if (FSPASC.date_time_mode == 'datetime') {
            $('#fspasc_date_pickup').datetimepicker({
                prevText: '',
                nextText: '',
                dateFormat: 'yy-mm-dd',
                currentText: FSPASC.dateTimePickerCurrentText,
                closeText: FSPASC.dateTimePickerCloseText,
                ampm: false,
                amNames: ['AM', 'A'],
                pmNames: ['PM', 'P'],
                timeFormat: 'hh:mm:ss tt',
                timeSuffix: '',
                timeText: FSPASC.dateTimePickerTimeText,
                hourText: FSPASC.dateTimePickerHourText,
                minuteText: FSPASC.dateTimePickerMinuteText
            });
        }
    } else {
        $('#fspasc_id_store_line').remove();
        if (FSPASC.date_time_mode == 'date' || FSPASC.date_time_mode == 'datetime') {
            $('#fspasc_date_pickup_line').remove();
        }
    }
};