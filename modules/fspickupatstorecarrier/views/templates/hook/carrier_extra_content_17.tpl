{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<div id="fspasc_wrapper">
    <div class="fspasc-col">
        <span class="carrier_title">
            {l s='Please select a store for your personal pickup location' mod='fspickupatstorecarrier'}
        </span>
        <div id="fspasc_store_selector">
            <select class="form-control form-control-select" name="fspasc_id_store" id="fspasc_id_store" onchange="FSPASC.changeStore();">
                {foreach $fspasc_stores as $fspasc_store}
                    <option value="{$fspasc_store.id_store}">
                        {$fspasc_store.name}
                    </option>
                {/foreach}
            </select>
        </div>
        <div id="fspasc_store_address">
            <span class="title">{l s='Address' mod='fspickupatstorecarrier'}:</span>
            <span id="fspasc_store_address_value"></span>
        </div>
        <div id="fspasc_store_phone">
            <span class="title">{l s='Phone' mod='fspickupatstorecarrier'}:</span>
            <span id="fspasc_store_phone_value"></span>
        </div>
    </div>
    {if $fspasc_date_enable}
    <div class="fspasc-col-last">
        <span class="carrier_title">
            {l s='Please select a date when you want to pickup the package' mod='fspickupatstorecarrier'}
        </span>
        <div id="fspasc-date-selector">
            <input type="hidden" id="fspasc_selected_date_time" value="{$fspasc_selected_date_time}">
            <div class="fspasc-date-selector-button" onclick="FSPASC.calendar.open();">
                <span id="fspasc-date-selector-text"></span>
                <span class="fspasc-hint">{l s='(Click To Select)' mod='fspickupatstorecarrier'}</span>
            </div>
            <div id="fspasc-date-time-selector-wrapper">
                {$fspasc_calendar_html nofilter}
            </div>
        </div>
    </div>
    {/if}
    <div class="fspasc-clear"></div>
    {if $fspasc_map_enable}
    <div id="fspasc_map"></div>
    {if $fspasc_enable_store_locator}
    <div class="fspasc-cell-label-sm">
        {l s='Nearest Store Locator' mod='fspickupatstorecarrier'}
    </div>
    <div id="fspasc-locator" class="fspasc-table">
        <div class="fspasc-row">
            <div class="fspasc-cell fspasc-cell-input">
                <textarea rows="1" id="fspasc-locator-value" class="form-control" placeholder="{l s='Type an address: 11001 Pines Blvd Pembroke Pines, Miami, FL 33026' mod='fspickupatstorecarrier'}">{$fspasc_customer_address}</textarea>
            </div>
            <div class="fspasc-cell fspasc-cell-button hidden-md-down">
                <a onclick="FSPASC.geocodeAddress();" href="javascript:;" class="btn btn-primary pull-right fspasc-search-button">
                    <span class="fspasc-search-button-loader"><i class="fspasc-fa fspasc-fa-refresh fspasc-fa-spin" aria-hidden="true"></i></span>
                    <span class="fspasc-search-button-text">{l s='Find a Store' mod='fspickupatstorecarrier'}</span>
                </a>
            </div>
            <div class="fspasc-cell fspasc-cell-button hidden-lg-up">
                <a onclick="FSPASC.geocodeAddress();" href="javascript:;" class="btn btn-primary pull-right fspasc-search-button">
                    <span class="fspasc-search-button-loader"><i class="fspasc-fa fspasc-fa-refresh fspasc-fa-spin" aria-hidden="true"></i></span>
                    <span class="fspasc-search-button-text">{l s='OK' mod='fspickupatstorecarrier'}</span>
                </a>
            </div>
        </div>
    </div>
    {/if}
    {/if}
</div>
{if $fspasc_async_ui_init}<script>{literal}$(document).ready(function(){FSPASC.initUI();});{/literal}</script>{/if}