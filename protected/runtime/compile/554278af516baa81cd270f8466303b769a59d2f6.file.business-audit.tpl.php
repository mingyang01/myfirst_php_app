<?php /* Smarty version Smarty-3.1.18, created on 2015-09-16 17:22:50
         compiled from "/home/work/websites/auth/protected/views/audit/business-audit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203415610755f7e67fc24105-94634393%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '554278af516baa81cd270f8466303b769a59d2f6' => 
    array (
      0 => '/home/work/websites/auth/protected/views/audit/business-audit.tpl',
      1 => 1442395368,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203415610755f7e67fc24105-94634393',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f7e67fc6a771_52930365',
  'variables' => 
  array (
    'project' => 0,
    'business' => 0,
    'key' => 0,
    'user' => 0,
    'start_time' => 0,
    'end_time' => 0,
    'data' => 0,
    'condition' => 0,
    'item' => 0,
    'totalNum' => 0,
    'pageInfo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f7e67fc6a771_52930365')) {function content_55f7e67fc6a771_52930365($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/lib/My97DatePicker/WdatePicker.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<style>
    .sortBtn{
        display:inline-block;
        width: 20px;
        padding-left: 5px;
        cursor:pointer;
    }
</style>
<div class="container">
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">平台审计</li>
            </ol>
        </div>
    </div>

    <div class="row" style="margin-bottom:30px;">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li role="presentation" ><a href="/audit/BusinessDetail">平台审计</a></li>
                <li role="presentation" class="active"><a href="#">审计详情</a></li>
                <li role="presentation"><a href="/audit/UvOfBusiness">用户审计</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <div class="form-inline">
                    <div class="form-group">
                        <label for=""></label>
                        <select id='business' name="businesses" class="js-example-basic-single form-control" >
                            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                                <option <?php if ($_smarty_tpl->tpl_vars['business']->value==$_smarty_tpl->tpl_vars['key']->value) {?> selected <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
--<?php echo $_smarty_tpl->tpl_vars['project']->value[$_smarty_tpl->tpl_vars['key']->value]['cname'];?>
</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">用户名</label>
                        <input id="user" type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
" class="form-control" placeholder="邮箱前缀(选填)">
                    </div>
                    <div class="form-group">
                        <label for="">起止时间</label>
                        <input id="start_time" type="text" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['start_time']->value;?>
" />
                    </div>
                     <div class="form-group">
                        <label for="">结束时间</label>
                        <input id="end_time" type="text" class="picker form-control" value="<?php echo $_smarty_tpl->tpl_vars['end_time']->value;?>
"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success submitBtn">查看</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- show data -->
    <?php if ($_smarty_tpl->tpl_vars['data']->value) {?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr class="active">
                        <th>
                            <i class="glyphicon glyphicon-th-list"></i> 平台 <i class="glyphicon glyphicon-arrow-up sortBtn" style="float:right;"></i>
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-globe"></i> 访问的url
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-user"></i> 用户 <i <?php if ($_smarty_tpl->tpl_vars['condition']->value=='audit_user-desc') {?> data-condition="audit_user-asc" class="glyphicon glyphicon-arrow-down sortBtn"<?php } else { ?>data-condition="audit_user-desc" class="glyphicon glyphicon-arrow-up sortBtn"<?php }?>  style="float:right;"></i>
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-modal-window"></i> 用户的ip
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-star"></i> 状态 <i <?php if ($_smarty_tpl->tpl_vars['condition']->value=='audit_flag-desc') {?> data-condition="audit_flag-asc" class="glyphicon glyphicon-arrow-down sortBtn" <?php } else { ?> data-condition="audit_flag-desc" class="glyphicon glyphicon-arrow-up sortBtn"<?php }?> style="float:right;"></i>
                        </th>
                        <th>
                            <i class="glyphicon glyphicon-time"></i> 访问的时间 <i <?php if ($_smarty_tpl->tpl_vars['condition']->value=='unix-desc') {?> data-condition="unix-asc" class="glyphicon glyphicon-arrow-down sortBtn"<?php } else { ?> data-condition="unix-desc" class="glyphicon glyphicon-arrow-up sortBtn" <?php }?> style="float:right;"></i>
                        </th>
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
                            <td><?php echo $_smarty_tpl->tpl_vars['item']->value['business'];?>
</td><td><a href="http://<?php echo $_smarty_tpl->tpl_vars['item']->value['audit_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['audit_url'];?>
</a></td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['audit_user'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['audit_ip'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['audit_flag'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['item']->value['unix'];?>
</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                该平台暂时没有相关的数据，切换别的平台,选择别的时间试试？
            </div>
        </div>
    </div>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['totalNum']->value>10) {?>
        <div class="row">
            <div class="panel-body">
                <div style="text-align:center">
                    <ul class="pagination">
                        <li><a>共<?php echo $_smarty_tpl->tpl_vars['totalNum']->value;?>
条记录</a></li>
                        <li><a>总共<?php echo $_smarty_tpl->tpl_vars['pageInfo']->value['totalPage'];?>
页</a></li>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pageInfo']->value['firstLink'];?>
">首页</a></li>
                        <?php if ($_smarty_tpl->tpl_vars['pageInfo']->value['prevPage']>=1) {?>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pageInfo']->value['prevLink'];?>
">上一页</a></li>
                        <?php }?>
                        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pageInfo']->value['pageNumList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                            <?php if ($_smarty_tpl->tpl_vars['pageInfo']->value['nowPage']&&$_smarty_tpl->tpl_vars['pageInfo']->value['nowPage']==$_smarty_tpl->tpl_vars['key']->value) {?>
                                <li class="active"><a href="javascript:return false;" class="cur"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</a></li>
                            <?php }?>
                        <?php } ?>
                        <?php if ($_smarty_tpl->tpl_vars['pageInfo']->value['nextPage']<=$_smarty_tpl->tpl_vars['pageInfo']->value['totalPage']) {?>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pageInfo']->value['nextLink'];?>
">下一页</a></li>
                        <?php }?>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pageInfo']->value['lastLink'];?>
">最后一页</a></li>
                    </ul>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<script>
$(function(){
    $('.submitBtn').click(function(){
        var business = $("#business").val();
        var user = $("#user").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        window.location = "/audit/businessaudit?business="+business+"&user="+user+"&start_time="+start_time+"&end_time="+end_time;
    })
    document.onkeydown = function(e){ 
        var ev = document.all ? window.event : e;
        var business = $("#business").val();
        var user = $("#user").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if(ev.keyCode==13) {
            window.location = "/audit/businessaudit?business="+business+"&user="+user+"&start_time="+start_time+"&end_time="+end_time
        }
    }
    $('.sortBtn').click(function(){
        var business = $("#business").val();
        var user = $("#user").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var condition = $(this).attr('data-condition')
        window.location = "/audit/businessaudit?business="+business+"&user="+user+"&start_time="+start_time+"&end_time="+end_time+"&condition="+condition;
    })
    //日期插件
    $('.picker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
})
</script><?php }} ?>
