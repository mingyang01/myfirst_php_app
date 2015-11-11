{/include file="layouts/header.tpl"/}
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<script>
    console.log({/json_encode($depart)/});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/publish/index">项目发布</a></li>
                <li class="active">部门功能</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="well" class="well clearfix">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="" class="">
                            部门：
                        </label>
                        <div class="form-group">
                            <select id='depart' name="businesses" class="js-example-basic-single form-control" >
                                {/foreach from=$departLeaders item=item key=key/}
                                    <option {/if $depart == $item.itemname /} selected {//if/} value="{/$item.itemname/}">{/$item.itemname/}</option>
                                {//foreach/}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">
                            管理员：
                        </label>
                        <div class="form-group">
                            <input id="admin" type="text" disabled class="form-control" value="{/$admin/}" >
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="export-depart" class="btn btn-success">导出部门的功能</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="dg"></table>
        </div>
    </div>
</div>
<script>
var depart = $('#depart').val();
var admin = $('#admin').val();
$('#depart').change(function(){
    var depart = $(this).val();
    //window.location = '/risk/index?depart='+depart+'&admin='+admin;
    $.get('/risk/index', {depart:depart}, function(data) {
        console.log(data)
        $('#admin').val(data);
    },'json');
    
    $('#dg').datagrid({
        url:'/risk/GetDepartFunction?depart='+depart+'&admin='+admin
    })
   
})
$('#dg').datagrid({ 
    url:'/risk/GetDepartFunction?depart='+depart+'&admin='+admin,
    fitColumns:true,
    singleSelect:true,
    rownumbers:true,
    columns:[[
        {field:'id',checkbox:true},
        {field:'business',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
        {field:'cname',title:'<i class="glyphicon glyphicon-th-list"> 项目名称',width:20},
        {field:'funname',title:'<i class="glyphicon glyphicon-globe"> 功能',width:20},
        {field:'item',title:'<i class="glyphicon glyphicon-pushpin"> 功能点',width:20}
            
    ]],
    pagination: true,
    pageList:[10,20,50,100]
})
//导出请求
$('#export-depart').click(function(){
    var depart = $('#depart').val();
    var admin = $('#admin').val();
    window.location='/risk/exportHtml?depart='+depart+'&admin='+admin;
})
</script>