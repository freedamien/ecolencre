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

<div id="tmgfeaturedcms">
  {if $tmgcms_infos.text == 'add your code here'}
		<div class="container">
		<div class="row">
		<div class="collections hb-animate-element top-to-bottom">
		
		<div class="collection collection1">
		<div class="collections-inner">
		<div class="collection-image">
		<div class="collection-image-inner image1">
		<a class="collection-image" href="#"><img src="modules/tmg_featuredcms/views/img/collection1.jpg" alt="banner1.jpg"></a>
		</div>
		</div>
		<div class="collction-details">
		<div class="collection-des">up to 50% off</div>
		<div class="collection-title"><a href="#">branded watch</a></div>
		<div class="more"><a class="btn btn-primary add-to-cart">shop now</a></div>
		</div>
		</div>
		</div>
		<div class="collection collection2">
		<div class="collections-inner">
		<div class="collection-image">
		<div class="collection-image-inner image2">
		<a class="collection-image" href="#"><img src="modules/tmg_featuredcms/views/img/collection2.jpg" alt="banner2.jpg"></a>
		</div>
		</div>
		<div class="collction-details">
		<div class="collection-des">up to 20% off</div>
		<div class="collection-title"><a href="#">branded camera</a></div>
		<div class="more"><a class="btn btn-primary add-to-cart">shop now</a></div>
		</div>
		</div>
		</div>
		<div class="collection collection3">
		<div class="collections-inner">
		<div class="collection-image">
		<div class="collection-image-inner image3">
		<a class="collection-image" href="#"><img src="modules/tmg_featuredcms/views/img/collection3.jpg" alt="banner3.jpg"></a>
		</div>
		</div>
		<div class="collction-details">
		<div class="collection-des">up to 20% off</div>
		<div class="collection-title"><a href="#">best headphone</a></div>
		<div class="more"><a class="btn btn-primary add-to-cart">shop now</a></div>
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
