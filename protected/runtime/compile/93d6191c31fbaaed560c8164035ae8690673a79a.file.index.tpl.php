<?php /* Smarty version Smarty-3.1.18, created on 2015-11-05 17:53:09
         compiled from "/home/work/websites/auth/protected/views/operation/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2077882114563b207a0f1dd0-39634401%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '93d6191c31fbaaed560c8164035ae8690673a79a' => 
    array (
      0 => '/home/work/websites/auth/protected/views/operation/index.tpl',
      1 => 1446717178,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2077882114563b207a0f1dd0-39634401',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_563b207a121893_15989819',
  'variables' => 
  array (
    'alldepart' => 0,
    'depart' => 0,
    'item' => 0,
    'roles' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563b207a121893_15989819')) {function content_563b207a121893_15989819($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
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
	            <li class="active">部门角色导出</li>
	        </ol>
        	<div class="well">
        		<div class="form-inline">
        			<label for="depart">部门：</label>
        			<select id="chooseDepart" type="text" class="js-example-basic-single form-control" placeholder="请选择部门名称">
        				<?php if ($_smarty_tpl->tpl_vars['alldepart']->value) {?>
        					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['alldepart']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
        						<option <?php if ($_smarty_tpl->tpl_vars['depart']->value==$_smarty_tpl->tpl_vars['item']->value) {?> selected <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
        					<?php } ?>
        				<?php }?>
        			</select>
        			<button id="export-btn" class="btn btn-info btn-sm">下载</button>
        		</div>
        	</div>
	    </div>
	    <div class="col-md-12">
	    	<?php if ($_smarty_tpl->tpl_vars['roles']->value) {?>
	    	<table class="table table-bordered">
	    		<tr>
	    			<th>部门名称</th><th>角色名称</th>
	    		</tr>
		    		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['roles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
		    			<tr>
		    				<td><?php echo $_smarty_tpl->tpl_vars['item']->value['parent'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['child'];?>
</td>
		    			</tr>
		    		<?php } ?>
	    	</table>
	    	<?php } else { ?>
	    	<div class="alert alert-danger">
	    		当前部门下还没有角色，请切换其他部门查看
	    	</div>
		    <?php }?>
	    </div>
	</div>
</div>
<script>
	$('#chooseDepart').change(function(){
		window.location = "/operation/index?depart="+$(this).val();
	})
	$('#export-btn').click(function(){
		window.open("/operation/index?depart="+$('#chooseDepart').val()+"&export="+true,'角色导出')
	})
</script><?php }} ?>
