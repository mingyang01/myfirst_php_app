{/include file="layouts/header.tpl"/}
<div class="container">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/publish/index">项目发布</a></li>
            <li class="active">功能管理</li>
        </ol>
        <div id="well" class="well clearfix">
            <div class="col-sm-3">
                <button data-action="create" data-ttt="添加" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">添加</button>
                <button data-action="update" data-title="修改" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">修改</button>
                <button class="btn btn-default" data-title="删除" id="delete">删除</button>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="text-right">
                        <input id="searchContent" class="form-control"  name="search" placeholder="输入功能名称"/>
                    </label>
                    <button  class="searchBtn btn btn-success">搜索</button>
                </div>
            </div>
            <div class="col-sm-1 right" style="margin-right:20px;">
                <button id="#addaction" data-action="addaction" data-title="添加action" data-toggle="modal" data-target="#addaction" class="btn btn-default">添加action</button>
             </div>
             <div class="col-sm-1 right">
                <button class="checksubmit btn btn-success">提交审核</button>
             </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-bottom:30px;">
        <table id="dg">
        </table>
    </div>
</div>    
    <div id="dlg" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <form id="fm" method="post" novalidate>
                        <div class="form-group" style="display:none;">
                            <label class="text-right">id:</label>
                            <input name="id" class="form-control" required="true">
                        </div>
                        <div class="form-group">
                            <label class="text-right">项目:</label>
                            <input id="business" class="form-control" readonly="readonly" name="business" required="true">
                        </div>
                        <div class="form-group">
                            <label class="text-right">功能:</label>
                            <input class="form-control"  name="funname" required="true">
                        </div>
                        <div class="form-group">
                            <label class="text-right">描述:</label>
                            <input class="form-control" name="description" required="true">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">路径:</label>
                            <input required="true" name="url" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">签名:</label>
                            <input required="true" name="sign" type="text" class="form-control">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="submit" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="addaction" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <form id="actionfm" method="post" novalidate>
                        <div class="form-group" style="display:none;">
                            <label class="text-right">id:</label>
                            <input id="sid" name="id" class="form-control" required="true">
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">项目:</span>
                                    <input id="businesssub" class="form-control" readonly="readonly" name="businesssub" required="true">
                                </div>
                            </div>
                            <div class="col-sm-6 ">
                                <div class="input-group">
                                    <span class="input-group-addon">功能:</span>
                                    <input class="form-control" id="funname" readonly="readonly" name="funname" required="true">
                                </div>
                            </div>
                        </div>
                        <div id="actionbox" class="form-group clearfix">
                            
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        namespace
                                    </span>
                                    <input id="controllersub" type="text" name="controller" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-5" style="padding-right:0px;">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        point
                                    </span>
                                    <input id="actionsub" type="text" name="action" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-1" style="padding-left:0px;">
                                <span style="top: 0px;
margin-left: -7px;
z-index: 999;border-radius:0 4px 4px 0" id="actionsubmit" class="btn btn-default glyphicon glyphicon-plus"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script>
    var business = '{/$business/}';
</script>
<script>
    $('#dg').datagrid({
        url:'/function/GetFunctions?business='+business+'&funname='+'',
        fitColumns:true,
        singleSelect:true,
        rownumbers:true,
        columns:[[
                {field:'id',checkbox:true},
                {field:'business',title:'<i class="glyphicon glyphicon-th-list"> 项目',width:20},
                {field:'funname',title:'<i class="glyphicon glyphicon-globe"> 功能',width:20},
                {field:'url',title:'<i class="glyphicon glyphicon-pushpin"> 路径',width:20},
                {field:'sign',title:'<i class="glyphicon glyphicon-pushpin"> 签名',width:20},
                {field:'description',title:'<i class="glyphicon glyphicon-pushpin"> 描述',width:20},
                {field:'unix',title:'<i class="glyphicon glyphicon-pushpin"> 更改日期',width:20},
                {field:'status',title:'<i class="glyphicon glyphicon-pushpin"> 状态',width:20,formatter:function(index){
                    if(index==0)
                        return '<button disabled class="btn btn-danger btn-sm">未审核</button>';
                    if(index==1)
                        return '<button disabled class="btn btn-warning btn-sm">待审核</button>';
                    if(index==2)
                        return '<button disabled class="btn btn-primary btn-sm">已审核</button>';
                }}
                
        ]],
        pagination: true,
        pageList:[10,15]
    })
</script>
<script type="text/javascript" src="/assets/js/function.js"></script>
