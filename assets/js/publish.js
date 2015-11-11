var url;

$('#dg').datagrid({
    onLoadSuccess: function (data) {
        if (data.rows.length != 0)
            $('#dg').datagrid("selectRow", 0);
    }
});

$("#add").click(function (e){
    $('#dlg').dialog('open').dialog('setTitle','添加');
    $('#fm').form('clear');
    url = '/publish/addbusiness';
});

$("#update").click(function (e){
    var row = $('#dg').datagrid('getSelected');
    var obj = document.getElementById("businessname");
    obj.setAttribute("readOnly",true);
    if (row.desc[0] == "<") {
        row.desc = $(row.desc)[0].href.replace('http://','')
    };
    if (row){
        $('#dlg').dialog('open').dialog('setTitle','更改');
        $('#fm').form('load',row);
        url = '/publish/updatabusiness';
    }
});

$("#delete").click(function (e){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','删除时业务下的方法和权限都会删除，确定要删除吗?',function(r){
            if (r){
                $.post('/publish/delebusiness',{id:row.id},function(result){
                    if (result){
                    	alert(result);
                        window.location.reload(); // reload the user data
                    } else {
                        alert(result);
                    }
                },'json');
            }
        });
    }
});

function saveUser(){

    $('#fm').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if (result){
            	alert( result);
                window.location.reload();
                
            } 
        }
    });
}


$("#function-manage").click(function (event) {
    var row = $('#dg').datagrid('getSelected');
    if(row) {
        window.location.href = "/function/index?business="+row.name;
    }
});

$("#menu-manage").click(function (event) {
    var row = $('#dg').datagrid('getSelected');
    if(row) {
    	if (row.name == 'works') {
    		row.name = 'work';
    	}
    	if (row.name == 'data平台') {
    		alert("data平台的菜单请到data平台系统进行添加、删除和修改操作！！！");
    		return;
    	}
        window.location.href = "/menu/index?business=" + row.name;
    }
});

$('#white-manage').click(function(){
    var row = $('#dg').datagrid('getSelected');
    if(row) {
        window.location.href = "/whitelist/index?project=" + row.name;
    }
})