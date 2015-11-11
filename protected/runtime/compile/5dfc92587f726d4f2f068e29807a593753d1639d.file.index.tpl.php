<?php /* Smarty version Smarty-3.1.18, created on 2015-09-18 15:17:02
         compiled from "/home/work/websites/auth/protected/views/menu/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:91517124855ed0bfb071394-46127520%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5dfc92587f726d4f2f068e29807a593753d1639d' => 
    array (
      0 => '/home/work/websites/auth/protected/views/menu/index.tpl',
      1 => 1442560424,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '91517124855ed0bfb071394-46127520',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55ed0bfb0e7fd0_41484102',
  'variables' => 
  array (
    'project' => 0,
    'u' => 0,
    'business' => 0,
    'functions' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55ed0bfb0e7fd0_41484102')) {function content_55ed0bfb0e7fd0_41484102($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
            <li><a href="/publish/index">项目发布</a></li>
            <li class="active">菜单管理</li>
        </ol>
        <div id="well" class="well">
            <div class="form-inline" role="form" id="form">
                <div class="form-group">
                    <select id="project" class="form-control">
                        <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                            <option <?php if ($_smarty_tpl->tpl_vars['u']->value['business']==$_smarty_tpl->tpl_vars['business']->value) {?>selected<?php }?>  value="<?php echo $_smarty_tpl->tpl_vars['u']->value['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['cname'];?>
</option>
                        <?php } ?>
                    </select>
                </div>
                <button data-action="create" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">添加</button>
                <button data-action="update" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">修改</button>
                <button class="btn btn-default" id="delete">删除</button>
                <div class="form-group" style="margin-left:400px;">
                    <label for="search-content">搜索</label>
                    <input type="text" class="form-control" id="search-content" placeholder="菜单名称（选填）">
                </div>
                <div class="form-group">
                    <button id="search-btn"  type="submit" class="btn btn-success ">查询</button>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-bottom:30px;">
        <table id="dg">
        </table>
    </div>
</div>
    <div id="dlg" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <form id="fm" method="post" novalidate>
                        <div class="form-group" style="display:none;">
                            <label for="exampleInputEmail1">id:</label>
                            <input required="true" name="id" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">项目:</label>
                            <input id="business" required="true" name="business" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">一级菜单:</label>
                            <input required="true" name="first" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">二级菜单:</label>
                            <input name="second" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">三级菜单</label>
                            <input name="third" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">项目绑定:</label>
                            <select id="selectBusiness" name="businesses" class="js-example-basic-single form-control" style="width:100%;">
                                <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                                <option <?php if ($_smarty_tpl->tpl_vars['u']->value['business']==$_smarty_tpl->tpl_vars['business']->value) {?>selected<?php }?>  value="<?php echo $_smarty_tpl->tpl_vars['u']->value['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['cname'];?>
</option>
                            <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">功能绑定:</label>
                            <select id="showfun" name="function" class="js-example-basic-single form-control" style="width:100%;">
                                <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['functions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                                <option value="<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</option>
                                <?php } ?>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="submit" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script type="text/javascript">
    var business = "<?php echo $_smarty_tpl->tpl_vars['business']->value;?>
";
</script>
<script type="text/javascript" src="/assets/js/menu.js"></script>
<script>
    $('#selectBusiness').change(function(){
        $.get('/menu/getfunction',{"business":$(this).val()},function(data) {
            if(data)
            {
                var html = [];
                for(var i=0; i<data.length;i++)
                {
                    html.push('<option value='+data[i].id+'>'+data[i].funname+'</option>');
                }
                html = html.join(' ');
                $('#showfun').html(html);
           
            }
        },'json');
        $('#dg').datagrid({
        url:'/menu/GetMenuList?business='+business+'&funname='+''
    });
    });
    $('#dg').datagrid({
        url:'/menu/GetMenuList?business='+business+'&funname='+'',
        fitColumns:true,
        singleSelect:true,
        rownumbers:true,
        columns:[[
                {field:'id',checkbox:true},
                {field:'business',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
                {field:'first',title:'<i class="glyphicon glyphicon-globe"> 一级菜单',width:20},
                {field:'second',title:'<i class="glyphicon glyphicon-pushpin"> 二级菜单',width:20},
                {field:'third',title:'<i class="glyphicon glyphicon-pushpin"> 三级菜单',width:20},
                {field:'funname',title:'<i class="glyphicon glyphicon-pushpin"> 绑定功能',width:20},
                {field:'creator',title:'<i class="glyphicon glyphicon-pushpin"> 创建者',width:20},
                
        ]],
        pagination: true,
        pageList:[10,15]
    })
    $('#search-btn').click(function(){
        var funname = $('#search-content').val();
        $('#dg').datagrid({
            url:'/menu/GetMenuList?business='+business+'&funname='+funname,
        })
    })
</script><?php }} ?>
