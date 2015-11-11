<?php /* Smarty version Smarty-3.1.18, created on 2015-09-23 19:30:42
         compiled from "/home/work/websites/auth/protected/views/publish/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:204132719856028d622f6c65-47216704%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a95a855ac6475c849ae35b947c1b09f4bd139ca' => 
    array (
      0 => '/home/work/websites/auth/protected/views/publish/index.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '204132719856028d622f6c65-47216704',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'redata' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56028d6237b027_64496860',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56028d6237b027_64496860')) {function content_56028d6237b027_64496860($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="container">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">项目发布</li>
        </ol>
        <div id="well" class="well clearfix">
            <div class="col-sm-6">
                <button id="add" class="btn btn-default">添加</button>
                <button id="update" class="btn btn-default">修改</button>
                <button id="delete" class="btn btn-default">删除</button>
            </div>
            <div class="col-sm-1 right" style="margin-right:4px;">
                <button id="white-manage" class="btn btn-default" plain="true">白名单管理</button>
            </div>

            <div class="col-sm-1 right">
                <button id="menu-manage" class="btn btn-default" plain="true">菜单管理</button>
            </div>

            <div class="col-sm-1 right">
                <button id="function-manage" class="btn btn-default" plain="true">功能管理</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-bottom:30px;">
        <table id="dg" rownumbers="true" style="border:none;" fitColumns="true" singleSelect="true">
            <thead>
                <tr>
                    <th data-options="field:'id',checkbox:true"></th>
                    <th field="name" width="40">项目</th>
                    <th field="cname" width="50">项目名称</th>
                    <th field="desc" width="50">域名</th>
                    <th field="leader" width="50">负责人</th>
                    <th field="time" width="50">更新时间</th>

                    <th field="developer" width="50">开发人</th>
                    <th field="creator" width="50">创建人</th>
                </tr>
            </thead>
            <tbody>
                <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['redata']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                <?php if ($_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['business']!="work") {?>
                <tr>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['id'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['business'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['cname'];?>
</td>
                    <td><a href="http://<?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['description'];?>
"><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['description'];?>
</a></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['leader'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['unix'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['developer'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['redata']->value[$_smarty_tpl->tpl_vars['i']->value]['creator'];?>
</td>
                </tr>
                <?php }?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
    <div id="dlg" class="easyui-dialog" style="width:400px;height:auto; padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" novalidate role="form">
            <div class="form-group" style="display:none;">
                <label class="text-right">id:</label>
                <input name="id" class="easyui-textbox" required="true">
            </div>
            <div class="form-group">
                <label class="text-right">项目:</label>&nbsp; &nbsp;<font color="red">(*业务ID，由英文字母和数字组成,创建后不能修改)</font>
                <input id="businessname" class="form-control" name="name" required="true" placeholder="业务ID，由英文字母和数字组成,创建后不能修改">
            </div>
            <div class="form-group">
                <label class="text-right">开发人:</label>&nbsp; &nbsp;<font color="red">(*开发者默认拥有项目的所有功能权限)</font>
                <input id="developer" class="form-control" name="developer" required="true" placeholder="开发者默认拥有项目的所有功能权限">
            </div>
            <div class="form-group">
                <label class="text-right">负责人:</label>
                <input class="form-control" name="leader" placeholder="负责人">
            </div>
            <div class="form-group">
                <label class="text-right">项目名称:</label>
                <input class="form-control" name="cname" placeholder="项目名称">
            </div>
            <div class="input-group">
                <span class="input-group-addon">http://</span>
                <input class="form-control" name="desc" required="true" placeholder="域名">
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <button href="javascript:void(0)" class="btn btn-default" onclick="saveUser()">保存</button>
        <button href="javascript:void(0)" class="btn btn-default" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">退出</button>
    </div>

</div>
<script type="text/javascript" src="/assets/js/publish.js"></script><?php }} ?>
