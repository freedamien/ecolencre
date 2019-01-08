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


<section class="featured-products clearfix">
	<h2 class="h1 products-section-title text-uppercase">
		{l s='Featured products' mod='tmg_featuredproducts'}
	</h2>
   <div class="products tab-container">
   <div class="homeproducts-row">
      {assign var='sliderFor' value=9} <!-- Define Number of product for SLIDER -->
      {if $slider == 1 && $no_prod >= $sliderFor}
	  <div class="feature-carousel">
      <ul id="feature-carousel" class="feature-carousel tmg-carousel product_list">
         {assign var='featurecount' value=0}
         {assign var='featureltotalcount' value=0}
         {foreach from=$products item=product name=homefeatureProducts}
         {$featureltotalcount = $featurecount++}
         {/foreach}
         {if $featurecount > 4 && $slider == 1}
         {foreach from=$products item="product" name=homefeatureProducts}
         {if $smarty.foreach.homefeatureProducts.index % 2 == 0} 
         <li class="featureproductlistitem">
            <ul>
               {/if}	
               <li class="{if $slider == 1 && $no_prod >= $sliderFor}item col-xs-12{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
                  {include file="catalog/_partials/miniatures/product.tpl" product=$product}
               </li>
               {if $smarty.foreach.homefeatureProducts.index % 2 != 0} 
            </ul>
         </li>
         {/if}
         {/foreach}
         {/if}
      </ul>
	  </div>
      {else}
      <ul id="feature-grid" class="feature-grid grid row gridcount product_list">
         {foreach from=$products item="product"}
         <li class="{if $slider == 1 && $no_prod >= $sliderFor}item col-xs-12{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
         </li>
         {/foreach} 
      </ul>
      <a class="all-product-link float-xs-left pull-md-right h4" href="{$allProductsLink}">
      {l s='View more' mod='tmg_featuredproducts'}
      </a>			
      {/if}						
   </div>
   {if $slider == 1 && $no_prod >= $sliderFor}
  			 <div class="customNavigation">
				<a class="btn prev feature_prev">&nbsp;</a>
				<a class="btn next feature_next">&nbsp;</a>
			</div>
   {/if}			
   </div>
</section>