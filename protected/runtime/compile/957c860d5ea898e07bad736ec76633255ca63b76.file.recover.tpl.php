<?php /* Smarty version Smarty-3.1.18, created on 2015-11-06 11:24:52
         compiled from "/home/work/websites/auth/protected/views/removeauth/recover.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14091655635603c53dbe5245-10305047%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '957c860d5ea898e07bad736ec76633255ca63b76' => 
    array (
      0 => '/home/work/websites/auth/protected/views/removeauth/recover.tpl',
      1 => 1446779162,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14091655635603c53dbe5245-10305047',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5603c53dc2eea4_18298313',
  'variables' => 
  array (
    'data' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5603c53dc2eea4_18298313')) {function content_5603c53dc2eea4_18298313($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
                <li role="presentation" ><a href="/removeAuth/index">解除权限</a></li>
                <li role="presentation"><a href="/removeAuth/removeAll">解除所有权限</a></li>
                <li role="presentation" class="active"><a href="#">恢复权限</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <div class="form-group">
                    <button class="btn btn-success submitBtn">恢复权限</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom:50px;">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" ></th><th>用户</th><th>功能</th><th>操作人</th><th>操作时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                    <tr>
                        <th data-id="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><input type="checkbox" ></th><th><?php echo $_smarty_tpl->tpl_vars['item']->value['user'];?>
</th><th><?php echo $_smarty_tpl->tpl_vars['item']->value['op_item'];?>
</th><th><?php echo $_smarty_tpl->tpl_vars['item']->value['op_user'];?>
</th><th><?php echo $_smarty_tpl->tpl_vars['item']->value['op_time'];?>
</th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$('.submitBtn').click(function(){
    var ids = '';
    $('input').each(function(){
        var flag = $(this).prop("checked");
        if(flag){
            ids += $(this).parent().attr('data-id')+','
        }
    })
    ids=ids.substring(0,ids.length-1);
    if(!ids){
        alert("请勾选");
    }
    $.post('/removeAuth/recoveRAuth', {ids:ids}, function(data) {
        if(data){
            alert(data)
        }
        window.location.reload();
    },'json');
})
</script>
<?php }} ?>
