{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<strong>{$fspasc_store.name|escape:'html':'UTF-8'}</strong><br />
{$fspasc_store.address1|escape:'html':'UTF-8'} {$fspasc_store.address2|escape:'html':'UTF-8'}<br />
{$fspasc_store.city|escape:'html':'UTF-8'}{if $fspasc_store.state_iso_code}, {$fspasc_store.state_iso_code|escape:'html':'UTF-8'}{/if}<br />
{$fspasc_store.postcode|escape:'html':'UTF-8'}<br />
<br />
<table>
    <tr>
        <td style="padding-right: 10px;">
            {foreach $fspasc_store.hours as $key => $day}
                <strong>{$fspasc_days_translations.$key|escape:'html':'UTF-8'}:</strong> {$day|escape:'html':'UTF-8'}<br />
            {/foreach}
        </td>
        {if $fspasc_store.img}
            <td>
                <img src="{$fspasc_store.img|escape:'html':'UTF-8'|fspascCorrectTheMess}">
            </td>
        {/if}
    </tr>
</table>
<br />
<a href="{$fspasc_get_direction_url|escape:'html':'UTF-8'|fspascCorrectTheMess}" target="_blank">
    {l s='Get directions' mod='fspickupatstorecarrier'}
</a> | <a href="javascript:;" onclick="FSPASC.selectFromMarker({$fspasc_store.id_store|escape:'html':'UTF-8'});">
    {l s='Select this store!' mod='fspickupatstorecarrier'}
</a>