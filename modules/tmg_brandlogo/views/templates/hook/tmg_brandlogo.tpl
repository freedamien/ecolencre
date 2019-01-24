{**
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


<section class="brands hb-animate-element top-to-bottom">
 	 <div class="container">
	 	<div class="row">
	<h2 class="h1 products-section-title text-uppercase">
		{if $display_link_brand}<a href="{$page_link}" title="{l s='Our partners' mod='tmg_brandlogo'}">{/if}
			{l s='Our Partners' mod='tmg_brandlogo'}
		{if $display_link_brand}</a>{/if}
	</h2>
 
	<div class="products">

    	{if $brands}
     
			{assign var='sliderFor' value=5} <!-- Define Number of product for SLIDER -->
			{assign var='brandCount' value=count($brands)}
	
			{if $slider == 1 && $brandCount >= $sliderFor}
				<ul id="brand-carousel" class="tmg-carousel product_list">
			{else}
				<ul id="brand-grid" class="brand_grid product_list grid row gridcount">
			{/if}
	 
			{foreach from=$brands item=brand name=brand_list}
				<li class="{if $slider == 1 && $brandCount >= $sliderFor}item{else}product_item col-xs-12 col-sm-4 col-md-3{/if}">
					<div class="brand-image">
					<a href="{$link->getmanufacturerLink($brand['id_manufacturer'], $brand['link_rewrite'])}" title="{$brand.name}">
						<img src="{$link->getManufacturerImageLink($brand['id_manufacturer'])}" alt="{$brand.name}" />
					</a>
					</div>
					{if $brandname}
						<span class="h3 product-title" itemprop="name">
							<a class="product-name" itemprop="url"  href="{$link->getmanufacturerLink($brand['id_manufacturer'], $brand['link_rewrite'])}" title="{$brand.name}">{$brand.name}</a>
						</span>
					{/if}
				</li>
			{/foreach}
	 		
			</ul>
			{if $slider == 1 && $brandCount >= $sliderFor}
				<div class="customNavigation">
					<a class="btn prev brand_prev">&nbsp;</a>
					<a class="btn next brand_next">&nbsp;</a>
				</div>
			{/if}
		{else}
			<p>{l s='No brand' mod='tmg_brandlogo'}</p>
		{/if}
	</div>
	</div>
	</div>
</section>
