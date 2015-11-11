$(function(){

    function init() {
        $("#business").val(business);
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
        if (action == 'create') {
        	if (business == 'works') {
        		if (!confirm('works平台的菜单是调用work下面的，建议works平台的项目是建在works下面，确认是否操作')){
        			return false;
        		}
        	}
            url = '/menu/add';
        } else if (action == 'update') {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#fm').form('load',row);
                $.get('/menu/GetMessage',{functionid:row.function},function(results) {
                    if(results)
                    {
                        console.log(results.data.id)
                        var functions = results.functions;
                        $('#select2-showfun-container').html(results.data.funname);
                        $('#select2-selectBusiness-container').html(results.data.cname);
                        var html = [];
                        for(var i=0; i<functions.length;i++)
                        {
                            if(results.data.id==functions[i].id)
                            {
                                html.push('<option selected=selected value='+functions[i].id+'>'+functions[i].funname+'</option>');
                            }
                            else
                            {
                                html.push('<option value='+functions[i].id+'>'+functions[i].funname+'</option>');
                            }
                        }
                        html = html.join(' ');
                        $('#showfun').html(html);

                        var business = results.businesses;
                        console.log(business)
                        var html = [];
                        for(var i=0; i<business.length;i++)
                        {
                            if(results.data.business==business[i].business)
                            {
                                html.push('<option selected=selected value='+business[i].business+'>'+business[i].cname+'</option>');
                            }
                            else
                            {
                                html.push('<option value='+business[i].business+'>'+business[i].cname+'</option>');
                            }
                        }
                        html = html.join(' ');
                        $('#selectBusiness').html(html);
                    }
                },'json');
                url = '/menu/updata';
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
                    $('#dlg').modal('hide')
                    $('#dg').datagrid('reload');

                }
            }
        });
    });

    $('#project').change(function(e){
        window.location = "/menu/index?business=" + this.value;
    });

    $("#delete").click(function (e){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','确定要删除吗?',function(r){
                if (r){
                    $.post('/menu/delete',{id:row.id},function(result){
                        if (result){
                                $('#dg').datagrid('reload');
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
});