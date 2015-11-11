<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 18:32:16
         compiled from "/home/work/websites/auth/protected/views/tools/showFunctionUser.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1835037655563b3030645e26-06257653%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0ae870ac99ebfea3bcb250900036edebe24996e' => 
    array (
      0 => '/home/work/websites/auth/protected/views/tools/showFunctionUser.tpl',
      1 => 1441534773,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1835037655563b3030645e26-06257653',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'business' => 0,
    'funname' => 0,
    'total' => 0,
    'users' => 0,
    'item' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_563b30307324b6_81517660',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563b30307324b6_81517660')) {function content_563b30307324b6_81517660($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">运营工具</li>
	            <li class="active">查询功能有权限用户</li>
	        </ol>
			<!-- Tab panes -->
	        <div id="well" class="well">
	            <div class="form-inline" role="form" id="form">
	                <div class="form-group">
	                    <label for="exampleInputName2">业务号：</label>
	                    <input type="text" class="form-control" name="business" id="business" placeholder="比如：focus" value="<?php echo $_smarty_tpl->tpl_vars['business']->value;?>
">
	                </div>
	                <div class="form-group">
	                    <label for="exampleInputName2">功能名称：</label>
	                    <input type="text" class="form-control" id="funname" name="funname" placeholder="完整的功能名称" value="<?php echo $_smarty_tpl->tpl_vars['funname']->value;?>
">
	                </div>
	                <button id="views" type="button" class="btn btn-default" onclick="search_info(0)">查看</button>
	                <button id="views" type="button" class="btn btn-sm btn-default" onclick="search_info(1)">下载</button>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
	<div class="col-md-12">
		共有<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
条
	</div>
		<div class="col-md-12">
    	<table class="table table-hover table-striped table-bordered table-condensed">
        	 <thead>
                <tr align="center">
                	<th width="15%">功能名称</th>
                	<th width="10%">用户uid</th>
                    <th width="15%">用户名</th>
                    <th width="15%">部门名称</th>
                    <th width="20%">权限对应角色</th>
                </tr>
            </thead>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'-'key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key'-'key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
            <tr>
            	<td align=center><?php echo $_smarty_tpl->tpl_vars['funname']->value;?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['userid'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['depart'];?>
</td>
                <td align=center><?php echo $_smarty_tpl->tpl_vars['item']->value['itemname'];?>
</td>
            </tr>
            <?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
            	<tr><td colspan=4 align=center><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</td></tr>
            <?php } ?>
        </table>
	</div>
	</div>
</div>

<script type="text/javascript">

	function search_info(excel) {
		var business = $("#business").val();
		var funname = $("#funname").val();
		if (business){
			window.location.href = "/function/getFunctionsRight?business="+business+"&funname="+funname+"&excel="+excel;
		}
	}

</script>

<?php }} ?>
