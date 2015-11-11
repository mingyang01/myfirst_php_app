{/include file="layouts/header.tpl"/}
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
					<input id="applyUser" type="text" placeHolder="邮箱前缀" class="form-control" value="{/$applyUser/}">
				</div>
				<div class="form-group">
					<label for="">申请条目</label>
					<input id="applyItem" type="text" class="form-control" value="{/$applyItem/}">
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
							申请时间 <button class="btn btn-default btn-small sort-condition" {/if $condition=='unix_desc'/} data-condition="unix_asc" {/else/} data-condition="unix_desc" {//if/} style="float:right;"> 
							{/if $condition=='unix_desc'/} 
								<i class="glyphicon glyphicon-arrow-down"></i>
							{/else/} 
								<i class="glyphicon glyphicon-arrow-up"></i> 
							{//if/}
							
						</button>
						</th>
						<th><i class="glyphicon glyphicon-pushpin"></i> 状态</th>
						<th><i class="glyphicon glyphicon-wrench"></i> 单个操作</th>
						<th><i class="glyphicon glyphicon-wrench"></i> 批量操作</th>
					</tr>
				</thead>
				<tbody>
					{/foreach from = $data item =item key = key/}
						<tr>
							<td>
								<input type='checkbox' name="chk"  id="chk" class="chk_{/$item.appuid/}" value="{/$item.id/}-{/$item.appuid/}-{/$item.item/}" {/if {/$users.{/$item.appuid/}/}<=1/}disabled{//if/}/>
								<input type="hidden" id="applyid" value="{/$item.appuid/}"/>
							</td>
							<td>{/$data.$key.applicant/}-({/$data.$key.depart/})</td>
							<td>{/$data.$key.item/}</td>
							<td>{/$data.$key.unix/}</td>
							{/if $data.$key.status eq 1 || $item.status eq 2 /}
							<td><a disabled class="btn btn-warning btn-sm">{/if $item.operate/}等待管理员"{/$item.operate/}"审批{/else/}{/$item.text/}{//if/}</a></td>
							{/elseif $data.$key.status eq 0/}
							<td><a disabled class="btn btn-warning btn-sm">等待直属上级"{/$item.operate/}"审批</a></td>
							{/elseif $data.$key.status eq 3/}
							<td><a disabled class="btn btn-warning btn-sm">通过</a></td>
							{//if/}
							<td >
								<a class="savebtn btn btn-success btn-sm" data-id="{/$data.$key.id/}" data-leaderid="{/$data.$key.leader/}" data-type="1" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-ok"></i> 同意</a>
								<br/><p></p>
								<i class="btn refuse btn-danger btn-sm" data-toggle="modal" data-target="#refuse" data-id="{/$data.$key.id/}" data-leaderid="{/$data.$key.leader/}"><i class="glyphicon glyphicon-remove"></i> 拒绝</a>
							</td>
							<td>
								<a class="savebtn btn btn-success btn-sm" href="javascript:void(0)" onclick="selectAll('{/$item.appuid/}')"> 选中该用户的所有申请</a><br/>
								<p></p>
								<a class="savebtn btn btn-success btn-sm" href="javascript:void(0)" data-uid="{/$item.appuid/}" data-type="2" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-ok"></i>批量同意</a>
							</td>
						</tr>
					{//foreach/}
				</tbody>
			</table>
		</div>
	</div>
	{/if $totalNum > 10/}
	    <div class="row">
        <div class="panel-body">
            <div style="text-align:center">
                <ul class="pagination">
                    <li><a>共{/$totalNum/}条记录</a></li>
                    <li><a>总共{/$pageInfo.totalPage/}页</a></li>
                    <li><a href="{/$pageInfo.firstLink/}">首页</a></li>
                    {/if $pageInfo.prevPage >= 1/}
                    <li><a href="{/$pageInfo.prevLink/}">上一页</a></li>
                    {//if/}
                    {/foreach from=$pageInfo.pageNumList item=item key=key/}
                    	{/if $pageInfo.nowPage && $pageInfo.nowPage==$key/}
                    		<li class="active"><a href="javascript:return false;" class="cur">{/$key/}</a></li>
                    	{/else/}
                    		<li><a href="{/$item/}">{/$key/}</a></li>
                    	{//if/}
                    {//foreach/}
                    {/if $pageInfo.nextPage <= $pageInfo.totalPage/}
                    <li><a href="{/$pageInfo.nextLink/}">下一页</a></li>
                    {//if/}
                    <li><a href="{/$pageInfo.lastLink/}">最后一页</a></li>
                </ul>
            </div>
        </div>
    </div>
    {//if/}
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
	var nowPage = {/$pageInfo.nowPage/}
	var applyItem = '{/$applyItem/}'
	var applyUser = '{/$applyUser/}'
	window.location="/apply/applycheck?condition="+condition+"&nowPage="+nowPage+"&applyItem="+applyItem+"&applyUser"+applyUser;
})
$('.viewsByCondition').click(function(){
	var applyUser = $('#applyUser').val();
	var applyItem = $('#applyItem').val();
	window.location="/apply/applycheck?applyUser="+applyUser+"&applyItem="+applyItem;

})
</script>