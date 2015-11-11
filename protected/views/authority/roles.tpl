{/include file="layouts/header.tpl"/}
<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>

<div class="container">
<div class="row">
    <div class="col-md-12" style="padding-top:20px;">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">角色管理</li>
        </ol>

        <div id="well" class="well">
            <div class="form-inline" role="form" id="form">
            	{/if $choice_depart/}
                <div class="form-group">
                    <select name="function" class="form-control" id="depart">
                    {/foreach from=$depart item=item key=key/}
                    	{/if $key neq $departid/}
                    		<option value="{/$key/}">{/$item/}</option>
                    	{//if/}
                    {//foreach/}
                    </select>
                </div>
                {//if/}
                <input type="text" value="{/$depart.$departid/}" disabled/>
                <button data-action="create" type="button" data-toggle="modal" data-target="#dlg" class="btn btn-default">添加</button>
               <button class="btn btn-default" id="delete">删除</button>

                <div class="col-sm-1 right">
                    <button id="tool-auth-manager" class="btn btn-default">角色权限</button>
                </div>

                <div class="col-sm-1 right">
                    <button id="tool-user-manager" class="btn btn-default">角色成员</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 tablemain" style="margin-bottom:30px;">
        <table id="dg"  class="easyui-datagrid"
                rownumbers="true" style="border:none;" fitColumns="true" singleSelect="true">
            <thead>

                <tr>
                    <th data-options="field:'id',checkbox:true"></th>
                    <th field="name" width="50">名字</th>
                    <th field="item" width="30">部门</th>
                    <th field="disc" width="20">描述</th>
                    <th field="rule" width="20">规则</th>
                    <th field="data" width="50">数据</th>     
                </tr>
            </thead>
                  
                 {/foreach from=$data key=i item=u/}
                    <tr>    
                        <td>{/$data[$i].id/}</td>
                        <td>{/$data[$i].name/}</td>
                        <td>{/$departname/}</td>
                        <td>{/$data[$i].description/}</td>
                        <td>{/$data[$i].bizrule/}</td>
                        <td>{/$data[$i].data/}</td>
                    </tr>
                {/foreachelse/}
                  <div>"{/$departname/}"部门下没有角色</div> 
                {//foreach/}
             
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
                    <form method="post" id="fm" class="form-horizontal" role="form">
                    	<input type="hidden" id="id" name="id" required="true">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名字:</label>
                            <div class="col-sm-9">
                                <input id="name" name="name" class="form-control" required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">部门:</label>
                            <div class="col-sm-9">
                                <input id = "depart_add" name="item" readOnly class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">描述:</label>
                            <div class="col-sm-9">
                                <input class="form-control" name="disc" /></li>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">规则:</label>
                            <div class="col-sm-9">
                                <input class="form-control" name="rule" /></li>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">数据:</label>
                            <div class="col-sm-9">
                                <input name="data" class="form-control" required="true">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button id="submit" type="button" class="btn btn-default">确认</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<script>
    var depart = "{/$departname/}";
    var departid = "{/$departid/}"; 
    var role_pre = "{/$depart.$departid/}"
</script>
<script type="text/javascript" src="/assets/js/roles.js"></script>