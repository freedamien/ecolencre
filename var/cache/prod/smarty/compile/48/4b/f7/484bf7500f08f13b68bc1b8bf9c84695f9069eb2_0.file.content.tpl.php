<?php
/* Smarty version 3.1.33, created on 2019-01-08 09:53:39
  from '/home/damien/Projets/ecolencre/admin663eczakx/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5c3465139bf8f6_98421700',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '484bf7500f08f13b68bc1b8bf9c84695f9069eb2' => 
    array (
      0 => '/home/damien/Projets/ecolencre/admin663eczakx/themes/default/template/content.tpl',
      1 => 1545054348,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c3465139bf8f6_98421700 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
