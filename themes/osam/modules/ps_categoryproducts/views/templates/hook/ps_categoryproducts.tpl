{*
* 2007-2017 PrestaShop
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
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<section class="productscategory-products clearfix">
	<h2 class="h1 products-section-title text-uppercase"><a href="#">
	{if $products|@count == 1}
		{l s='%s other product in the same category:' sprintf=[$products|@count] d='Shop.Theme.Catalog'}
	{else}
		{l s='%s other products in the same category:' sprintf=[$products|@count] d='Shop.Theme.Catalog'}
	{/if}
	</a></h2>
  
	<div class="products">
		{assign var='sliderFor' value=4} <!-- Define Number of product for SLIDER -->
		{assign var='productCount' value=count($products)}
		
		{if $productCount >= $sliderFor}
			<ul id="productscategory-carousel" class="tmg-carousel product_list">
		{else}
			<ul id="productscategory-grid" class="productscategory_grid product_list grid row gridcount">
		{/if}
	
		{foreach from=$products item="product"}
			<li class="{if $productCount >= $sliderFor}item{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-4{/if}">
				{include file="catalog/_partials/miniatures/product.tpl" product=$product}
			</li>
		{/foreach}
		</ul>
		
		{if $productCount >= $sliderFor}
			<div class="customNavigation">
				<a class="btn prev productscategory_prev">&nbsp;</a>
				<a class="btn next productscategory_next">&nbsp;</a>
			</div>
		{/if}
		
	</div>
</section>
