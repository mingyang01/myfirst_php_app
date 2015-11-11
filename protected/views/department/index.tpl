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
            <li><a href="/publish/index">内部功能</a></li>
            <li class="active">部门权限</li>
        </ol>
        <div id="well" class="well">
            <div class="form-inline" role="form" id="form">
                <div class="form-group">
                    <label for="exampleInputName2">项目</label>
                    <select class="form-control" id="business" >
                        {/foreach from=$arrBusiness item=item key=key/}
                            <option {/if $item['business']==$business/}selected{//if/} value="{/$item.business/}">{/$item.cname/}</option>
                        {//foreach/}
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputName2">用户</label>
                    <!-- 
                    <select class="form-control" id="username" >
                        {/foreach from=$arrDepartUsers item=item key=key/}
                            <option {/if $item['mail']==$username/}selected{//if/} value="{/$item.mail/}">{/$item.name/}</option>
                        {//foreach/}
                    </select>
                    -->
                    <input type="text" class="form-control" id="username" placeholder="邮箱前缀(必填)" value="{/$username/}"> 
                </div>
                
                <!--
                <div class="form-group">
                    <label for="funname">功能</label>
                    <input type="text" class="form-control" id="funname" placeholder="功能名称（选填）" value="{/$funname/}">
                </div>
                -->
                <button id="views" data-action="create" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">查看</button>
                <button id="exportEmail" data-action="update" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">Email发送</button>
                <button id="revoke" data-action="getAuth" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-danger">解除权限</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <table id="tt">
        </table>
    </div>
</div>
</div>
<style>
    .sbtn{border:1px solid #CCC; padding:4px;}
</style>

<script type="text/javascript">



$('#views').click(function(){
    window.location = '/department/index?business='+$('#business').val()+'&username='+$('#username').val()+'&funname='+$('#funname').val();
});
$(document).keyup(function(event){
  if(event.keyCode ==13){
        window.location = '/department/index?business='+$('#business').val()+'&username='+$('#username').val()+'&funname='+$('#funname').val();
  }
});

var business = $('#business').val();
var username = $('#username').val();
var funname = $('#funname').val();
$('#tt').datagrid({
        url:'/department/views?business='+business+'&username='+username+'&funname='+funname,
        fitColumns:true,
        singleSelect:false,
        rownumbers:true,
        columns:[[
            {field:'id',checkbox:true},
            {field:'business',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
            {field:'name',title:'<i class="glyphicon glyphicon-globe"> 功能',width:20},
            {field:'username',title:'<i class="glyphicon glyphicon-pushpin"> 用户',width:20},
            {field:'status',title:'<i class="glyphicon glyphicon-pushpin"> 状态',width:20},
            {field:'item',hidden:'true',title:'<i class="glyphicon glyphicon-pushpin"> 权限点',width:20}

        ]],
        pagination: true,
        pageList:[10,15]
    })

// $("<td style='padding: 0 8px;'><input id='sinput' class='easyui-searchbox' style='width: 200px;'/></td> ").
//                 prependTo(".datagrid-toolbar table tbody tr");

$('#exportEmail').click(function(){
    var business = $('#business').val();
    var username = $('#username').val();
    var mailto=prompt("请输入要发送的邮箱前缀,多人请以\",\"分割","");
    console.log(mailto.length);
    if(mailto.length > 0)
    {
        var url = '/department/sendEmail?business='+business+'&username='+username+'&mailto='+mailto;
        $.get(url, function(result){
            alert(result.data)
        });
    }
    //window.location='/department/sendEmail?business='+business+'&username='+username;
   
})
$('#getAuth').click(function(){
    var row = $('#tt').datagrid('getSelections');
    $.get('/export/getAuth',{item:row,uid:row[0].id}, function(data) {
        if(data)
        {
            alert(data)
            $('#tt').datagrid('reload');
        }
    },'json');
})
$('#revoke').click(function(){
     var row = $('#tt').datagrid('getSelections');
    $.get('/department/RevokeAuth',{item:row,uid:row[0].id}, function(data) {
        if(data)
        {
            alert(data)
            $('#tt').datagrid('reload');
        }
    },'json');
});

</script>
