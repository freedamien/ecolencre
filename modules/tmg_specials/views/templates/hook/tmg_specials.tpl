{*
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

<section class="special-products hb-animate-element left-to-right">
 <div class="container">
 	<div class="row">
	<h2 class="h1 products-section-title text-uppercase">
		<a href="#">{l s='Special products' mod='tmg_specials'}</a>
	</h2>
   <div class="products tab-container">
   <div class="homeproducts-row">
      {assign var='sliderFor' value=9} <!-- Define Number of product for SLIDER -->
      {if $slider == 1 && $no_prod >= $sliderFor}
	  <div class="special-carousel">
      <ul id="special-carousel" class="special-carousel tmg-carousel product_list">
         {assign var='specialcount' value=0}
         {assign var='specialltotalcount' value=0}
         {foreach from=$products item=product name=homespecialProducts}
         {$specialltotalcount = $specialcount++}
         {/foreach}
         {if $specialcount > 4 && $slider == 1}
         {foreach from=$products item="product" name=homespecialProducts}
         {if $smarty.foreach.homespecialProducts.index % 2 == 0} 
         <li class="specialproductlistitem">
            <ul>
               {/if}	
               <li class="{if $slider == 1 && $no_prod >= $sliderFor}item col-xs-12{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
                  {include file="catalog/_partials/miniatures/product.tpl" product=$product}
               </li>
               {if $smarty.foreach.homespecialProducts.index % 2 != 0} 
            </ul>
         </li>
         {/if}
         {/foreach}
         {/if}
      </ul>
	  </div>
      {else}
      <ul id="special-grid" class="special-grid grid row gridcount product_list">
         {foreach from=$products item="product"}
         <li class="{if $slider == 1 && $no_prod >= $sliderFor}item col-xs-12{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
         </li>
         {/foreach} 
      </ul>
   			  <a class="all-product-link pull-xs-left pull-md-right h4" href="{$allSpecialProductsLink}">
				{l s='View More' mod='tmg_specials'}
			</a>		
      {/if}						
   </div>
   <div class="banner-block">
				{hook h='displayHomeBottom'}
		</div>
   {if $slider == 1 && $no_prod >= $sliderFor}
  	 <div class="customNavigation">
				<a class="btn prev special_prev">&nbsp;</a>
				<a class="btn next special_next">&nbsp;</a>
			</div>
   {/if}			
   </div>
   </div>
   </div>
</section>
