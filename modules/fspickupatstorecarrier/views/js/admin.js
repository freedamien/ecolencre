/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

var FSPASC = FSPASC || {};

$(document).ready(function(){
    $('select#fspasc_id_carrier').change(function(){
        FSPASC.carrierChangeCallback();
    });

    FSPASC.carrierChangeCallback();

    $('#fspasc_tabs a').click(function(){
        $('#fspasc_tabs a').removeClass('active');
        $(this).addClass('active');
    });

    $('#csv_field_enclosure').val('"');
});

FSPASC.carrierChangeCallback = function() {
    if ($('select#fspasc_id_carrier').val() == FSPASC.id_carrier) {
        $('select#fspasc_id_store').parent().parent().show();
    }
    else {
        $('select#fspasc_id_store').parent().parent().hide();
    }
};

