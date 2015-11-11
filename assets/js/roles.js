$(function(){

    function init() {
        $("#depart").val(depart);
        $('#dg').datagrid({
            onLoadSuccess: function (data) {
                if (data.rows.length != 0)
                    $('#dg').datagrid("selectRow", 0);
            }
        });
    }
    init();

    var url, action;

    function setTitle(title) {
        $(".modal-title").text(title);
    }

    function setContent(content) {
        $(".modal-body").text(content);
    }

    $('button[data-toggle="modal"]').click(function(e){
        action = $(this).data('action');
        setTitle(action);
    });

    $('#dlg').on('show.bs.modal', function (e) {
    	$("#depart_add").val(depart);
        if (action == 'create') {
            url = '/auth/roleAdd';
        } else if (action == 'update') {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#fm').form('load',row);
                url = '/auth/RoleUpdata';
            }
        } else if (action == 'delete'){
            // code
        }
    })

    $("#submit").click(function (e){
    	
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result){
                	alert(result.msg);
                    setTimeout(function (){
                        $('#dlg').modal('hide')
                        $('#dg').datagrid('reload'); 
                        window.location.reload();
                    },
                    500);
                    
                } 
            }
        });
    });

    $("#delete").click(function (e) {
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','确定要删除吗?',function(r){
                if (r){
                    $.post('/auth/roleDelete',{id:row.id},function(result){
                        if (result){
                           $('#dg').datagrid('reload'); 
                             setTimeout(function (){
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
    });

    $('#tool-user-manager').click(function(e){
        var row = $('#dg').datagrid('getSelected');
        if (row) {
        	var role = row['name'];
            window.location = "/auth/roleUser?depart=" + departid + "&role=" + role
        } else {
        	alert('请先创建角色！');
        }
        
    });
    $('#tool-auth-manager').click(function(e){
        var row = $('#dg').datagrid('getSelected');
        if (row) {
        	var role = row['name'];
        	window.location = "/authTree/Index?depart=" + departid + "&role=" + role + "&pre=" + role_pre;
        } else {
        	alert('请先创建角色！');
        }
        
    })
    
    
    $('#depart').change(function(){
    	var departid = $('#depart').val();
    	if (departid) {
    		window.location = "/auth/Role?departid=" + departid;
    	}
    });
});