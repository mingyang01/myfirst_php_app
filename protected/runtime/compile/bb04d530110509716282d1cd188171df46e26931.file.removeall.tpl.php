<?php /* Smarty version Smarty-3.1.18, created on 2015-11-06 10:52:28
         compiled from "/home/work/websites/auth/protected/views/removeauth/removeall.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1808028103563b3128cf5652-49147025%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bb04d530110509716282d1cd188171df46e26931' => 
    array (
      0 => '/home/work/websites/auth/protected/views/removeauth/removeall.tpl',
      1 => 1446778346,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1808028103563b3128cf5652-49147025',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_563b3128d204f8_64018561',
  'variables' => 
  array (
    'user' => 0,
    'userAuth' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563b3128d204f8_64018561')) {function content_563b3128d204f8_64018561($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
                <li role="presentation"><a href="#">解除权限</a></li>
                <li role="presentation" class="active"><a href="/removeAuth/removeAll">解除所有权限</a></li>
                <li role="presentation"><a href="/removeAuth/recover">恢复权限</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
        	<div class="alert alert-danger">
        		一旦解除某人的所有权限，将无法恢复，请慎重操作！
        	</div>
            <div class="well clearfix">
                <div class="col-md-4">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="">被解除人</label>
                            <input name="user" type="text" placeholder="填写正确的邮箱前缀" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
"/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success submitBtn">提交</button>
                        </div>
                    </form>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['userAuth']->value) {?>
                <div class="col-md-8">
                    <button class="btn btn-info sure-remove-btn">一键解除该人所有权限</button>
                </div>
                <?php }?>
            </div>
        </div>
        <?php if ($_smarty_tpl->tpl_vars['userAuth']->value) {?>
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>被解除人</th><th>被解除项</th><th>类型</th>
                </tr>
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['userAuth']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                 <tr>
                    <td><?php echo $_smarty_tpl->tpl_vars['user']->value;?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</td>
                    <?php if ($_smarty_tpl->tpl_vars['item']->value['type']==1) {?> <td>功能</td><?php } else { ?> <td>角色</td><?php }?>
                 </tr>
                <?php } ?>
            </table>
        </div>
        <?php }?>
    </div>
</div>
<script>
    var user = '<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
';
    $('.sure-remove-btn').click(function(){
        window.location = "/removeAuth/removeAll?user="+user+"&remove=true"
    })
</script><?php }} ?>
