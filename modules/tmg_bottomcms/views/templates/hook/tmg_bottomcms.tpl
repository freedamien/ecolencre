{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="tmgbottomcms" class="parallax-comment" data-source-url="{$image_url}/testimonial-comment.jpg">
   {if $tmgcms_infos.text == 'add your code here'}
		<div class="testimonial_main">
		<div class="container">
		<div class="row">
		<div class="products hb-animate-element top-to-bottom">
		<ul id="testimonial-carousel" class="tmg-carousel product_list">
		<li class="item item1">
		<div class="item cms_face">
		<div class="testmonial-image"><img alt="testmonial" title="testmonial" src="modules/tmg_bottomcms/views/img/T1.png" />
		</div>
		<div class="product_inner_cms">
		<div class="testmonial-user">
		<div class="name"><a href="#">Mr. Dhaval Hidad</a></div>
		<div class="desig">Web Designer</div>
		</div>
		<div class="des">
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
		when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
		</div>
		</div>
		</div>
		</li>
		<li class="item item2">
		<div class="item cms_face">
		<div class="testmonial-image"><img alt="testmonial" title="testmonial" src="modules/tmg_bottomcms/views/img/T2.png" />
		</div>
		<div class="product_inner_cms">
		<div class="testmonial-user">
		<div class="name"><a href="#">Ms. Binali Patel</a></div>
		<div class="desig">Web devloper</div>
		</div>
		<div class="des">
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
		when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
		</div>
		</div>
		</div>
		</li>
		<li class="item item3">
	    <div class="item cms_face">
		<div class="testmonial-image"><img alt="testmonial" title="testmonial" src="modules/tmg_bottomcms/views/img/T3.png" />
		</div>
		<div class="product_inner_cms">
		<div class="testmonial-user">
		<div class="name"><a href="#">Mr. Manish Savaliya</a></div>
		<div class="desig">Marketing</div>
		</div>
		<div class="des">
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
		when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
		</div>
		</div>
		</div>
		</li>
		</ul>
		<div class="customNavigation"><a class="btn prev tmgtestimonial_prev">prev</a> <a class="btn next tmgtestimonial_next">next</a></div>
		</div>
		</div>
		</div>
		</div>
	 {else}
	   	{$tmgcms_infos.text nofilter}
	{/if}
</div>
