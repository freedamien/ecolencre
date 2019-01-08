{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div id="_desktop_cart">
  <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <div class="header blockcart-header dropdown js-dropdown">
	 
		<a class="link" rel="nofollow" href="{$cart_url}" {if $cart.products_count > 0} data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"{/if}> 
			<i class="material-icons">&#xE8CC;</i>
			<span class="cart-products-count">{$cart.products_count}</span>
			<div class="cart-data">
			<!--<span class="cart-title">{l s='Add to Cart' d='Shop.Theme.Actions'}</span>-->
			{if $cart.products_count > 1}
				<span class="cart-products-count">{$cart.products_count}&nbsp;{l s='Items -' d='Shop.Theme.Actions'}</span>
			{else if $cart.products_count == 1}
				<span class="cart-products-count">{$cart.products_count}&nbsp;{l s='Item -' d='Shop.Theme.Actions'}</span>
			{else}
				<span class="cart-products-count">{$cart.products_count}&nbsp;{l s='Item -' d='Shop.Theme.Actions'}</span>
			{/if}
			<span class="cart-value">{$cart.totals.total.value}</span>
			</div>
		</a>
		 		
	  {if $cart.products_count > 0}
		<div class="cart_block block exclusive dropdown-menu">
			<div class="block_content">
				<div class="cart_block_list">
				{foreach from=$cart.products item=product}  
					
					<div class="cart-item">					
						<div class="cart-image">
							 <a href="{$product.url}">
							 <img src="{$product.cover.bySize.cart_default.url}" alt="{$product.name|escape:'quotes'}">
							 </a>
						</div>
						
						<div class="cart-info">
						<span class="product-quantity">{$product.quantity}&nbsp;x</span>
						<span class="product-name"><a href="{$product.url}">{$product.name|truncate:10:'...'}</a></span>
						<span class="product-price">{$product.price}</span>
						<a class = "remove-from-cart"
							  rel                         = "nofollow"
							  href                        = "{$product.remove_from_cart_url}"
							  data-link-action            = "delete-from-cart"
							  data-id-product             = "{$product.id_product|escape:'javascript'}"
							  data-id-product-attribute   = "{$product.id_product_attribute|escape:'javascript'}"
							  data-id-customization   	  = "{$product.id_customization|escape:'javascript'}"
						>
							<i class="material-icons pull-xs-left">delete</i>
						</a>
						{if $product.customizations|count}
							<div class="customizations">
								<ul>
									{foreach from=$product.customizations item='customization'}
										<li>
											<span class="product-quantity">{$customization.quantity}</span>
											<a href="{$customization.remove_from_cart_url}" title="{l s='remove from cart' d='Shop.Theme.Actions'}" class="remove-from-cart" rel="nofollow">{l s='Remove' d='Shop.Theme.Actions'}</a>
											<ul>
												{foreach from=$customization.fields item='field'}
													<li>
														<span>{$field.label}</span>
														{if $field.type == 'text'}
															<span>{$field.text}</span>
														{else if $field.type == 'image'}
															<img src="{$field.image.small.url}">
														{/if}
													</li>
												{/foreach}
											</ul>
										</li>
									{/foreach}
								</ul>
							</div>
						{/if}
						</div>
					</div>
					
				{/foreach}
				</div>
				
				
			  <div class="card cart-summary">
					<div class="card-block">
						{foreach from=$cart.subtotals item="subtotal"}
						  {if $subtotal.value && $subtotal.type !== 'tax'}
							<div class="cart-summary-line" id="cart-subtotal-{$subtotal.type}">
							  <span class="label{if 'products' === $subtotal.type} js-subtotal{/if}">
								{if 'products' == $subtotal.type}
								  {$cart.summary_string}
								{else}
								  {$subtotal.label}
								{/if}
							  </span>
							  <span class="value">{$subtotal.value}</span>
							  {if $subtotal.type === 'shipping'}
								  <div><small class="value">{hook h='displayCheckoutSubtotalDetails' subtotal=$subtotal}</small></div>
							  {/if}
							</div>
						  {/if}
						{/foreach}						
					</div>
										
					<div class="card-block">
						<div class="cart-summary-line cart-total">
						  <span class="label">{$cart.totals.total.label} {$cart.labels.tax_short}</span>
						  <span class="value">{$cart.totals.total.value}</span>
						</div>
					
						<div class="cart-summary-line">
						  <small class="label">{$cart.subtotals.tax.label}</small>
						  <small class="value">{$cart.subtotals.tax.value}</small>
						</div>
				  </div>
			  </div>
			  
			  <div class="checkout card-block">
				  <a rel="nofollow" href="{$cart_url}" class="viewcart more">
				  	<button type="button" class="btn btn-primary add-to-cart">{l s='View Cart' d='Shop.Theme.Actions'}</button>
				  </a>
				  
				  {if $cart.minimalPurchaseRequired}			
					<button type="button" class="btn btn-primary disabled add-to-cart" disabled>{l s='Checkout' d='Shop.Theme.Actions'}</button>					
				  {else}
					{if isset($urls) }
						<a href="{$urls.pages.order}" rel="nofollow" class="more">
	
							<button type="button" class="btn btn-primary add-to-cart">{l s='Checkout' d='Shop.Theme.Actions'}</button>
						</a>
					 {else}	
						<a href="{$cart_url}" rel="nofollow" class="more">
							<button type="button" class="btn btn-primary add-to-cart">{l s='Checkout' d='Shop.Theme.Actions'}</button>
						</a>						
					{/if}
				  {/if}
				</div>
			  
				
			</div>
		</div>
      {/if}
    </div>
  </div>
</div>
