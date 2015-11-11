{/include file="layouts/header.tpl"/}
<div class="container">
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">项目发布</li>
        </ol>
        <div id="well" class="well clearfix">
            <div class="col-sm-6">
                <button id="add" class="btn btn-default">添加</button>
                <button id="update" class="btn btn-default">修改</button>
                <button id="delete" class="btn btn-default">删除</button>
            </div>
            <div class="col-sm-1 right" style="margin-right:4px;">
                <button id="white-manage" class="btn btn-default" plain="true">白名单管理</button>
            </div>

            <div class="col-sm-1 right">
                <button id="menu-manage" class="btn btn-default" plain="true">菜单管理</button>
            </div>

            <div class="col-sm-1 right">
                <button id="function-manage" class="btn btn-default" plain="true">功能管理</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-bottom:30px;">
        <table id="dg" rownumbers="true" style="border:none;" fitColumns="true" singleSelect="true">
            <thead>
                <tr>
                    <th data-options="field:'id',checkbox:true"></th>
                    <th field="name" width="40">项目</th>
                    <th field="cname" width="50">项目名称</th>
                    <th field="desc" width="50">域名</th>
                    <th field="leader" width="50">负责人</th>
                    <th field="time" width="50">更新时间</th>

                    <th field="developer" width="50">开发人</th>
                    <th field="creator" width="50">创建人</th>
                </tr>
            </thead>
            <tbody>
                {/foreach from=$redata key=i item=u/}
                {/if $redata[$i]['business'] neq "work"/}
                <tr>
                    <td>{/$redata[$i].id/}</td>
                    <td>{/$redata[$i].business/}</td>
                    <td>{/$redata[$i].cname/}</td>
                    <td><a href="http://{/$redata[$i].description/}">{/$redata[$i].description/}</a></td>
                    <td>{/$redata[$i].leader/}</td>
                    <td>{/$redata[$i].unix/}</td>
                    <td>{/$redata[$i].developer/}</td>
                    <td>{/$redata[$i].creator/}</td>
                </tr>
                {//if/}
                {//foreach/}
            </tbody>
        </table>
    </div>
</div>
    <div id="dlg" class="easyui-dialog" style="width:400px;height:auto; padding:10px 20px" closed="true" buttons="#dlg-buttons">
        <form id="fm" method="post" novalidate role="form">
            <div class="form-group" style="display:none;">
                <label class="text-right">id:</label>
                <input name="id" class="easyui-textbox" required="true">
            </div>
            <div class="form-group">
                <label class="text-right">项目:</label>&nbsp; &nbsp;<font color="red">(*业务ID，由英文字母和数字组成,创建后不能修改)</font>
                <input id="businessname" class="form-control" name="name" required="true" placeholder="业务ID，由英文字母和数字组成,创建后不能修改">
            </div>
            <div class="form-group">
                <label class="text-right">开发人:</label>&nbsp; &nbsp;<font color="red">(*开发者默认拥有项目的所有功能权限)</font>
                <input id="developer" class="form-control" name="developer" required="true" placeholder="开发者默认拥有项目的所有功能权限">
            </div>
            <div class="form-group">
                <label class="text-right">负责人:</label>
                <input class="form-control" name="leader" placeholder="负责人">
            </div>
            <div class="form-group">
                <label class="text-right">项目名称:</label>
                <input class="form-control" name="cname" placeholder="项目名称">
            </div>
            <div class="input-group">
                <span class="input-group-addon">http://</span>
                <input class="form-control" name="desc" required="true" placeholder="域名">
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <button href="javascript:void(0)" class="btn btn-default" onclick="saveUser()">保存</button>
        <button href="javascript:void(0)" class="btn btn-default" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">退出</button>
    </div>

</div>
<script type="text/javascript" src="/assets/js/publish.js"></script>