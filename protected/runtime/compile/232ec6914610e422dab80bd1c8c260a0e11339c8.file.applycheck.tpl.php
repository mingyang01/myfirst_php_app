<?php /* Smarty version Smarty-3.1.18, created on 2015-09-09 12:17:44
         compiled from "/home/work/websites/auth/protected/views/apply/applycheck.tpl" */ ?>
<?php /*%%SmartyHeaderCode:108781899555e01e263d2923-44336167%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '232ec6914610e422dab80bd1c8c260a0e11339c8' => 
    array (
      0 => '/home/work/websites/auth/protected/views/apply/applycheck.tpl',
      1 => 1441772262,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '108781899555e01e263d2923-44336167',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55e01e2646f150_52420109',
  'variables' => 
  array (
    'applyUser' => 0,
    'applyItem' => 0,
    'condition' => 0,
    'data' => 0,
    'item' => 0,
    'users' => 0,
    'key' => 0,
    'totalNum' => 0,
    'pageInfo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e01e2646f150_52420109')) {function content_55e01e2646f150_52420109($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<style>
	#showdata thead tr th{
		line-height: 40px;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">申请管理</li>
	        </ol>
		</div>
		<div class="col-md-12">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation">
					<a href="/apply/index"><i class="glyphicon glyphicon-send"></i> 权限申请</a>
				</li>
				<li role="presentation" >
					<a  href="/apply/myapply"><i class="glyphicon glyphicon-folder-open"></i> 我的申请</a>
				</li>
				<li role="presentation" class="active">
					<a  href="/apply/applycheck"><i class="glyphicon glyphicon-bell"></i> 权限审批</a>
				</li>
				<li role="presentation">
					<a href="/function/getFunnameByUrl"><i class="glyphicon glyphicon-bell"></i> 根据url获取功能信息</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="container" style="padding-top:20px;">
	<div class="row" >
	<div class="col-md-12">
		<div class="alert alert-danger">
			只能批量通过一个用户的申请，如果一次选中多个用户的申请，只会通过最上面用户的申请，授权时请检查选中用户的权限
		</div>
	</div>
	<div class="col-md-12">
		<div class="well">
			<div class="form-inline">
				<div class="form-group">
					<label for="">申请人</label>
					<input id="applyUser" type="text" placeHolder="邮箱前缀" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['applyUser']->value;?>
">
				</div>
				<div class="form-group">
					<label for="">申请条目</label>
					<input id="applyItem" type="text" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['applyItem']->value;?>
">
				</div>
				<div class="form-group">
					<button class="btn btn-success viewsByCondition">查看</button>
				</div>
			</div>
		</div>
	</div>
		<div class="col-md-12">
			<table class="table table-bordered" id="showdata">
				<thead>
					<tr class="active">
						<th><i class="glyphicon glyphicon-pushpin"></i></th>
						<th><i class="glyphicon glyphicon-user"></i> 申请人 <button class="btn btn-default btn-small sort-condition" data-condition="applicant_desc" style="float:right;"> <i class="glyphicon glyphicon-arrow-down"></i></button></th>
						<th><i class="glyphicon glyphicon-list-alt"></i> 申请的条目</th>
						<th><i class="glyphicon glyphicon-time"></i> 
							申请时间 <button class="btn btn-default btn-small sort-condition" <?php if ($_smarty_tpl->tpl_vars['condition']->value=='unix_desc') {?> data-condition="unix_asc" <?php } else { ?> data-condition="unix_desc" <?php }?> style="float:right;"> 
							<?php if ($_smarty_tpl->tpl_vars['condition']->value=='unix_desc') {?> 
								<i class="glyphicon glyphicon-arrow-down"></i>
							<?php } else { ?> 
								<i class="glyphicon glyphicon-arrow-up"></i> 
							<?php }?>
							
						</button>
						</th>
						<th><i class="glyphicon glyphicon-pushpin"></i> 状态</th>
						<th><i class="glyphicon glyphicon-wrench"></i> 单个操作</th>
						<th><i class="glyphicon glyphicon-wrench"></i> 批量操作</th>
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
							<td>
								<input type='checkbox' name="chk"  id="chk" class="chk_<?php echo $_smarty_tpl->tpl_vars['item']->value['appuid'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
-<?php echo $_smarty_tpl->tpl_vars['item']->value['appuid'];?>
-<?php echo $_smarty_tpl->tpl_vars['item']->value['item'];?>
" <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['users']->value[$_smarty_tpl->tpl_vars['item']->value['appuid']];?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1<=1) {?>disabled<?php }?>/>
								<input type="hidden" id="applyid" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['appuid'];?>
"/>
							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['applicant'];?>
-(<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['depart'];?>
)</td>
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['item'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['unix'];?>
</td>
							<?php if ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['status']==1||$_smarty_tpl->tpl_vars['item']->value['status']==2) {?>
							<td><a disabled class="btn btn-warning btn-sm"><?php if ($_smarty_tpl->tpl_vars['item']->value['operate']) {?>等待管理员"<?php echo $_smarty_tpl->tpl_vars['item']->value['operate'];?>
"审批<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
<?php }?></a></td>
							<?php } elseif ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['status']==0) {?>
							<td><a disabled class="btn btn-warning btn-sm">等待直属上级"<?php echo $_smarty_tpl->tpl_vars['item']->value['operate'];?>
"审批</a></td>
							<?php } elseif ($_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['status']==3) {?>
							<td><a disabled class="btn btn-warning btn-sm">通过</a></td>
							<?php }?>
							<td >
								<a class="savebtn btn btn-success btn-sm" data-id="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['id'];?>
" data-leaderid="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['leader'];?>
" data-type="1" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-ok"></i> 同意</a>
								<br/><p></p>
								<i class="btn refuse btn-danger btn-sm" data-toggle="modal" data-target="#refuse" data-id="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['id'];?>
" data-leaderid="<?php echo $_smarty_tpl->tpl_vars['data']->value[$_smarty_tpl->tpl_vars['key']->value]['leader'];?>
"><i class="glyphicon glyphicon-remove"></i> 拒绝</a>
							</td>
							<td>
								<a class="savebtn btn btn-success btn-sm" href="javascript:void(0)" onclick="selectAll('<?php echo $_smarty_tpl->tpl_vars['item']->value['appuid'];?>
')"> 选中该用户的所有申请</a><br/>
								<p></p>
								<a class="savebtn btn btn-success btn-sm" href="javascript:void(0)" data-uid="<?php echo $_smarty_tpl->tpl_vars['item']->value['appuid'];?>
" data-type="2" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-ok"></i>批量同意</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
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
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">审批提示：</h4>
			</div>
		<div class="modal-body">
			<div class="alert alert-danger" role="alert">
				<p>请务必仔细确认您所审批的权限是否为员工工作必需，如非必用权限请予以拒绝并提示对方从新按要求申请。</p>
				<p>为自己的审批负责，为现在做起！</p>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-primary passbtn" data-dismiss="modal">同意</button>
		</div>
		</div>
	</div>
</div>

<div class="modal fade" id="refuse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">填写原因：</h4>
			</div>
		<div class="modal-body">
			<textarea id="message" name="" class="form-control" id="" style="height:100px;">
			</textarea>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-primary refusebtn" data-dismiss="modal">提交</button>
		</div>
		</div>
	</div>
</div>
<script>
</script>
<script>


	function selectAll(uid){
		 var class_id="chk_"+uid;	
		 $("."+class_id).attr("checked", true);
	}

	$(function(){
		var dataid = "";
		var passid = "";
		var type = '';
		var uid = '';
		$('.savebtn').click(function(){
			passid  = $(this).attr('data-id');
			type = $(this).attr('data-type');
			uid = $(this).attr('data-uid');
		})
		
		
		$(".passbtn").click(function(){
			if(type == 1){
				$.post('/apply/applydeal',{id:passid,leaderid:$('.savebtn').attr('data-leaderid')}, function(data) {
					if(data) {
						alert(data);
						window.location="/apply/applycheck";
					}
				},"json");
			} else {
			
				var ids = [];
		        $('input[name="chk"]:checked').each(function () {
		            var tempVal = $(this).val();
		            ids.push(String(tempVal));
		        });
				$.post('/apply/applydealbat',{ids:ids, uid:uid}, function(data) {
					if(data) {
						alert(data);
						window.location="/apply/applycheck";
					}
				},"json");
			}
		});
		
		
		$('.refuse').click(function(){
			dataid  = $(this).attr('data-id');
		})
		$(".refusebtn").click(function(){
			$.post('/apply/refuse',{id:dataid ,"message":$('#message').val()}, function(data) {
				if(data) {
					alert(data);
					window.location="/apply/applycheck";
				}
			},"json");
		});
	});

</script>
<script>
$('.sort-condition').click(function(){
	var condition = $(this).attr('data-condition');
	var nowPage = <?php echo $_smarty_tpl->tpl_vars['pageInfo']->value['nowPage'];?>

	var applyItem = '<?php echo $_smarty_tpl->tpl_vars['applyItem']->value;?>
'
	var applyUser = '<?php echo $_smarty_tpl->tpl_vars['applyUser']->value;?>
'
	window.location="/apply/applycheck?condition="+condition+"&nowPage="+nowPage+"&applyItem="+applyItem+"&applyUser"+applyUser;
})
$('.viewsByCondition').click(function(){
	var applyUser = $('#applyUser').val();
	var applyItem = $('#applyItem').val();
	window.location="/apply/applycheck?applyUser="+applyUser+"&applyItem="+applyItem;

})
</script><?php }} ?>
