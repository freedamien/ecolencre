{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<script type="text/javascript">
    var FSPASC = FSPASC || { };
    FSPASC.id_carrier = {$fspasc_id_carrier|escape:'html':'UTF-8'};
    FSPASC.date_time_mode = '{$fspasc_date_time_mode|escape:'html':'UTF-8'}';

    FSPASC.store_selector_content = '<div class="form-group" id="fspasc_id_store_line">';
    FSPASC.store_selector_content += '<label class="control-label col-lg-3">{l s='Pickup Store' mod='fspickupatstorecarrier'}</label>';
    FSPASC.store_selector_content += '<div class="col-lg-9"><select name="fspasc_id_store" id="fspasc_id_store">';
    {foreach $fspasc_stores as $fspasc_store}
    FSPASC.store_selector_content += '<option value="{$fspasc_store.id_store|escape:'htmlall':'UTF-8'}">{$fspasc_store.name|escape:'htmlall':'UTF-8'}</option>';
    {/foreach}
    FSPASC.store_selector_content += '</select></div>';
    FSPASC.store_selector_content += '</div>';

    if (FSPASC.date_time_mode == 'date' || FSPASC.date_time_mode == 'datetime') {
        FSPASC.store_selector_content += '<div class="form-group" id="fspasc_date_pickup_line">';
        FSPASC.store_selector_content += '<label class="control-label col-lg-3">{l s='Pickup Time' mod='fspickupatstorecarrier'}</label>';
        FSPASC.store_selector_content += '<div class="col-lg-4">';
        FSPASC.store_selector_content += '<input id="fspasc_date_pickup" type="text" data-hex="true" name="fspasc_date_pickup" value="" placeholder="{l s='click to select date' mod='fspickupatstorecarrier'}" />';
        FSPASC.store_selector_content += '</div>';
        FSPASC.store_selector_content += '</div>';
    }

    FSPASC.dateTimePickerCurrentText = '{l s='Now' mod='fspickupatstorecarrier'}';
    FSPASC.dateTimePickerCloseText = '{l s='Done' mod='fspickupatstorecarrier'}';
    FSPASC.dateTimePickerTimeText = '{l s='Time' mod='fspickupatstorecarrier'}';
    FSPASC.dateTimePickerHourText = '{l s='Hour' mod='fspickupatstorecarrier'}';
    FSPASC.dateTimePickerMinuteText = '{l s='Minute' mod='fspickupatstorecarrier'}';
</script>
<script type="text/javascript" src="{$fspasc_module_base_url|escape:'html':'UTF-8'}views/js/admin_order_add.js"></script>