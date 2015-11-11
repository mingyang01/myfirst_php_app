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
                                {/foreach from=$departs item=item key=key/}
                                    <option {/if $depart == $key /} selected {//if/} value="{/$key/}">{/$item/}</option>
                                {//foreach/}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button id="export-depart" class="btn btn-success">导出</button>
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
    $('#dg').datagrid({
        url:'/risk/GetDepartStaff?depart='+depart
    })
})
$('#dg').datagrid({ 
    url:'/risk/GetDepartFunction?depart='+depart,
    fitColumns:false,
    singleSelect:true,
    rownumbers:true,
    frozenColumns:[[
       {field:'id',checkbox:true},
       {field:'name',title:'<i class="glyphicon glyphicon-th-list"> 姓名',width:200,sortable:true},
       {field:'mail',title:'<i class="glyphicon glyphicon-th-list"> 邮箱',width:200},
    ]],
    columns:[[
        {field:'function',title:'<i class="glyphicon glyphicon-th-list"> 功能',width:'100%'}
    ]]
})
//导出请求
$('#export-depart').click(function(){
    var depart = $('#depart').val();
    window.location='/risk/ExportDepartStaffAuth?depart='+depart;
})
</script>