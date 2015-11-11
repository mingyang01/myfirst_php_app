{/include file="layouts/header.tpl"/}
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				<ol class="breadcrumb">
		            <li><a href="/">Home</a></li>
		            <li><a href="/function/index">功能管理</a></li>
		            <li class="active">功能点管理</li>
	        	</ol>
        	</div>
		</div>
	    <div class="col-md-12" style="padding-bottom:10px;">
	    	<div class="col-md-12">
	    		{/if count($message) gt 0/}
	    		<div id="messagewrap" class="{/$message.class/}" role="alert">{/$message.msg/}</div>
	    		{//if/}
	    	</div>
	    </div>
		<div class="col-md-12 container-main" style="padding-bottom:40px;">
			{/foreach from=$data item=it key=key/}
				<div class="col-md-6" style="margin-bottom:10px;">
					<div class="panel panel-default">
						<div class="panel-heading clearfix">
							<div class="col-sm-1">
								{/if $data[$key].submitflag eq 0/}
								<h4 class="glyphicon glyphicon-bell"></h4>
								{//if/}
								{/if $data[$key].submitflag eq 1/}
								<h4 class="glyphicon glyphicon-bell"></h4>
								{//if/}
								{/if $data[$key].submitflag eq 2/}
								<h4 class="glyphicon glyphicon-list"></h4>
								{//if/}
							</div>
							<div class="col-sm-8 text-rights">
								{/if $data[$key].submitflag eq 0/}
								<h4>删除申请待审核</h4>
								{//if/}
								{/if $data[$key].submitflag eq 1/}
								<h4>添加申请待审核</h4>
								{//if/}
								{/if $data[$key].submitflag eq 2/}
								<h4>审核通过</h4>
								{//if/}
							</div>
							<div class="col-sm-3" style="padding-right:0px;">
								<div class="col-sm-7 text-right" style="padding-right:0px;">
									{/if $data[$key].submitflag neq 2/}
									<span  class="glyphicon glyphicon-ok btn btn-success btn-sm passbtn" data-id="{/$data[$key].id/}"></span>
									{/else/}
									<span  disabled class="glyphicon glyphicon-ok btn btn-success btn-sm" data-id="{/$data[$key].id/}"></span>
									{//if/}
								</div>
								<div class="col-sm-5 text-right" style="padding-right:0px;">
									<span  class="glyphicon glyphicon-remove btn btn-danger btn-sm deletebtn" data-id="{/$data[$key].id/}"></span>
								</div>
							</div>
						</div>
						<div class="panel-body" style="padding:10px; padding-bottom:0px;">
							<table class="table table-striped table-condensed">
								<tr class="form-group">
									<td class="col-sm-3 text-right"><h5>功能名称:</h5></td>
									<td class="col-sm-9" style="padding-left:20px;"><h5>{/$data[$key].subitem/}</h5></td>
								</tr>
								<tr>
									<td class="col-sm-3 text-right"><h5>英文名称:</h5></td>
									<td class="col-sm-9" style="padding-left:20px;"><h5>{/$data[$key].authority/}</h5></td>
								</tr>
								<tr>
									<td class="col-sm-3 text-right"><h5>规则:</h5></td>
									<td class="col-sm-9" style="padding-left:20px;"><h5>{/$data[$key].bizrule/}</h5></td>
								</tr>
								<tr>
									<td class="col-sm-3 text-right"><h5>描述:</h5></td>
									<td class="col-sm-9" style="padding-left:20px;"><h5>{/$data[$key].description/}</h5></td>
								</tr>
								<tr>
									<td class="col-sm-3 text-right"><h5>数据:</h5></td>
									<td class="col-sm-9" style="padding-left:20px;"><h5>{/$data[$key].data/}</h5></td>
								</tr>
								<tr style="border-bottom:1px solid #ddd">
									<td class="col-sm-3 text-right"><h5>权限:</h5></td>
									<td class="col-sm-9" style="padding-left:20px;"><h5>{/$authflag[$key]/}</h5></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			{//foreach/}
        </div>
	</div>
</div>
<script>
	$(function(){
		setTimeout(function(){$("#messagewrap").hide("slow")},2000);
		$(".deletebtn").click(function(){
			$(this).parent().parent().parent().parent().parent().hide("slow");
			$.post('/apply/itemdelete', {id:$(this).attr('data-id')}, function(data) {
				var data = eval("("+data+")");
				if(data)
				{
					window.location = "/item/index?functionid="+functionid;
				}
			});
		});
		
	})
</script>			