<?php /* Smarty version Smarty-3.1.18, created on 2015-09-15 12:41:47
         compiled from "/home/work/websites/auth/protected/views/department/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12801874855f7a18b135df5-43646494%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19bde852023464278d4fce4c7208647d08660ce8' => 
    array (
      0 => '/home/work/websites/auth/protected/views/department/index.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12801874855f7a18b135df5-43646494',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'arrBusiness' => 0,
    'item' => 0,
    'business' => 0,
    'arrDepartUsers' => 0,
    'username' => 0,
    'funname' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f7a18b185747_34147297',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f7a18b185747_34147297')) {function content_55f7a18b185747_34147297($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
            <li><a href="/publish/index">内部功能</a></li>
            <li class="active">部门权限</li>
        </ol>
        <div id="well" class="well">
            <div class="form-inline" role="form" id="form">
                <div class="form-group">
                    <label for="exampleInputName2">项目</label>
                    <select class="form-control" id="business" >
                        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['arrBusiness']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                            <option <?php if ($_smarty_tpl->tpl_vars['item']->value['business']==$_smarty_tpl->tpl_vars['business']->value) {?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['cname'];?>
</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputName2">用户</label>
                    <!-- 
                    <select class="form-control" id="username" >
                        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['arrDepartUsers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                            <option <?php if ($_smarty_tpl->tpl_vars['item']->value['mail']==$_smarty_tpl->tpl_vars['username']->value) {?>selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['mail'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</option>
                        <?php } ?>
                    </select>
                    -->
                    <input type="text" class="form-control" id="username" placeholder="邮箱前缀(必填)" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
"> 
                </div>
                
                <!--
                <div class="form-group">
                    <label for="funname">功能</label>
                    <input type="text" class="form-control" id="funname" placeholder="功能名称（选填）" value="<?php echo $_smarty_tpl->tpl_vars['funname']->value;?>
">
                </div>
                -->
                <button id="views" data-action="create" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">查看</button>
                <button id="exportEmail" data-action="update" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">Email发送</button>
                <button id="revoke" data-action="getAuth" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-danger">解除权限</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <table id="tt">
        </table>
    </div>
</div>
</div>
<style>
    .sbtn{border:1px solid #CCC; padding:4px;}
</style>

<script type="text/javascript">



$('#views').click(function(){
    window.location = '/department/index?business='+$('#business').val()+'&username='+$('#username').val()+'&funname='+$('#funname').val();
});
$(document).keyup(function(event){
  if(event.keyCode ==13){
        window.location = '/department/index?business='+$('#business').val()+'&username='+$('#username').val()+'&funname='+$('#funname').val();
  }
});

var business = $('#business').val();
var username = $('#username').val();
var funname = $('#funname').val();
$('#tt').datagrid({
        url:'/department/views?business='+business+'&username='+username+'&funname='+funname,
        fitColumns:true,
        singleSelect:false,
        rownumbers:true,
        columns:[[
            {field:'id',checkbox:true},
            {field:'business',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
            {field:'name',title:'<i class="glyphicon glyphicon-globe"> 功能',width:20},
            {field:'username',title:'<i class="glyphicon glyphicon-pushpin"> 用户',width:20},
            {field:'status',title:'<i class="glyphicon glyphicon-pushpin"> 状态',width:20},
            {field:'item',hidden:'true',title:'<i class="glyphicon glyphicon-pushpin"> 权限点',width:20}

        ]],
        pagination: true,
        pageList:[10,15]
    })

// $("<td style='padding: 0 8px;'><input id='sinput' class='easyui-searchbox' style='width: 200px;'/></td> ").
//                 prependTo(".datagrid-toolbar table tbody tr");

$('#exportEmail').click(function(){
    var business = $('#business').val();
    var username = $('#username').val();
    var mailto=prompt("请输入要发送的邮箱前缀,多人请以\",\"分割","");
    console.log(mailto.length);
    if(mailto.length > 0)
    {
        var url = '/department/sendEmail?business='+business+'&username='+username+'&mailto='+mailto;
        $.get(url, function(result){
            alert(result.data)
        });
    }
    //window.location='/department/sendEmail?business='+business+'&username='+username;
   
})
$('#getAuth').click(function(){
    var row = $('#tt').datagrid('getSelections');
    $.get('/export/getAuth',{item:row,uid:row[0].id}, function(data) {
        if(data)
        {
            alert(data)
            $('#tt').datagrid('reload');
        }
    },'json');
})
$('#revoke').click(function(){
     var row = $('#tt').datagrid('getSelections');
    $.get('/department/RevokeAuth',{item:row,uid:row[0].id}, function(data) {
        if(data)
        {
            alert(data)
            $('#tt').datagrid('reload');
        }
    },'json');
});

</script>
<?php }} ?>
