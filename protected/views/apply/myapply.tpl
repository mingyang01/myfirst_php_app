{/include file="layouts/header.tpl"/}
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
				<li role="presentation" class="active">
					<a  href="/apply/myapply"><i class="glyphicon glyphicon-folder-open"></i> 我的申请</a>
				</li>
				<li role="presentation">
					<a href="/apply/applycheck"><i class="glyphicon glyphicon-bell"></i> 权限审批</a>
				</li>
				<li role="presentation">
					<a href="/function/getFunnameByUrl"><i class="glyphicon glyphicon-bell"></i> 根据url获取功能信息</a>
				</li>
			</ul>
			<!-- Tab panes -->
		</div>
	</div>
</div>
<div class="container" style="padding-top:20px;">
	<div class="row" style="height:380px;overflow:auto;">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead class="thead">
					<tr class="active">
						<th colspan=2 class="col-sm-3" style="line-height:50px;"><i class="glyphicon glyphicon-list"></i> 您所申请的条目</th>
						<th rowspan=2 class="col-sm-1" style="line-height:60px;"><i class="glyphicon glyphicon-time"></i> 申请时间</th>
						<th colspan=3 class="text-center">进展情况</th>
					</tr>
					<tr class="active">
						<th class="col-sm-1">项目</th>
						<th class="col-sm-2">功能</th>
						<th class="col-sm-2">上级审批</th>
						<th class="col-sm-2">管理员审批</th>
					</tr>
				</thead>
				<tbody>
					{/foreach from=$data item = item  key = key/}
						<tr>
							<td>{/$data.$key.cname/}</td>
							<td>{/$data.$key.cname/}/{/$data.$key.funname/}</td>
							<td>{/$data.$key.unix/}</td>
							<td colspan=2>
								{/if $data.$key.status eq 101/}
									<p role="alert" class="alert alert-danger">{/$item.text/}； 被拒绝，原因：{/$data.$key.message/}</p>
								{/elseif $data.$key.status eq 0/}
									<div class="progress" style="margin:0px;">
										<div role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color: 00ff00">
											<span  style="padding-left:10px;color:#000;">{/$item.text/}</span>
										</div>
									</div>
								{/else/}
									<div class="progress" style="margin:0px;">
										<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {/$data.$key.status/}%; ">
											<span  style="padding-left:10px;color:#000;">{/$item.text/}</span>
										</div>
									</div>
								{//if/}
							</td>
						</tr>
					{//foreach/}
				</tbody>
			</table>
		</div>
	</div>
</div>
