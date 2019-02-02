{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='brand'}
	<li class="brand col-xs-12">
	<div class="brand-inner">
		<div class="row">
		<div class="brand-img left-side col-xs-12 col-sm-3">
			<a href="{$brand.url}"><img src="{$brand.image}" alt="{$brand.name}"></a>
		</div>
		
		<div class="brand-infos middle-side col-xs-12 col-sm-5">
			<h3 class="h3 product-title"><a href="{$brand.url}">{$brand.name}</a></h3>
			{$brand.text nofilter}
		</div>
		
		<div class="brand-products right-side col-xs-12 col-sm-4">
			<p class="product-counter"><a href="{$brand.url}">{$brand.nb_products}</a></p>
			<a href="{$brand.url}" class="btn btn-primary">{l s='View products' d='Shop.Theme.Actions'}</a>
		</div>
	</div>
	</div>
	</li>
{/block}
