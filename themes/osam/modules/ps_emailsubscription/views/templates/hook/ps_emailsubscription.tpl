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

<div class="block_newsletter  col-md-12 col-sm-12 hb-animate-element top-to-bottom">
  		<div class="container">
			<div class="row">
	   <div class="blog-title col-xs-12"><span class="envelope"></span><span class="title">
	  {l s='Our Newsletter' d='Shop.Theme.Global'}</span> 
	    <p>{l s='Whether you need to sell your products, share some big news' d='Shop.Theme.Global'}</p>
	   </div>
		<div class="block-details col-md-12 col-xs-12">
		
			 
		  <form action="{$urls.pages.index}#footer" method="post">
			   <div class="input-wrapper">
				  <input
					name="email"
					type="text"
					value="{$value}"
					placeholder="{l s='Your email address' d='Shop.Forms.Labels'}"
				  >
				</div>
				<span class="more">		
				<input
              class="btn btn-primary float-xs-right add-to-cart hidden-xs-down"
              name="submitNewsletter"
              type="submit"
              value="{l s='Subscribe' d='Shop.Theme.Actions'}"
            >
            <input
              class="btn btn-primary float-xs-right add-to-cart hidden-sm-up"
              name="submitNewsletter"
              type="submit"
              value="{l s='OK' d='Shop.Theme.Actions'}"
            >

				</span>
				
				<input type="hidden" name="action" value="0">
				<div class="clearfix"></div>

							  <div class="col-xs-12">
				<!--  {if $conditions}
					<p>{$conditions}</p>
				  {/if} -->
				  {if $msg}
					<p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
					  {$msg}
					</p>
				  {/if}
			  </div> 
		 </form>
		</div>
	 	</div>
	  </div>
</div>
