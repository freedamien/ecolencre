{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<div id="fspasc-calendar">
    <input type="hidden" id="fspasc-selected-date" value="{$fspasc_calendar.selected_date}">
    <input type="hidden" id="fspasc-selected-time" value="{$fspasc_calendar.selected_time}">
    <div class="fspasc-calendar-months">
        {foreach $fspasc_calendar.dates as $month}
        <div class="fspasc-calendar-month {$month.class}" id="fspasc-calendar-month-{$month.panel_id}">
            <div class="fspasc-calendar-row-nav">
                {if $month.prev > 0}
                <div class="fspasc-calendar-nav-left" onclick="FSPASC.calendar.changeMonth('{$month.prev}');">
                    <i class="fspasc-fa fspasc-fa-chevron-left"></i>
                </div>
                {else}
                <div class="fspasc-calendar-nav-side-empty">
                    <i class="fspasc-fa fspasc-fa-chevron-left"></i>
                </div>
                {/if}
                <div class="fspasc-calendar-nav-middle">
                    {$month.name}
                </div>
                {if $month.next > 0}
                <div class="fspasc-calendar-nav-right" onclick="FSPASC.calendar.changeMonth('{$month.next}');">
                    <i class="fspasc-fa fspasc-fa-chevron-right"></i>
                </div>
                {else}
                <div class="fspasc-calendar-nav-side-empty">
                    <i class="fspasc-fa fspasc-fa-chevron-right"></i>
                </div>
                {/if}
                <div class="fspasc-clear"></div>
            </div>
            <div class="fspasc-calendar-row-days">
                <div class="fspasc-calendar-col">
                    {l s='Mo' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-calendar-col">
                    {l s='Tu' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-calendar-col">
                    {l s='We' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-calendar-col">
                    {l s='Th' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-calendar-col">
                    {l s='Fr' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-calendar-col">
                    {l s='Sa' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-calendar-col">
                    {l s='Su' mod='fspickupatstorecarrier'}
                </div>
                <div class="fspasc-clear"></div>
            </div>
            {foreach $month.weeks as $week => $week_days}
            <div class="fspasc-calendar-week">
                {foreach $week_days as $day_of_week => $day}
                <div{if !$day.disabled} id="fspasc-calendar-day-{$day.date}"{/if}
                        class="fspasc-calendar-day{if $day.disabled} disabled{/if}{if $day.current} selected{/if}"
                        data-selectdayid="{if $day.current}today{else}day{$day_of_week}{/if}"
                        {if !$day.disabled} onclick="FSPASC.calendar.selectDay('{$day.date}', {if $day.current}'today'{else}'day{$day_of_week}'{/if});"{/if}>
                    {$day.text}
                </div>
                {/foreach}
                <div class="fspasc-clear"></div>
            </div>
            {/foreach}
        </div>
        {/foreach}
    </div>
    {if $fspasc_time_enable}
    <div class="fspasc-calendar-time-picker">
        <div class="fspasc-calendar-time-picker-header">
            {l s='Time' mod='fspickupatstorecarrier'}
        </div>
        <div class="fspasc-calendar-time-days">
            {foreach $fspasc_calendar.times as $day}
            <div class="fspasc-calendar-time-day {$day.class}" id="fspasc-calendar-time-day-{$day.id}">
                {foreach $day.hours as $hour}
                <div{if !$hour.disabled} id="fspasc-calendar-time-hour-{$hour.id}"{/if}
                        class="fspasc-calendar-time-hour{if $hour.disabled} disabled{/if}{if $hour.current} selected{/if}"{if !$hour.disabled}
                        onclick="FSPASC.calendar.selectTime('{$hour.full_time}', '{$hour.id}')"{/if}
                        data-fulltime="{$hour.full_time}">
                    {$hour.text}
                </div>
                {/foreach}
                <div class="fspasc-clear"></div>
            </div>
            {/foreach}
        </div>
    </div>
    {/if}
    <div class="fspasc-calendar-buttons">
        <div class="fspasc-calendar-button">
            <div class="fspasc-calendar-button-cancel" onclick="FSPASC.calendar.cancel();">
                {l s='Cancel' mod='fspickupatstorecarrier'}
            </div>
        </div>
        <div class="fspasc-calendar-button">
            <div class="fspasc-calendar-button-select" onclick="FSPASC.calendar.select();">
                {l s='Select' mod='fspickupatstorecarrier'}
            </div>
        </div>
        <div class="fspasc-clear"></div>
    </div>
</div>