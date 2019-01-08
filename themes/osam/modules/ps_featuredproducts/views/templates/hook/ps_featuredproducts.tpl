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
<section class="featured-products clearfix">
	<h1 class="h1 products-section-title text-uppercase ">
		<a href="#">{l s='Popular Products' d='Shop.Theme.Catalog'}</a>
	</h1>
	
	<div class="products">
		<ul class="featured_grid product_list grid row gridcount">
		{foreach from=$products item="product"}
			<li class="product_item col-xs-12 col-sm-6 col-md-4 col-lg-3">
			{include file="catalog/_partials/miniatures/product.tpl" product=$product}
			</li>
		{/foreach}
		</ul>
			<a class="all-product-link pull-xs-left pull-md-right h4" href="{$allProductsLink}">
				{l s='view more' d='Shop.Theme.Catalog'}
			</a>
		</div>
</section>