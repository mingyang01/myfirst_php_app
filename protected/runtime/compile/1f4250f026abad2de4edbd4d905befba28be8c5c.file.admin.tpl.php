<?php /* Smarty version Smarty-3.1.18, created on 2015-10-16 15:01:58
         compiled from "/home/work/websites/auth/protected/views/apply/admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19771391455620a0e66e6f35-78406755%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f4250f026abad2de4edbd4d905befba28be8c5c' => 
    array (
      0 => '/home/work/websites/auth/protected/views/apply/admin.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19771391455620a0e66e6f35-78406755',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'depart' => 0,
    'key' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_5620a0e67910a6_04399997',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5620a0e67910a6_04399997')) {function content_5620a0e67910a6_04399997($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
		$(".js-example-basic-single").select2({placeholder: "请选择部门"});
	});
 
</script>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">后台管理</li>
	        </ol>
	        <div class="well clearfix">
				<div class="col-sm-5">
					<div class="form-group">
						<label class="col-sm-2 control-label" style="padding-left:0px; paddding-right:0px;"><h4>部门:</h4></label>
						<div class="col-sm-10" style="padding-left:0px; padding-right:0px;">
							<select id="depart" class="js-example-basic-single col-sm-12">
								<option value="" selected=selected></option>
								 <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['depart']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
								 		<optgroup label="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
									 		<?php  $_smarty_tpl->tpl_vars['it'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['it']->_loop = false;
 $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['it']->key => $_smarty_tpl->tpl_vars['it']->value) {
$_smarty_tpl->tpl_vars['it']->_loop = true;
 $_smarty_tpl->tpl_vars['val']->value = $_smarty_tpl->tpl_vars['it']->key;
?>
									 			<option value="<?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value][$_smarty_tpl->tpl_vars['val']->value]['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value][$_smarty_tpl->tpl_vars['val']->value]['name'];?>
</option>
									 		<?php } ?>
								 		</optgroup>
								 <?php } ?>
							</select>
						</div>
					</div>
					<select name="rolelist" id="rolelist" size="26" id="" class="form-control"></select>
				</div>
				<div class="col-sm-7">
					<div class="col-sm-12">
						<select name="actionlist" multiple="multiple" size="19" id="actionlist" class="change_list"></select>
					</div>
					<div class="col-sm-12" style="padding-left:0px; margin-top:10px;">
						<button id="submit" class="btn btn-default btn-block">确定</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
</script>
<script>
var listBox = $('#actionlist').bootstrapDualListbox({
	nonSelectedListLabel: '未分配的功能',
	selectedListLabel: '已分配的功能',
	preserveSelectionOnMove: 'moved',
	moveOnSelect: true,
	nonSelectedFilter: ''
});
$('#depart').change(function(){
	$.get('/apply/getRoleList',{departid:$('#depart').val()},function(data){
		if(data)
		{
			var html = [];
			for(var i=0;i<data.length;i++)
			{
				html.push('<option value='+data[i]['id']+'>'+data[i]['name']+'</option>');
			}
			html = html.join(" ");
			$("#rolelist").html(html);
		}
	},'json');
});
$('#rolelist').change(function(){
	$.get('/apply/getActionList',{roleid:$('#rolelist').val()},function(data){
		if(data)
		{
			console.log(data);
			var html = [];
			$("#actionlist").html("");
			for(var i=0;i<data['selected'].length;i++)
			{
				html.push('<option selected="selected" value="'+data['selected'][i]+'">'+data['selected'][i]+'</option>');
			}
			for(var i=0;i<data['select'].length;i++)
			{
				html.push('<option value="'+data['select'][i]+'">'+data['select'][i]+'</option>');
			}
			html.join(" ");
			$("#actionlist").html(html);
			$('#actionlist').bootstrapDualListbox('refresh',true);
		}
	},'json')
});
$("#submit").click(function(){
	var actionlist=$('#actionlist').val();
	var roleid=$('#rolelist').val();
	$.post('/apply/updateaction',{'roleid':roleid,'actionlist':actionlist},function(data) {
		if(data)
		{
			alert(data)
		}
	},'json');
});

</script>

<?php }} ?>
