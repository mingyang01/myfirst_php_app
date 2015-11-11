<?php /* Smarty version Smarty-3.1.18, created on 2015-08-25 17:35:41
         compiled from "/home/work/websites/auth/protected/views/risk/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:102068849555da970de583f0-66276924%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a19b3cb3218ae4060817efa4b360e6d9d25e94e' => 
    array (
      0 => '/home/work/websites/auth/protected/views/risk/index.tpl',
      1 => 1440495336,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '102068849555da970de583f0-66276924',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55da970de88bf1_72212374',
  'variables' => 
  array (
    'depart' => 0,
    'departLeaders' => 0,
    'item' => 0,
    'admin' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55da970de88bf1_72212374')) {function content_55da970de88bf1_72212374($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
 $_from = $_smarty_tpl->tpl_vars['departLeaders']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                                    <option <?php if ($_smarty_tpl->tpl_vars['depart']->value==$_smarty_tpl->tpl_vars['item']->value['itemname']) {?> selected <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['itemname'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['itemname'];?>
</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">
                            管理员：
                        </label>
                        <div class="form-group">
                            <input id="admin" type="text" disabled class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value;?>
" >
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="export-depart" class="btn btn-success">导出部门的功能</button>
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
    //window.location = '/risk/index?depart='+depart+'&admin='+admin;
    $.get('/risk/index', {depart:depart}, function(data) {
        console.log(data)
        $('#admin').val(data);
    },'json');
    
    $('#dg').datagrid({
        url:'/risk/GetDepartFunction?depart='+depart+'&admin='+admin
    })
   
})
$('#dg').datagrid({ 
    url:'/risk/GetDepartFunction?depart='+depart+'&admin='+admin,
    fitColumns:true,
    singleSelect:true,
    rownumbers:true,
    columns:[[
        {field:'id',checkbox:true},
        {field:'business',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
        {field:'cname',title:'<i class="glyphicon glyphicon-th-list"> 项目名称',width:20},
        {field:'funname',title:'<i class="glyphicon glyphicon-globe"> 功能',width:20},
        {field:'item',title:'<i class="glyphicon glyphicon-pushpin"> 功能点',width:20}
            
    ]],
    pagination: true,
    pageList:[10,20,50,100]
})
//导出请求
$('#export-depart').click(function(){
    var depart = $('#depart').val();
    var admin = $('#admin').val();
    window.location='/risk/exportHtml?depart='+depart+'&admin='+admin;
})
</script><?php }} ?>
