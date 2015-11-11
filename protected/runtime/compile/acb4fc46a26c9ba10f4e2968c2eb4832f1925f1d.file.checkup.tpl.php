<?php /* Smarty version Smarty-3.1.18, created on 2015-10-26 11:11:58
         compiled from "/home/work/websites/auth/protected/views/apply/checkup.tpl" */ ?>
<?php /*%%SmartyHeaderCode:63048191055f006d4d503d2-25138438%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'acb4fc46a26c9ba10f4e2968c2eb4832f1925f1d' => 
    array (
      0 => '/home/work/websites/auth/protected/views/apply/checkup.tpl',
      1 => 1442302592,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '63048191055f006d4d503d2-25138438',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f006d4e66dc9_30245337',
  'variables' => 
  array (
    'data' => 0,
    'key' => 0,
    'type' => 0,
    'item' => 0,
    'flag' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f006d4e66dc9_30245337')) {function content_55f006d4e66dc9_30245337($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script src="/assets/js/jquery.nestable.js"></script>
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script src="/assets/js/bufferview.js"></script>
<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">功能审核</li>
	        </ol>

	        <div class="well clearfix">
	        	<div class="col-md-10">
	        		
	        	<form role="form" class="form-inline" id="form">
					<div class="form-group">
						<label for="">项目：</label>
						<select name="" id="business" class="js-example-basic-single form-control">
	        				<option value="" >全部</option>
	        				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
	        					<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['cname'];?>
</option>
	        				<?php } ?>
	                    </select>
					</div>
					<div class="form-group">
						<label for="">审核状态：</label>
						<select name="" id="funname" class="form-control">
	        				<option value="9" >全部</option>
	        				<option value="1" >待审核</option>
	        				<option value="2" >已审核</option>
	        				<option value="0" >未提交审核</option>
	                    </select>
					</div>
					<div class="form-group">
						<label for="">功能状态：</label>
						<select name="" id="type" class="form-control">
	        				<option value="" >全部</option>
	        				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['type']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
	        					<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
	        				<?php } ?>
	                    </select>
					</div>
					<div class="form-group">
						<input id="searchContent" name="searchContent" class="form-control" placeholder="输入功能名称(选填)">
					</div>
	        	
	        		<div class="form-group">
	                	<button id="submit" class="btn btn-default">查询</button>
	        		</div>
	        		
	        	</form>
	        	</div>
	        	<div class="col-md-2">
	        		<?php if ($_smarty_tpl->tpl_vars['flag']->value==1) {?>
	                <div class="form-group">
	                	<button id="checkshow" class="btn btn-default" data-toggle="modal" data-target=".checkshow">审核</button>
	                </div>
	                <?php }?>
	        	</div>
	        </div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12" style="margin-bottom:40px">
			<table id="tt">
	        </table>
		</div>
	</div>
</div>

<div class="modal fade checkshow bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">审查功能</h4>
			</div>
			<div class="modal-body clearfix " style="height:400px; overflow:auto;">
				<table class="table col-sm-12 table-bordered">
					<thead>
						<tr class="active">
							<th class="col-sm-1">项目</th>
							<th class="col-sm-3">功能</th>
							<th class="col-sm-3">预览</th>
							<th class="col-sm-3">权限</th>
							<th class="col-sm-3">分配到部门</th>
						</tr>
					</thead>
					<tbody id="tbody">
					</tbody>
				</table>
				<div class="depart-wramp" style="padding-left:10px; display:none;"></div>
				<div class="btn-box" style="margin-top:20px;">
					<button class="btn btn-default btn-block btn-all">一健分配到部门</button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="savechange" class="btn btn-primary">保存</button>
			</div>
		</div>
	</div>
</div>

<script src="/assets/js/checkup.js"></script>
<style>
	 .datagrid-row  {
        height: 40px;
        line-height: 20px;
    }
    .datagrid-cell
    {
    	height: 30px;	
    	line-height: 20px;
    }
</style>


<?php }} ?>
