{/include file="layouts/header.tpl"/}
<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">功能名查询</li>
	        </ol>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation">
					<a href="/apply/index"><i class="glyphicon glyphicon-send"></i> 权限申请</a>
				</li>
				<li role="presentation">
					<a  href="/apply/myapply"><i class="glyphicon glyphicon-folder-open"></i> 我的申请</a>
				</li>
				<li role="presentation">
					<a href="/apply/applycheck"><i class="glyphicon glyphicon-bell"></i> 权限审批</a>
				</li>
				<li role="presentation"  class="active">
					<a href="/function/getFunnameByUrl"><i class="glyphicon glyphicon-bell"></i> 根据url获取功能信息</a>
				</li>
			</ul>
			<!-- Tab panes -->
	        <div id="well" class="well">
	            <div class="form-inline" role="form" id="form">
	                <div class="form-group">
	                    <label for="exampleInputName2">功能url：</label>
	                    <input type="text" class="form-control" style="width:500px" id="search_url" placeholder="功能url" value="{/$url/}">
	                </div>
	                <button id="views" type="button" class="btn btn-default" onclick="search_info()">查看</button>
					<p><font size="2px" color="gray">＊完整的请求url，比如http://developer.meiliworks.com//apply/index</font></p>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12">
    	<table class="table table-hover table-striped table-bordered table-condensed">
        	 <thead>
                <tr align="center">
                <th width="10%">项目</th>
                    <th width="10%">功能名</th>
                    <th width="18%">描述</th>
                    <th width="20%">菜单</th>
					<th width="27%">url</th>
                    <th width="15%">操作时间</th>
                </tr>
            </thead>
            {/foreach from=$function item=item key=key/}
            <tr>
                <td>{/$item.business/}</td>
                <td>{/$item.funname/}</td>
                <td>{/$item.description/}</td>
                <td>{/$item.menu/}</td>
				<td>{/$item.url/}</td>
                <td>{/$item.unix/}</td>
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

