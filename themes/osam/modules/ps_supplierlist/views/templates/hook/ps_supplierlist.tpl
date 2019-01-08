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

<div id="suppliers_list" class="block">
  <h4 class="block_title hidden-md-down">
  	{if $display_link_supplier}<a href="{$page_link}" title="{l s='Suppliers' d='Shop.Theme.Catalog'}">{/if}
      {l s='Suppliers' d='Shop.Theme.Catalog'}
    {if $display_link_supplier}</a>{/if}  
  </h4>
  <h4 class="block_title hidden-lg-up" data-target="#suppliers_list_toggle" data-toggle="collapse">
    {if $display_link_supplier}<a href="{$page_link}" title="{l s='Suppliers' d='Shop.Theme.Catalog'}">{/if}
      {l s='Suppliers' d='Shop.Theme.Catalog'}
    {if $display_link_supplier}</a>{/if}
	  
	<span class="pull-xs-right">
	  <span class="navbar-toggler collapse-icons">
		<i class="material-icons add">&#xE313;</i>
		<i class="material-icons remove">&#xE316;</i>
	  </span>
	</span>
	  
  </h4>
  <div id="suppliers_list_toggle" class="block_content collapse">
    {if $suppliers}
      {include file="module:ps_supplierlist/views/templates/_partials/$supplier_display_type.tpl" suppliers=$suppliers}
    {else}
      <p>{l s='No supplier' d='Shop.Theme.Catalog'}</p>
    {/if}
  </div>
</div>
