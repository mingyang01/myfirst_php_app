<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 18:32:52
         compiled from "/home/work/websites/auth/protected/views/removeauth/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:51323905156013a54c0e5c7-46313196%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e58280220e75744fb16e9ed4a906601435b47eef' => 
    array (
      0 => '/home/work/websites/auth/protected/views/removeauth/index.tpl',
      1 => 1446719087,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51323905156013a54c0e5c7-46313196',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56013a54c53739_81607788',
  'variables' => 
  array (
    'project' => 0,
    'business' => 0,
    'item' => 0,
    'functioname' => 0,
    'user' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56013a54c53739_81607788')) {function content_56013a54c53739_81607788($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<style>
    .sortBtn{
        display:inline-block;
        width: 20px;
        padding-left: 5px;
        cursor:pointer;
    }
</style>
<div class="container">
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">解除权限</li>
            </ol>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a href="#">解除权限</a></li>
                <li role="presentation"><a href="/removeAuth/removeAll">解除所有权限</a></li>
                <li role="presentation"><a href="/removeAuth/recover">恢复权限</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <form class="form-inline">
                    <div class="form-group">
                        <label for="">平台：</label>
                        <select name="business" class="form-control">
                            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                                <option <?php if ($_smarty_tpl->tpl_vars['business']->value==$_smarty_tpl->tpl_vars['item']->value['business']) {?> selected <?php }?>value="<?php echo $_smarty_tpl->tpl_vars['item']->value['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['business'];?>
--<?php echo $_smarty_tpl->tpl_vars['item']->value['cname'];?>
</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">功能名称</label>
                        <input name="functioname" type="text" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['functioname']->value;?>
" />
                    </div>
                     <div class="form-group">
                        <label for="">解除人</label>
                        <input name="user" type="text" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success submitBtn">提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-12">
                <?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
                <div class="alert alert-danger">
                    <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

                </div>
                <?php }?>
            </div>
        </div>
</div>
<?php }} ?>
