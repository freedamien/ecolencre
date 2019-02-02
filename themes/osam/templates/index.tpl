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
{extends file='page.tpl'}

    {block name='page_content_container'}
      <section id="content" class="page-home">
        {block name='page_content_top'}{/block}
        
		<!-- ThemeMagics start -->
			<div class="topcolumn">
				{hook h='displayTopColumn'}
			</div>
			
			<section class="tmg-hometabcontent">
				<div class="container">	
					<div class="row">
					<h2 class="h1 products-section-title text-uppercase">{l s='Trending Items' d='Shop.Theme.Global'}</h2>
						<div class="tabs">
							<ul id="home-page-tabs" class="nav nav-tabs clearfix">
								<li class="nav-item hb-animate-element left-to-right">
									<a data-toggle="tab" href="#featureProduct" class="nav-link active" data-text="{l s='Featured products' d='Shop.Theme.Global'}">
										<span>{l s='Featured products' d='Shop.Theme.Global'}</span>
									</a>
								</li>
								<li class="nav-item hb-animate-element bottom-to-top">
									<a data-toggle="tab" href="#newProduct" class="nav-link" data-text="{l s='New products' d='Shop.Theme.Global'}">
										<span>{l s='New products' d='Shop.Theme.Global'}</span>
									</a>
								</li>
								<li class="nav-item hb-animate-element right-to-left">
									<a data-toggle="tab" href="#bestseller" class="nav-link" data-text="{l s='Best Sellers' d='Shop.Theme.Global'}">
										<span>{l s='Best Sellers' d='Shop.Theme.Global'}</span>
									</a>
								</li>
							</ul>
							<div class="tab-content hb-animate-element left-to-right">
								<div id="featureProduct" class="tmg_productinner tab-pane active">	
									{hook h='displayTmgFeature'}
								</div>
								<div id="newProduct" class="tmg_productinner tab-pane">
									{hook h='displayTmgNew'}
								</div>
								<div id="bestseller" class="tmg_productinner tab-pane">
									{hook h='displayTmgBestseller'}
								</div>
							</div>					
						</div>
					</div>					
				</div>
			</section>

		<!-- ThemeMagics end -->
		
		{block name='page_content'}
          {$HOOK_HOME nofilter}
        {/block}
      </section>
    {/block}
	
	
