{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<tr style="background-color:{$fspasc_mail_color nofilter};border-bottom:1px solid #D6D4D4;color:#333;padding:7px 0;">
    <td style="padding:0.6em 0.4em;border-left:1px solid #D6D4D4;">
        {$fspasc_mail_product_reference nofilter}
    </td>
    <td style="padding:0.6em 0.4em;">
        <strong>
            <a href="{$fspasc_mail_url nofilter}">
                {$fspasc_mail_product_name nofilter}
            </a>
            {if $fspasc_mail_attributes_small} {$fspasc_mail_attributes_small nofilter}{/if}
            {if $fspasc_mail_customization_text}<br />{$fspasc_mail_customization_text nofilter}{/if}
        </strong>
    </td>
    <td style="padding:0.6em 0.4em; text-align:right;">
        {$fspasc_mail_unit_price nofilter}
    </td>
    <td style="padding:0.6em 0.4em; text-align:center;">
        {$fspasc_mail_quantity nofilter}
    </td>
    <td style="padding:0.6em 0.4em; text-align:right;border-right:1px solid #D6D4D4;">
        {$fspasc_mail_total_price nofilter}
    </td>
</tr>