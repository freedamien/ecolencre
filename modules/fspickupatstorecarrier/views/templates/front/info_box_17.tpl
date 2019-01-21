{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<strong>{$fspasc_store.name}</strong><br />
{$fspasc_store.address1} {$fspasc_store.address2}<br />
{$fspasc_store.city}{if $fspasc_store.state_iso_code}, {$fspasc_store.state_iso_code}{/if}<br />
{$fspasc_store.postcode}<br />
<br />
<table>
    <tr>
        <td style="padding-right: 10px;">
            {foreach $fspasc_store.hours as $key => $day}
            <strong>{$fspasc_days_translations.$key}:</strong> {$day}<br />
            {/foreach}
        </td>
        {if $fspasc_store.img}
        <td>
            <img src="{$fspasc_store.img}">
        </td>
        {/if}
    </tr>
</table>
<br />
<a href="{$fspasc_get_direction_url nofilter}" target="_blank">
    {l s='Get directions' mod='fspickupatstorecarrier'}
</a> | <a href="javascript:;" onclick="FSPASC.selectFromMarker({$fspasc_store.id_store});">
    {l s='Select this store!' mod='fspickupatstorecarrier'}
</a>