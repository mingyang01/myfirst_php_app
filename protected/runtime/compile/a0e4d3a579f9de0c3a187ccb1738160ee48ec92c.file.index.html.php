<?php /* Smarty version Smarty-3.1.18, created on 2015-09-15 12:44:08
         compiled from "/home/work/websites/auth/protected/views/log/index.html" */ ?>
<?php /*%%SmartyHeaderCode:107922468455f7a218c7e045-97700183%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a0e4d3a579f9de0c3a187ccb1738160ee48ec92c' => 
    array (
      0 => '/home/work/websites/auth/protected/views/log/index.html',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '107922468455f7a218c7e045-97700183',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'optuser' => 0,
    'optfunction' => 0,
    'types' => 0,
    'key' => 0,
    'type' => 0,
    'item' => 0,
    'start' => 0,
    'end' => 0,
    'data' => 0,
    'totalNum' => 0,
    'pageInfo' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f7a218cfe8a4_04213187',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f7a218cfe8a4_04213187')) {function content_55f7a218cfe8a4_04213187($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<script type="text/javascript" src="/assets/js/bootstrap-datepicker.js"></script>
<style type="text/css" src="/assets/css/datepicker.css"></style>
<script src="/assets/js/bufferview.js"></script>
<div class="container cont">
	<div class="panel">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">用户审计</li>
        </ol>
     </div>
     <div class="row">
		<div id="search_form" class="well">
            <form class="form-horizontal" role="form" id="form">
            	<div class="form-group">
                    <label class="col-md-1 control-label">操作人：</label>
                    <div class="col-md-2">
                    	<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
" class="form-control" id="user" name="user" placeholder="邮箱前缀(可选)">
                    </div>
	                <label class="col-md-2 control-label">被操作人或角色：</label>
	                <div class="col-md-2">
	                    <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['optuser']->value;?>
" class="form-control" id="optuser" name="optuser" placeholder="邮箱前缀或者角色名(可选)">
	                </div>
	                <label class="col-md-2 control-label">操作对象：</label>
	                <div class="col-md-2">
	                    <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['optfunction']->value;?>
" class="form-control" id="optfunction" name="optfunction" placeholder="功能名(可选)">
	                </div>
	            </div>
	            <div  class="form-group">
	            	<label class="col-md-1 control-label">&nbsp;&nbsp;动作：</label>
	                 <div class="col-md-2">
	                    <select class="form-control" id="opttype" name="opttype">
		                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
		                     	<option <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['type']->value) {?>selected<?php }?>  value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
		                    <?php } ?>
		                </select>
	                </div>
	            	<label class="col-md-2 control-label">&nbsp;&nbsp;开始时间：</label>
	            	<div class="col-md-2  date datepicker">
	                    <input type="text" id="start" name="start" value="<?php echo $_smarty_tpl->tpl_vars['start']->value;?>
"  data-date-format="yyyy-mm-dd">
	                    <span class="glyphicon glyphicon-calendar add-on"></span>  
	                </div>
	                <label class="col-md-2 control-label">结束时间：</label>
	                <div class="col-md-2  date datepicker">
	                    <input id="end" name="end" type="text" value="<?php echo $_smarty_tpl->tpl_vars['end']->value;?>
" data-date-format="yyyy-mm-dd">
	                    <span class="glyphicon glyphicon-calendar add-on"></span>  
	                </div>
	                	&nbsp;&nbsp;<button id="submit" class="btn btn-default">查看</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row bodylist">
        <table class="table table-hover table-striped table-bordered table-condensed">
            <thead>
                <tr align="center">
                <th width="10%">用户</th>
                    <th width="20%">动作</th>
                    <th width="20%">被操作人或者角色</th>
                    <th width="30%">被操作权限点</th>
                    <th width="20%">操作时间</th>
                </tr>
            </thead>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'-'key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key'-'key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['item']->value['user'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['optobject'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['optfunname'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['time'];?>
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
<!--分页-->
    <?php if ($_smarty_tpl->tpl_vars['totalNum']->value>20) {?>
    <div class="panel">
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
                    	<?php if ($_smarty_tpl->tpl_vars['page']->value==$_smarty_tpl->tpl_vars['key']->value) {?>
                    		<li><a href="javascript:return false;" class="cur"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
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
<div  class="row">
 aaaa
</div>
  
</div>
<script>
    $(function(){
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        }).on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

	$('#submit').click(function (e){
		var user=$('#user').val();
		var optuser=$('#optuser').val();
		var opttype=$('opttype').val();
		var optfunction=$('#optfunction').val();
		var start=$('#start').val();
		var end=$('#end').val();
		
		window.location.href ="/Audit/Index?user="+user+"&opttype="+opttype+"&optuser="+optuser+"&optfunction="+optfunction+"&start="+start+"&end="+end;
		
    });

    });

</script><?php }} ?>
