/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

var FSPASC = FSPASC || { };
FSPASC.calendar = FSPASC.calendar || { };

FSPASC.calendar.changeMonth = function(id) {
    $('.fspasc-calendar-month').hide();
    $('#fspasc-calendar-month-'+id).show();
};

FSPASC.calendar.selectDay = function(date, day_of_week) {
    $('.fspasc-calendar-day').removeClass('selected');
    $('#fspasc-calendar-day-'+date).addClass('selected');

    $('.fspasc-calendar-time-day').removeClass('active');
    $('#fspasc-calendar-time-day-'+day_of_week).addClass('active');

    $('#fspasc-selected-date').val(date);

    if (FSPASC.time_enable) {
        var time = $('.fspasc-calendar-time-day.active .selected').data('fulltime');
        $('#fspasc-selected-time').val(time);
    }
};

FSPASC.calendar.selectTime = function(time, id) {
    $('.fspasc-calendar-time-hour', $('#fspasc-calendar-time-hour-'+id).parent()).removeClass('selected');
    $('#fspasc-calendar-time-hour-'+id).addClass('selected');
    $('#fspasc-selected-time').val(time);
};

FSPASC.calendar.open = function() {
    $('#fspasc-calendar').show();
};

FSPASC.calendar.cancel = function() {
    FSPASC.calendar.close();
    FSPASC.calendar.setDate();
};

FSPASC.calendar.close = function() {
    $('#fspasc-calendar').hide();
};

FSPASC.calendar.select = function() {
    FSPASC.calendar.updateValue();
    FSPASC.calendar.formatDate();

    FSPASC.calendar.close();
};

FSPASC.calendar.updateValue = function() {
    var selected_date = $('#fspasc-selected-date').val();
    var selected_time = $('#fspasc-selected-time').val();
    $('#fspasc_selected_date_time').val(selected_date+' '+selected_time);

    FSPASC.changeDate();
};

FSPASC.calendar.formatDate = function() {
    var format = FSPASC.date_time_format;
    var selected_date = $('#fspasc-selected-date').val();
    var selected_time = $('#fspasc-selected-time').val();

    if (selected_date && selected_time) {
        var selected_date_array = selected_date.split('-');
        var year = parseInt(selected_date_array[0]);
        var month = FSPASC.translation_months[parseInt(selected_date_array[1])-1];
        var day = parseInt(selected_date_array[2]);

        var selected_time_array = selected_time.split(':');
        var time_12 = parseInt(selected_time_array[0]);
        if (time_12 > 12) {
            time_12 = time_12-12;
            time_12 = time_12 + ':00 PM';
        } else {
            time_12 = time_12 + ':00 AM';
        }

        var time_24 = parseInt(selected_time_array[0]);
        time_24 = time_24 + ':00';

        format = format.replace('%Y', year);
        format = format.replace('%M', month);
        format = format.replace('%D', day);
        format = format.replace('%T12', time_12);
        format = format.replace('%T24', time_24);

        $('#fspasc-date-selector-text').html(format);
    }
};

FSPASC.calendar.setDate = function() {
    if (FSPASC.date_enable && $('#fspasc_selected_date_time').val() != undefined) {
        var selected_date_time = $('#fspasc_selected_date_time').val();
        var selected_date_time_array = selected_date_time.split(' ');
        var selected_date = selected_date_time_array[0];

        var selected_date_array = selected_date.split('-');
        var year = parseInt(selected_date_array[0]);
        var month = parseInt(selected_date_array[1]);
        var day = parseInt(selected_date_array[2]);

        FSPASC.calendar.changeMonth(year+'-'+month);

        var select_day_id = $('#fspasc-calendar-day-'+selected_date).data('selectdayid');
        FSPASC.calendar.selectDay(selected_date, select_day_id);

        if (FSPASC.time_enable) {
            var selected_time = selected_date_time_array[1];
            var selected_time_array = selected_time.split(':');
            var hours = parseInt(selected_time_array[0]);

            var d = new Date(Date.UTC(year, month-1, day, hours, 0, 0));
            var d_str = d.toString();
            var d_str_array = d_str.split('GMT');
            var d_str_utc = d.toUTCString()+d_str_array[1];
            d = new Date(d_str_utc);

            var day_id = '';
            if (select_day_id == 'today') {
                day_id = day_id+'t';
            }
            day_id = day_id+'d'+ d.getDay()+'h'+hours;

            var time = $('#fspasc-calendar-time-hour-'+day_id).data('fulltime');
            FSPASC.calendar.selectTime(time, day_id);
        }
    }
};
