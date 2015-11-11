{/include file="layouts/header.tpl"/}
<script src="/assets/js/bufferview.js"></script>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">白名单管理</li>
	        </ol>

	        <div class="well">
	        	<button id="add" class="btn btn-default" data-toggle="modal" data-target=".modal">添加</button>
                <button id="delete" class="btn btn-default" >删除</button>
        	</div>
        	<table id="tt" title=""
            	rownumbers="true" style="border:none;" fitColumns="true" singleSelect="true">
    		</table>
	        
	    </div>

		<div id="dlg" class="easyui-dialog" style="width:400px;height:auto; padding:10px 20px" closed="true" buttons="#dlg-buttons">
	        <form id="fm" method="post" novalidate role="form">
	            <div class="form-group" style="display:none;">
	                <label class="text-right">id:</label>
	                <input name="id" class="easyui-textbox" required="true">
	            </div>
	            <div class="form-group">
	                <label class="text-right">项目:</label>
	                <input id="project" readOnly="readOnly" class="form-control" name="project" required="true" placeholder="项目">
	            </div>
	            <div class="form-group">
	                <label class="text-right">namespace:</label>
	                <input id="controller" class="form-control" name="controller" required="true" placeholder="‘项目ID’或者‘项目ID/action’">
	            </div>
	            <div class="form-group">
	                <label class="text-right">point:</label>
	                <input class="form-control" name="action" placeholder="‘功能名称’或者‘没有域的url’">
	            </div>
	            <div class="form-group">
	                <label class="text-right">comment:</label>
	                <input class="form-control" name="comment" placeholder="描述">
	            </div>
	        </form>
		    </div>
		    <div id="dlg-buttons">
		        <button href="javascript:void(0)" class="btn btn-default" id="savebtn">保存</button>
		        <button href="javascript:void(0)" class="btn btn-default" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">退出</button>
		    </div>

		</div>
	</div>
</div>
<script>
	var project = {/json_encode($project)/};
	var url = "";
	$('#tt').datagrid({
		url:'/whitelist/getwhitelist?project='+project,
		columns:[[
			{field:'id',checkbox:'true'},
			{field:'project',title:'项目',width:20},
			{field:'controller',title:'namespace',width:20},
			{field:'action',title:'point',width:20},
			{field:'creator',title:'creator',width:20},
			{field:'comment',title:'comment',width:20}
		]]
	});
	$('#add').click(function(){
		$('#dlg').dialog('open').dialog('setTitle','修改');
		$('#fm').form('clear');
		$('#project').val(project);
		url = '/whitelist/addlist';
	});
	$('#update').click(function(e){
		var row = $('#tt').datagrid('getSelections')[0];
		if(row)
		{
			$('#dlg').dialog('open').dialog('setTitle','修改');
			$('#fm').form('load',row);
			url = '/whitelist/updatelist'
		}
		else
		{
			var msg = "请选择要修改的条目"
			 $.messager.show({
                title: 'message',
                msg: msg
            });
		}
	});
	$('#savebtn').click(function(){
		$('#fm').form('submit',{
	        url: url,
	        onSubmit: function(){
	            return $(this).form('validate');
	        },
	        success: function(result){
	            if (result){
	            	result = eval('('+result+')')
	                alert(result.msg);
	                window.location.reload();
	            }  else {
	            	alert("操作失败！");
	            }
	        }
    	});
	});
	$('#delete').click(function(){
		var row = $('#tt').datagrid('getSelections')[0];
		if(row) {
			$.post('/whitelist/deletelist',{'id':row.id}, function(result) {
				
				if (result){
	                alert(result.msg);
	                window.location.reload();
	            }  else {
	            	alert("操作失败！");
	            }
			},'json');
			
		} else {
			alert("请选择要删除的条目！");
		}
	});

</script>