{/include file="layouts/navigator.tpl"/}
    <div class="container">
    <div class="col-md-12">
        <div id="well" class="well">
        <a href="javascript:void(0)" class="btn btn-default" iconCls="icon-add" plain="true" onclick="newUser()">添加</a>
        <a href="javascript:void(0)" class="btn btn-default" iconCls="icon-edit" plain="true" onclick="editUser()">修改</a>
        <a href="javascript:void(0)" class="btn btn-default" iconCls="icon-remove" plain="true" onclick="destroyUser()">删除</a>
        </div>
    </div>


    <div class="col-md-12 tablemain" style="margin-bottom:30px;">
    <table id="dg"  class="easyui-datagrid" 
            url="menufix" 
            rownumbers="true" style="border:none;" fitColumns="true" singleSelect="true">
        <thead>

            <tr>
                <th field="id" width="0"></th>
                <th field="business" width="20">项目</th>
                <th field="first" width="50">一级菜单</th>
                <th field="second" width="50">二级菜单</th>
                <th field="url" width="50">路径</th>
                <th field="sign" width="50">签名</th>
                <th field="creator" width="20">创建者</th>
                
            </tr>
        </thead>
              
             {/foreach from=$data key=i item=u/}
                   <tr>    
                    <td><span style="display:none;">{/$data[$i].id/}</span></td>
                    <td>{/$data[$i].business/}</td><td>{/$data[$i].first/}</td>
                    <td>{/$data[$i].second/}</td><td>{/$data[$i].url/}</td>
                    <td>{/$data[$i].sign/}</td><td>{/$data[$i].creator/}</td>
                  </tr>
            {//foreach/}
         
    </table>
</div>
    
    
    <div id="dlg" class="easyui-dialog" style="width:400px;height:380px; padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle">编辑的信息</div>
        <form id="fm" method="post" novalidate>
            <div class="fitem " style="display:none;">
                <label class="text-right">id:</label>
                <input name="id" class="easyui-textbox" required="true">
            </div>
            <div class="fitem ">
                <label class="text-right">项目:</label>
                <input name="business" class="easyui-textbox" required="true">
            </div>
            <div class="fitem">
                <label class="text-right">一级菜单:</label>
                <input name="first" class="easyui-textbox" required="true">
            </div>
            <div class="fitem">
                <label class="text-right">二级菜单:</label>
                <input name="second" class="easyui-textbox">
            </div>
            <div class="fitem">
                <label class="text-right">路径:</label>
                <input name="url" class="easyui-textbox" required="true">
            </div>
            <div class="fitem">
                <label class="text-right">签名:</label>
                <input name="sign" class="easyui-textbox" required="true">
            </div>
        </form>
    </div>
    <div id="dlg-buttons" style="margin-bottom:10px;padding-bottom:10px;">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">保存</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">退出</a>
    </div>
</div>
    <script type="text/javascript">
        var url;
        function newUser(){
            $('#dlg').dialog('open').dialog('setTitle','添加');
            $('#fm').form('clear');
            url = '/admin/menuadd';
        }
        function editUser(){
            var row = $('#dg').datagrid('getSelected');
            row.id=parseInt($(row.id).html());
            if (row){
                $('#dlg').dialog('open').dialog('setTitle','更改');
                $('#fm').form('load',row);
                url = '/admin/menuupdata';
            }
        }
        function saveUser(){

            $('#fm').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result){
                        $.messager.show({
                            title: 'message',
                            msg: result
                        });
                        setTimeout(function (){
                        $('#dlg').dialog('close');        
                        $('#dg').datagrid('reload'); 
                        window.location.reload();},
                        500);
                        
                    } 
                }
            });
        }
        function destroyUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','确定要删除吗?',function(r){
                    if (r){
                        $.post('/admin/dele',{id:$(row.id).html()},function(result){
                            if (result){
                                 setTimeout(function (){
                                    $('#dlg').dialog('close');        
                                    $('#dg').datagrid('reload'); 
                                    window.location.reload();},
                                    500);   // reload the user data
                            } else {
                                $.messager.show({    // show error message
                                    title: '提示信息',
                                    msg: result
                                });
                            }
                        },'json');
                    }
                });
            }
        }
    </script>
    <style type="text/css">
        #fm{
            margin:0;
            padding:10px 30px;
        }
        .ftitle{
            font-size:14px;
            font-weight:bold;
            padding:5px 0;
            margin-bottom:10px;
            border-bottom:1px solid #ccc;
        }
        .fitem{
            margin-bottom:5px;
        }
        .fitem label{
            display:inline-block;
            width:80px;
        }
        .fitem input{
            width:160px;
        }
        .tablemain
        {
            height:400px;
            overflow:auto;
        }
       
    </style>