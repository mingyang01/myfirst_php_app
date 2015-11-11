{/include file="layouts/header.tpl"/}
<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">运营工具</li>
	            <li class="active">查询用户有权限功能</li>
	        </ol>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="/function/getUserFunctions"><i class="glyphicon glyphicon-bell"></i>查询用户有权限功能</a>
				</li>
				<li role="presentation">
					<a href="/function/getFunctionsRight"><i class="glyphicon glyphicon-bell"></i>查询功能有权限用户</a>
				</li>
			</ul>
			<!-- Tab panes -->
	        <div id="well" class="well">
	            <div class="form-inline" role="form" id="form">
	                <div class="form-group">
	                    <label for="exampleInputName2">用户邮箱前缀：</label>
	                    <input type="text" class="form-control" name="user" id="user" placeholder="用户邮箱前缀" value="{/$user/}">
	                </div>
	                <button id="views" type="button" class="btn btn-default" onclick="search_info()">查看</button>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12">
    	<table class="table table-hover table-striped table-bordered table-condensed">
        	 <thead>
                <tr align="center">
                <th width="20%">用户</th>
                    <th width="20%">功能名</th>
					<th width="60%">url</th>
                </tr>
            </thead>
            {/foreach from=$funnames item=item key-key/}
            <tr>
                <td>{/$item.business/}</td>
                <td>{/$item.funname/}</td>
                <td>{/$item.description/}</td>
            </tr>
            {/foreachelse/}
            	<tr><td>没有数据</td></tr>
            {//foreach/}
        </table>
	</div>
	</div>
</div>

<script type="text/javascript">

	function search_info() {
		var url = $("#search_url").val();
		if (url){
			window.location.href = "/function/getFunnameByUrl?url=" + url;
		}
	}

</script>

