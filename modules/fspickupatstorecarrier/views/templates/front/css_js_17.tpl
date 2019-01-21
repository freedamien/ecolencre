{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

{fspascMinifyCss}
<style type="text/css">
    #fspasc_map {
        height: {$fspasc_css.map_height}px;
    }
</style>
{/fspascMinifyCss}

<script type="text/javascript">
    var FSPASC = FSPASC || { };
    FSPASC.callback_url = '{$fspasc_js.callback_url nofilter}';
    FSPASC.callback_url_dt = '{$fspasc_js.callback_url_dt nofilter}';
    FSPASC.geocode_url = '{$fspasc_js.geocode_url nofilter}';
    FSPASC.stores = {$fspasc_js.stores nofilter};
    FSPASC.selected_id_store = {$fspasc_js.selected_id_store};
    FSPASC.marker_url = '{$fspasc_js.marker_url nofilter}';
    FSPASC.marker_type = '{$fspasc_js.marker_type}';
    FSPASC.map_zoom = {$fspasc_js.map_zoom};
    FSPASC.map_enable = {$fspasc_js.map_enable};
    FSPASC.time_enable = {$fspasc_js.time_enable};
    FSPASC.date_enable = {$fspasc_js.date_enable};
    FSPASC.date_time_format = '{$fspasc_js.date_time_format}';
    FSPASC.display_phone = {$fspasc_js.display_phone};
    FSPASC.async_ui_init = {$fspasc_js.async_ui_init};
    FSPASC.open_info_window = {$fspasc_js.open_info_window};
    FSPASC.translation_months = { };
    FSPASC.module_id_carrier = {$fspasc_js.module_id_carrier};
    FSPASC.selected_id_carrier = {$fspasc_js.selected_id_carrier};
    FSPASC.selected_store_formatted_address = '{$fspasc_js.formatted_address nofilter}';
    {foreach $fspasc_translated_months as $month_num => $month_name}
    FSPASC.translation_months[{($month_num-1)}] = '{$month_name}';
    {/foreach}
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&language={$fspasc_js.language_code_for_map}{if isset($fspasc_js.map_api_key) && $fspasc_js.map_api_key}&amp;key={$fspasc_js.map_api_key}{/if}"></script>