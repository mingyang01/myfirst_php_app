<?php /* Smarty version Smarty-3.1.18, created on 2015-08-25 18:48:30
         compiled from "/home/work/websites/auth/protected/views/risk/depart-staff-detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:167861849755dc33c5a16411-04041675%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ea39d541ba27205dc785ab0c8f81bacf076bf554' => 
    array (
      0 => '/home/work/websites/auth/protected/views/risk/depart-staff-detail.tpl',
      1 => 1440499707,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '167861849755dc33c5a16411-04041675',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55dc33c5a553a5_69763641',
  'variables' => 
  array (
    'depart' => 0,
    'departs' => 0,
    'key' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55dc33c5a553a5_69763641')) {function content_55dc33c5a553a5_69763641($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<script>
    console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['depart']->value);?>
);
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/publish/index">项目发布</a></li>
                <li class="active">部门功能</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="well" class="well clearfix">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="" class="">
                            部门：
                        </label>
                        <div class="form-group">
                            <select id='depart' name="businesses" class="js-example-basic-single form-control" >
                                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['departs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                                    <option <?php if ($_smarty_tpl->tpl_vars['depart']->value==$_smarty_tpl->tpl_vars['key']->value) {?> selected <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="export-depart" class="btn btn-success">导出</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="dg"></table>
        </div>
    </div>
</div>
<script>
var depart = $('#depart').val();
var admin = $('#admin').val();
$('#depart').change(function(){
    var depart = $(this).val();
    $('#dg').datagrid({
        url:'/risk/GetDepartStaff?depart='+depart
    })
})
$('#dg').datagrid({ 
    url:'/risk/GetDepartFunction?depart='+depart,
    fitColumns:false,
    singleSelect:true,
    rownumbers:true,
    frozenColumns:[[
       {field:'id',checkbox:true},
       {field:'name',title:'<i class="glyphicon glyphicon-th-list"> 姓名',width:200,sortable:true},
       {field:'mail',title:'<i class="glyphicon glyphicon-th-list"> 邮箱',width:200},
    ]],
    columns:[[
        {field:'function',title:'<i class="glyphicon glyphicon-th-list"> 功能',width:'100%'}
    ]]
})
//导出请求
$('#export-depart').click(function(){
    var depart = $('#depart').val();
    window.location='/risk/ExportDepartStaffAuth?depart='+depart;
})
</script><?php }} ?>
