{/include file="layouts/header.tpl"/}
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
	            <li class="active">部门角色导出</li>
	        </ol>
        	<div class="well">
        		<div class="form-inline">
        			<label for="depart">部门：</label>
        			<select id="chooseDepart" type="text" class="js-example-basic-single form-control" placeholder="请选择部门名称">
        				{/if $alldepart/}
        					{/foreach from = $alldepart item = item key = key/}
        						<option {/if $depart==$item/} selected {//if/} value="{/$item/}">{/$item/}</option>
        					{//foreach/}
        				{//if/}
        			</select>
        			<button id="export-btn" class="btn btn-info btn-sm">下载</button>
        		</div>
        	</div>
	    </div>
	    <div class="col-md-12">
	    	{/if $roles/}
	    	<table class="table table-bordered">
	    		<tr>
	    			<th>部门名称</th><th>角色名称</th>
	    		</tr>
		    		{/foreach from = $roles item=item key = key/}
		    			<tr>
		    				<td>{/$item.parent/}</td><td>{/$item.child/}</td>
		    			</tr>
		    		{//foreach/}
	    	</table>
	    	{/else/}
	    	<div class="alert alert-danger">
	    		当前部门下还没有角色，请切换其他部门查看
	    	</div>
		    {//if/}
	    </div>
	</div>
</div>
<script>
	$('#chooseDepart').change(function(){
		window.location = "/operation/index?depart="+$(this).val();
	})
	$('#export-btn').click(function(){
		window.open("/operation/index?depart="+$('#chooseDepart').val()+"&export="+true,'角色导出')
	})
</script>