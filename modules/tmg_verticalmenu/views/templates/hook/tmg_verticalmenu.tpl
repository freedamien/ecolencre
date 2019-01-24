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

{function name="tmgmenu" nodes=[] depth=0 parent=null}
  {strip}
    {if $nodes|count}
      <ul class="top-vertical-menu block_content" {if $depth == 0}id="top-vertical-menu"{/if} data-depth="{$depth}">
        {foreach from=$nodes item=node}
          <li class="{$node.type}{if $node.current} current{/if}" id="{$node.page_identifier}">
            <a href="{$node.url}" class="{if $depth >= 0}dropdown-item{/if}{if $depth === 1} dropdown-submenu{/if}" data-depth="{$depth}" {if $node.open_in_new_window} target="_blank" {/if}>
			{if $node.children|count}
			  {* Cannot use page identifier as we can have the same page several times *}
			  {assign var=_expand_id value=10|mt_rand:100000}
			  <span class="pull-xs-right hidden-md-up">
				<span data-target="#top_sub_menu_{$_expand_id}" data-toggle="collapse" class="navbar-toggler collapse-icons">
				  <i class="material-icons add">&#xE313;</i>
				  <i class="material-icons remove">&#xE316;</i>
				</span>
			  </span>
			  <span class="pull-xs-right sub-menu-arrow">
			  	<i class="material-icons right">&#xE313;</i>			  	
			  </span>
			{/if}
			{$node.label}</a>

             {if $node.children|count}
			  {* Cannot use page identifier as we can have the same page several times *}
			  <div {if $depth === 0} class="popover sub-menu js-sub-menu collapse"{else} class="collapse"{/if} id="top_sub_menu_{$_expand_id}">
				   {tmgmenu nodes=$node.children depth=$node.depth}
					<div class="menu-images-container">
					  {foreach from=$node.image_urls item=image_url}
						<img src="{$image_url}" alt="banner-1">
					  {/foreach}
					</div>
			  </div>
			  {/if}
          </li>
        {/foreach}
      </ul>
    {/if}
  {/strip}
{/function}

<div class="vertical-menu js-top-menu position-static hidden-sm-down col-md-3"  id="_desktop_vertical_menu">
<div class="block-title block">		
<h4 class="block_title hidden-lg-up" data-target="#top-vertical-menu" data-toggle="collapse">
		<span class="pull-xs-right">
		  <span class="navbar-toggler collapse-icons">
			<i class="material-icons add">&#xE315;</i>
			<i class="material-icons remove">&#xE315;</i>
		  </span>
		</span>
</h4>

		<div class="cat-title">{l s='Categories' mod='tmg_verticalmenu'}</div>
		<div class="title-arrow"></div>

</div>
    {tmgmenu nodes=$tmgmenu.children}
</div>
