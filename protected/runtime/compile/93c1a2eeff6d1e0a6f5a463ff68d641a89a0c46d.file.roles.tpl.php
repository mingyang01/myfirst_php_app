<?php /* Smarty version Smarty-3.1.18, created on 2015-09-23 19:55:08
         compiled from "/home/work/websites/auth/protected/views/authority/roles.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2860933985602931ce0ed31-25334769%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '93c1a2eeff6d1e0a6f5a463ff68d641a89a0c46d' => 
    array (
      0 => '/home/work/websites/auth/protected/views/authority/roles.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2860933985602931ce0ed31-25334769',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'choice_depart' => 0,
    'depart' => 0,
    'key' => 0,
    'departid' => 0,
    'item' => 0,
    'data' => 0,
    'i' => 0,
    'departname' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5602931ceaac39_90322726',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5602931ceaac39_90322726')) {function content_5602931ceaac39_90322726($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>

<div class="container">
<div class="row">
    <div class="col-md-12" style="padding-top:20px;">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">角色管理</li>
        </ol>

        <div id="well" class="well">
            <div class="form-inline" role="form" id="form">
            	<?php if ($_smarty_tpl->tpl_vars['choice_depart']->value) {?>
                <div class="form-group">
                    <select name="function" class="form-control" id="depart">
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['depart']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                    	<?php if ($_smarty_tpl->tpl_vars['key']->value!=$_smarty_tpl->tpl_vars['departid']->value) {?>
                    		<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
                    	<?php }?>
                    <?php } ?>
                    </select>
                </div>
                <?php }?>
                <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['departid']->value];?>
" disabled/>
                <button data-action="create" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">添加</button>
               <button class="btn btn-default" id="delete">删除</button>

                <div class="col-sm-1 right">
                    <button id="tool-auth-manager" class="btn btn-default">角色权限</button>
                </div>

                <div class="col-sm-1 right">
                    <button id="tool-user-manager" class="btn btn-default">角色成员</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 tablemain" style="margin-bottom:30px;">
        <table id="dg"  class="easyui-datagrid"
                rownumbers="true" style="border:none;" fitColumns="true" singleSelect="true">
            <thead>

                <tr>
                    <th data-options="field:'id',checkbox:true"></th>
                    <th field="name" width="50">名字</th>
                    <th field="item" width="30">部门</th>
                    <th field="disc" width="20">描述</th>
                    <th field="rule" width="20">规则</th>
                    <th field="data" width="50">数据</th>     
                </tr>
            </thead>
                  
                 <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                    <tr>    
                        <td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['i']->value]['id'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['i']->value]['name'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['departname']->value;?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['i']->value]['description'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['i']->value]['bizrule'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['i']->value]['data'];?>
</td>
                    </tr>
                <?php }
if (!$_smarty_tpl->tpl_vars['u']->_loop) {
?>
                  <div>"<?php echo $_smarty_tpl->tpl_vars['departname']->value;?>
"部门下没有角色</div> 
                <?php } ?>
             
        </table>
    </div>
    
</div>    
    <div id="dlg" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="fm" class="form-horizontal" role="form">
                    	<input type="hidden" id="id" name="id" required="true">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名字:</label>
                            <div class="col-sm-9">
                                <input id="name" name="name" class="form-control" required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">部门:</label>
                            <div class="col-sm-9">
                                <input id = "depart_add" name="item" readOnly class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">描述:</label>
                            <div class="col-sm-9">
                                <input class="form-control" name="disc" /></li>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">规则:</label>
                            <div class="col-sm-9">
                                <input class="form-control" name="rule" /></li>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">数据:</label>
                            <div class="col-sm-9">
                                <input name="data" class="form-control" required="true">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button id="submit" type="button" class="btn btn-default">确认</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<script>
    var depart = "<?php echo $_smarty_tpl->tpl_vars['departname']->value;?>
";
    var departid = "<?php echo $_smarty_tpl->tpl_vars['departid']->value;?>
"; 
    var role_pre = "<?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['departid']->value];?>
"
</script>
<script type="text/javascript" src="/assets/js/roles.js"></script><?php }} ?>
