<?php /* Smarty version Smarty-3.1.18, created on 2015-08-24 11:21:17
         compiled from "/home/work/websites/auth/protected/views/error/404.tpl" */ ?>
<?php /*%%SmartyHeaderCode:90620794155da8dad869f83-85283993%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c47485affba4edfe3a94dc59a792c3bc33908806' => 
    array (
      0 => '/home/work/websites/auth/protected/views/error/404.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '90620794155da8dad869f83-85283993',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55da8dad8a16b3_88884551',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55da8dad8a16b3_88884551')) {function content_55da8dad8a16b3_88884551($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <div class="container">
            <div class="header" style="margin-top:100px"></div>
            <div class="row">
                <div class="span12" style="text-align:center">
            <h1><?php if ($_smarty_tpl->tpl_vars['message']->value) {?><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
<?php } else { ?>您访问的页面正在保养中!<?php }?></h1>   
                </div>
            </div>
            <div class="row">
                <div class="span12" style="text-align:center">
                <img src="/assets/images/404.jpg" alt="" />
            </div>
            </div>
        </div>
    </body>
</html><?php }} ?>
