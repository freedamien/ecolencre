{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="tmgfootercms">
  {if $tmgcms_infos.text == 'add your code here'}
		<div class="block-payment hb-animate-element top-to-bottom">
		<div class="block-payment-inner">
		<ul>
		<li class="amex pay-icon"><a href="#" target="_blank">amex</a></li>
	    <li class="visa pay-icon"><a href="#" target="_blank">visa</a></li>
		<li class="master pay-icon"><a href="#" target="_blank">master</a></li>
		<li class="paypal pay-icon"><a href="#" target="_blank">paypal</a></li>
		<li class="mastro pay-icon"><a href="#" target="_blank">mastro</a></li>
		</ul>
		</div>
		</div>
	 {else}
	   	{$tmgcms_infos.text nofilter}
	{/if}
</div>
