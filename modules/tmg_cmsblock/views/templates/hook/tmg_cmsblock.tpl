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

<div id="tmgcmsblock">
 {if $tmgcms_infos.text == 'add your code here'}

				<div class="subbannercms">
				<div class="subbanner blocks">
				<div class="subbanner-inner">
					<div class="subbanner-image">
					<a class="subbanner-image" href="#">
					<img src="modules/tmg_cmsblock/views/img/subbanner1.jpg" alt="banner1.jpg">
					</a>
					</div>
					<div class="detailes">
					<div class="cms-icon"><img src="modules/tmg_cmsblock/views/img/headphone.png" alt="headphone.png"></div>
					<div class="top-title">up to 30% off all products</div>
					<div class="title">Best Electronics</div>
					<div class="description">Lorem Ipsum is simply dummy of the printing typesetting industry. Lorem Ipsum has been industry's standard</div>
					<div class="more"><a class="btn btn-primary add-to-cart">Shop now</a></div>
					</div>
				</div>
				</div>
				</div>

	 {else}
	   	{$tmgcms_infos.text nofilter}
	{/if}
</div>
