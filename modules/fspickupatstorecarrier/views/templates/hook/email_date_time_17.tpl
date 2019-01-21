{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

{if $fspasc_mail_date_selected}
<span style="color:#333"><strong>{l s='Selected Date:' mod='fspickupatstorecarrier'}</strong></span> {$fspasc_mail_date_selected nofilter}<br />
{/if}
{if $fspasc_mail_time_selected}
<span style="color:#333"><strong>{l s='Selected Time:' mod='fspickupatstorecarrier'}</strong></span> {$fspasc_mail_time_selected nofilter}
{/if}