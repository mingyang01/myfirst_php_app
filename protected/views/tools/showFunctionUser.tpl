{/include file="layouts/header.tpl"/}
<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">运营工具</li>
	            <li class="active">查询功能有权限用户</li>
	        </ol>
			<!-- Tab panes -->
	        <div id="well" class="well">
	            <div class="form-inline" role="form" id="form">
	                <div class="form-group">
	                    <label for="exampleInputName2">业务号：</label>
	                    <input type="text" class="form-control" name="business" id="business" placeholder="比如：focus" value="{/$business/}">
	                </div>
	                <div class="form-group">
	                    <label for="exampleInputName2">功能名称：</label>
	                    <input type="text" class="form-control" id="funname" name="funname" placeholder="完整的功能名称" value="{/$funname/}">
	                </div>
	                <button id="views" type="button" class="btn btn-default" onclick="search_info(0)">查看</button>
	                <button id="views" type="button" class="btn btn-sm btn-default" onclick="search_info(1)">下载</button>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="row">
	<div class="col-md-12">
		共有{/$total/}条
	</div>
		<div class="col-md-12">
    	<table class="table table-hover table-striped table-bordered table-condensed">
        	 <thead>
                <tr align="center">
                	<th width="15%">功能名称</th>
                	<th width="10%">用户uid</th>
                    <th width="15%">用户名</th>
                    <th width="15%">部门名称</th>
                    <th width="20%">权限对应角色</th>
                </tr>
            </thead>
            {/foreach from=$users item=item key-key/}
            <tr>
            	<td align=center>{/$funname/}</td>
                <td>{/$item.userid/}</td>
                <td>{/$item.name/}</td>
                <td>{/$item.depart/}</td>
                <td align=center>{/$item.itemname/}</td>
            </tr>
            {/foreachelse/}
            	<tr><td colspan=4 align=center>{/$msg/}</td></tr>
            {//foreach/}
        </table>
	</div>
	</div>
</div>

<script type="text/javascript">

	function search_info(excel) {
		var business = $("#business").val();
		var funname = $("#funname").val();
		if (business){
			window.location.href = "/function/getFunctionsRight?business="+business+"&funname="+funname+"&excel="+excel;
		}
	}

</script>

