<?php /* Smarty version Smarty-3.1.18, created on 2015-08-28 16:38:59
         compiled from "/home/work/websites/auth/protected/views/apply/myapply.tpl" */ ?>
<?php /*%%SmartyHeaderCode:71548682455e01e23674ad0-71542247%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '398fc6a0c72c82afceba40cfb0a3bf2cd1d75f24' => 
    array (
      0 => '/home/work/websites/auth/protected/views/apply/myapply.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '71548682455e01e23674ad0-71542247',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'key' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55e01e23749e82_11540879',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e01e23749e82_11540879')) {function content_55e01e23749e82_11540879($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">申请管理</li>
	        </ol>
		</div>
		<div class="col-md-12">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation">
					<a href="/apply/index"><i class="glyphicon glyphicon-send"></i> 权限申请</a>
				</li>
				<li role="presentation" class="active">
					<a  href="/apply/myapply"><i class="glyphicon glyphicon-folder-open"></i> 我的申请</a>
				</li>
				<li role="presentation">
					<a href="/apply/applycheck"><i class="glyphicon glyphicon-bell"></i> 权限审批</a>
				</li>
				<li role="presentation">
					<a href="/function/getFunnameByUrl"><i class="glyphicon glyphicon-bell"></i> 根据url获取功能信息</a>
				</li>
			</ul>
			<!-- Tab panes -->
		</div>
	</div>
</div>
<div class="container" style="padding-top:20px;">
	<div class="row" style="height:380px;overflow:auto;">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead class="thead">
					<tr class="active">
						<th colspan=2 class="col-sm-3" style="line-height:50px;"><i class="glyphicon glyphicon-list"></i> 您所申请的条目</th>
						<th rowspan=2 class="col-sm-1" style="line-height:60px;"><i class="glyphicon glyphicon-time"></i> 申请时间</th>
						<th colspan=3 class="text-center">进展情况</th>
					</tr>
					<tr class="active">
						<th class="col-sm-1">项目</th>
						<th class="col-sm-2">功能</th>
						<th class="col-sm-2">上级审批</th>
						<th class="col-sm-2">管理员审批</th>
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
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['cname'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['cname'];?>
/<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['funname'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['unix'];?>
</td>
							<td colspan=2>
								<?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['status']==101) {?>
									<p role="alert" class="alert alert-danger"><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
； 被拒绝，原因：<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['message'];?>
</p>
								<?php } elseif ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['status']==0) {?>
									<div class="progress" style="margin:0px;">
										<div role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color: 00ff00">
											<span  style="padding-left:10px;color:#000;"><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
</span>
										</div>
									</div>
								<?php } else { ?>
									<div class="progress" style="margin:0px;">
										<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['status'];?>
%; ">
											<span  style="padding-left:10px;color:#000;"><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
</span>
										</div>
									</div>
								<?php }?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php }} ?>
