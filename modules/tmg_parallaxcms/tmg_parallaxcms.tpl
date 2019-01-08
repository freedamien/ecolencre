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

<div id="tmgprallaxcmsblock" class="block parallax" data-source-url="{$image_url}/parallax-bg.jpg">
<div class="prallax">
	<div class="container">
		<div class="row">
  			{if $tmgcms_infos.text == 'add your code here'}	
		<div class="service-cms container">
		<div class="row">
		<div class="prallax-block">
			<a class="prallax-image" href="#"><img src="modules/tmg_parallaxcms/views/img/parallax-img.png" alt="parallax-img.png"></a>
		</div>
		<div class="service-block">
		<div class="services">
		<div class="service-1 service ">
		<div class="services-inner">
		<span class="service1-icon icon"><span class="service1-icon icon-inner"></span></span>
		<div class="service-content">
		<div class="content-title"><a href="#">free shipping</a></div>		
		<div class="content-desc">Lorem Ipsum is simply dummy text of printing setting industry.</div>
		</div>
		</div>
		</div>
		<div class="service-2 service">
		<div class="services-inner">
		<span class="service2-icon icon"><span class="service2-icon icon-inner"></span></span>
		<div class="service-content">
		<div class="content-title"><a href="#">24 x 7 free Support</a></div>
		<div class="content-desc">Lorem Ipsum is simply dummy text of printing setting industry.</div>
		</div>
		</div>
		</div>	
		<div class="service-3 service">
		<div class="services-inner">
		<span class="service3-icon icon"><span class="service3-icon icon-inner"></span></span>
		<div class="service-content">
		<div class="content-title"><a href="#">money bank</a></div>		
		<div class="content-desc">Lorem Ipsum is simply dummy text of printing setting industry.</div>
		</div>
		</div>
		</div>
	    <div class="service-4 service">
		<div class="services-inner">
		<span class="service4-icon icon"><span class="service4-icon icon-inner"></span></span>
		<div class="service-content">
		<div class="content-title"><a href="#">AWARD WINNERs</a></div>
		<div class="content-desc">Lorem Ipsum is simply dummy text of printing setting industry.</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		
	 {else}
	   	{$tmgcms_infos.text nofilter}
	{/if}
		</div>
	</div>
</div>
</div>
