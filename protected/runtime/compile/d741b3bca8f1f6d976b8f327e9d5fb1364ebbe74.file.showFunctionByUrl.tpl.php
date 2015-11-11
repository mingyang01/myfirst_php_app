<?php /* Smarty version Smarty-3.1.18, created on 2015-09-06 18:23:19
         compiled from "/home/work/websites/auth/protected/views/function/showFunctionByUrl.tpl" */ ?>
<?php /*%%SmartyHeaderCode:45642200455e025e22a45e1-94974831%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd741b3bca8f1f6d976b8f327e9d5fb1364ebbe74' => 
    array (
      0 => '/home/work/websites/auth/protected/views/function/showFunctionByUrl.tpl',
      1 => 1440993536,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '45642200455e025e22a45e1-94974831',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55e025e23046c6_34824278',
  'variables' => 
  array (
    'url' => 0,
    'function' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e025e23046c6_34824278')) {function content_55e025e23046c6_34824278($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">功能名查询</li>
	        </ol>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation">
					<a href="/apply/index"><i class="glyphicon glyphicon-send"></i> 权限申请</a>
				</li>
				<li role="presentation">
					<a  href="/apply/myapply"><i class="glyphicon glyphicon-folder-open"></i> 我的申请</a>
				</li>
				<li role="presentation">
					<a href="/apply/applycheck"><i class="glyphicon glyphicon-bell"></i> 权限审批</a>
				</li>
				<li role="presentation"  class="active">
					<a href="/function/getFunnameByUrl"><i class="glyphicon glyphicon-bell"></i> 根据url获取功能信息</a>
				</li>
			</ul>
			<!-- Tab panes -->
	        <div id="well" class="well">
	            <div class="form-inline" role="form" id="form">
	                <div class="form-group">
	                    <label for="exampleInputName2">功能url：</label>
	                    <input type="text" class="form-control" style="width:500px" id="search_url" placeholder="功能url" value="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
">
	                </div>
	                <button id="views" type="button" class="btn btn-default" onclick="search_info()">查看</button>
					<p><font size="2px" color="gray">＊完整的请求url，比如http://developer.meiliworks.com//apply/index</font></p>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12">
    	<table class="table table-hover table-striped table-bordered table-condensed">
        	 <thead>
                <tr align="center">
                <th width="10%">项目</th>
                    <th width="10%">功能名</th>
                    <th width="18%">描述</th>
                    <th width="20%">菜单</th>
					<th width="27%">url</th>
                    <th width="15%">操作时间</th>
                </tr>
            </thead>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['function']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['business'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['funname'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['description'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['menu'];?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['unix'];?>
</td>
            </tr>
            <?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
            	<tr><td>没有数据</td></tr>
            <?php } ?>
        </table>
	</div>
	</div>
</div>

<script type="text/javascript">

	function search_info() {
		var url = $("#search_url").val();
		if (url){
			window.location.href = "/function/getFunnameByUrl?url=" + url;
		}
	}

</script>

<?php }} ?>
