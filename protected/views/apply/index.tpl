{/include file="layouts/header.tpl"/}
<script src="/assets/js/bufferview.js"></script>
<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
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
                <li role="presentation" class="active"><a href="/apply/index"><i class="glyphicon glyphicon-send"></i> 权限申请</a></li>
                <li role="presentation"><a href="/apply/myapply"><i class="glyphicon glyphicon-folder-open"></i> 我的申请</a></li>
                <li role="presentation"><a href="/apply/applycheck"><i class="glyphicon glyphicon-bell"></i> 权限审批</a></li>
                <li role="presentation">
					<a href="/function/getFunnameByUrl"><i class="glyphicon glyphicon-bell"></i> 根据url获取功能信息</a>
				</li>
            </ul>
            <!-- Tab panes -->
        </div>
        <div class="col-md-12">
            <div class="col-md-12 well" style="margin-top:20px;">
                <div class="form-inline" role="form" id="form">
                {/if $business/}
                    <div class="form-group">
                        <label for="" >项目</label>
                        <select name="business" id="business" class="form-control">
                            {/foreach from = $business item = item key = key/}
                            <option value="{/$business.$key.business/}">{/$business.$key.cname/}</option>
                            {//foreach/}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" >状态</label>
                        <select name="status" id="status" class="form-control">
                            <option value="5">全部</option>
                            <option value="0">待审核</option>
                            <option value="3">已获权限</option>
                            <option value="4">没有权限</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">功能</label>
                        <input type="text" class="form-control" id="funname" placeholder="功能名称（选填）" value="{/$funname/}">
                    </div>
                    <div class="form-group">
                        <button id="submit"  type="submit" class="btn btn-success ">查询</button>
                    </div>
                    <div class="form-group">
                        <button class="applybtn btn btn-default">提交申请</button>
                    </div>
                    {/else/}
                    	<div class="form-group">
                        <label for="" >所在部门"{/$depart/}"没有分配功能，请发邮件给quanxian@进行处理</label>
                       </div>
                        {//if/}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="alert alert-success">
                如果你您在权限申请处无法搜到所需功能，<a href="javascript:void(0);" onclick="intro()"><i class="glyphicon glyphicon-fast-forward  "></i> 请点击此处申请</a>
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom:30px;">
            <table id="tt" title="">
            </table>
        </div>
    </div>  
</div>

<!-- Modal -->
<div class="modal fade" id="introModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passModelTitle">权限申请</h4>
      </div>
      <div class="modal-body">
        <div>
        	<label> 说明： </label>
        	<p>&nbsp;&nbsp;&nbsp;&nbsp;亲，若您在权限申请处搜不到所需功能，请发送邮件至
        		<font color="red">quanxian@meilishuo.com</font>进行申请，相关邮件务必抄送主管予以确认（如未确认不予处理），经风控部门审批后，权限管理人员将基于权限分级管理机制进行相应的处理。</p>
        </div>
        <div>
        	<label>发邮件申请权限需要信息格式： </label>
        	<p>&nbsp;&nbsp;&nbsp;&nbsp;用户名：</p>
        	<p>&nbsp;&nbsp;&nbsp;&nbsp;功能名称：</p>
        	<p>&nbsp;&nbsp;&nbsp;&nbsp;截图或链接：</p>
        	<p>&nbsp;&nbsp;&nbsp;&nbsp;申请原因：</p>
        </div>
        
      </div>
      <div class="modal-footer">
        	<button type="button" class="btn btn-default" data-dismiss="modal">已了解</button>
      </div>
    </div>
  </div>
</div>

<script>

    $('#status').val({/$status/});
    
    function intro() {
		$('#introModal').modal('show');
	}
    
    $(function(){
    
    var business = $('#business').val();
    $('#tt').datagrid({
        url:'/apply/condition?business='+business+'&status=4'+'&funname='+'',
        fitColumns:true,
        singleSelect:false,
        rownumbers:true,
        columns:[[
                {field:'id',checkbox:true},
                {field:'cname',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
                {field:'funname',title:'<i class="glyphicon glyphicon-globe"> 功能',width:20},
                {field:'description',title:'<i class="glyphicon glyphicon-pushpin"> 描述',width:20},
                //{field:'status',title:'状态',width:10},
                {field:'status',title:'<i class="glyphicon glyphicon-wrench"> 状态',width:10,formatter:function(index){
                    if(index==3)
                    {
                        return "<i class='btn disabled btn-sm btn-primary'>已获权限</i>";
                    }
                    else if(index==4)
                    {
                        return "<i class='disabled btn btn-sm btn-danger'>没有权限</i>";
                    }
                    else if(index==6)
                    {
                        return "<i class='disabled btn btn-sm btn-danger'>被拒绝</i>";
                    }
                    else
                    {
                        return "<i class='btn disabled btn-sm btn-warning'>等待审核</i>";
                    }
                   
                }}
        ]],
        pagination: true,
        pageList:[10,15]
    })
    $('#submit').click(function(){
        var status = $('#status').val();
        var business = $('#business').val();
        var funname = $('#funname').val();
        $('#tt').datagrid({
            url:'/apply/condition?business='+business+'&status='+status+'&funname='+funname,
        })
    });
    

})
$('.applybtn').on('click', function() {
    var rows = $('#tt').datagrid('getSelections');
    var results = [];
    if(rows) {
        for (var i = 0; i < rows.length; i++) {
            if(rows[i].status==4||rows[i].status==6) {
                results.push(rows[i]);
            } else {
                alert("请选择要申请权限的功能！");
                return;
            }
        };
    }
    if (results.length<=0) {
    	alert("请选择要申请权限的功能！");
        return;
    }
    $.post('/apply/addapply',{rows:results},function(data) {
    	var arr = eval(data);
        alert(arr)
        $('#tt').datagrid('reload');
    });

});

</script>
