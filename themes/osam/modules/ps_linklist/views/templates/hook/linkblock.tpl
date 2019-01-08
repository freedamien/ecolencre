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
<div class="footer-links links">
{foreach $linkBlocks as $linkBlock}
<div class="block footer-link col-md-3 links wrapper hb-animate-element left-to-right">
  <h3 class="h3 hidden-md-down">{$linkBlock.title}</h3> 
  {assign var=_expand_id value=10|mt_rand:100000}
  <div class="title h3 block_title hidden-lg-up" data-target="#footer_sub_menu_{$_expand_id}" data-toggle="collapse">
	<span class="">{$linkBlock.title}</span>
	<span class="pull-xs-right">
	  <span class="navbar-toggler collapse-icons">
		<i class="material-icons add">&#xE313;</i>
		<i class="material-icons remove">&#xE316;</i>
	  </span>
	</span>
  </div>
  <ul id="footer_sub_menu_{$_expand_id}" class="collapse block_content">
	{foreach $linkBlock.links as $link}
	  <li>
		<a
			id="{$link.id}-{$linkBlock.id}"
			class="{$link.class}"
			href="{$link.url}"
			title="{$link.description}">
		  {$link.title}
		</a>
	  </li>
	{/foreach}
  </ul>
</div>
{/foreach}
</div>
