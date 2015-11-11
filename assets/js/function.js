$(function(){
    
    var url, action;
    function init() {
        $("#business").val(business);
        $('#dg').datagrid({
            onLoadSuccess: function (data) {
                if (data.rows.length != 0) {
                    $('#dg').datagrid("selectRow", 0);
                } else {
                    $('#well').find('button').eq(1).hide()
                    $('#well').find('button').eq(2).hide()
                }
            }
        });
    }

    init();
    
    function setTitle(title) {
        $(".modal-title").text(title);
    }

    function setContent(content) {
        $(".modal-body").text(content);
    }

    function updateData(data)
    {
        var rhtml = [];
        for(item in data)
        {
            var action =data[item].action;
            var index = action.indexOf('action/');
            action = action.substr(index+'action/'.length);
            rhtml.push('<div class="col-sm-12" style="margin-bottom:10px;">',
                '<div class="input-group">',
                    '<input class="form-control" id="funname" readonly="readonly" name="funname" value='+action+' required="true">',
                    '<span data-id='+data[item].id+' class="deleteaction input-group-btn"><span class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></span></span>',
            '</div></div>');
        }
        $("#actionbox").html(rhtml.join(" "));
        $(".deleteaction").click(function(){
            var self = $(this);
            $.post('/function/deleteaction',{id:$(this).attr("data-id")}, function(data) {
                if(data)
                {
                    $(self).parent().parent().remove(); 
                }
                
            });
        });
    
    }

    $('button[data-toggle="modal"]').click(function(e){
        action = $(this).data('action');
        title = $(this).data('title');
        setTitle(title);
    });

    $('#dlg').on('show.bs.modal', function (e) {
        if (action == 'create') {
        	if (business == 'work') {
        		if (!confirm('建议works平台的项目是建在works下面，确认是否操作')){
        			return false;
        		}
        	}
        	url = '/function/add';
            $('#fm').form('clear');
            $("#business").val(business);
            
        } else if (action == 'update') {
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#fm').form('load',row);
                url = '/function/updata';
            }
        } else if (action == 'delete'){
            // code
        }
    })
    
    $("#delete").click(function (e){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','确定要删除吗?',function(r){
                if (r){
                    $.post('/function/delete',{id:row.id, point: row.business + '/' + row.funname},function(result){
                        if (result.errno == 1){
                        	alert(result.msg);
                            $('#dg').datagrid('reload'); 
                        } else {
                        	alert('删除失败');
                        }
                    },'json');
                }
            });
        }
    });

    $('#submit').click(function (e){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result.code == "1") {
                	alert(result.msg);
                    $('#dlg').modal('hide');        
                    $('#dg').datagrid('reload'); 
                } else {
                	alert(result.msg);
                }
            }
        });
    });

    $("#additem").click(function(){
        var row = $('#dg').datagrid('getSelected');
        window.location="/item/index?functionid="+row.id;
    });

    

    
    $('#addaction').on('show.bs.modal', function (e) {
        var row = $('#dg').datagrid('getSelected');
        if (action == 'addaction') {
            //var row = $('#dg').datagrid('getSelected');
            url = '/function/addaction';
            $('#actionfm').form('clear');
            $("#businesssub").val(row.business);
            $("#funname").val(row.funname);
            $("#sid").val(row.id);

        } 
        $.post('/function/updateaction',{row:$('#dg').datagrid('getSelected')}, function(data) {
            var data = eval('('+data+')');
            if(data)
            {
                updateData(data);
            }
            
        });
    });

    $('#actionsubmit').click(function(){
        var row = $('#dg').datagrid('getSelected');
        $.post('/function/addaction', {id:row.id,controller:$("#controllersub").val(),action:$("#actionsub").val(),business:$("#businesssub").val()}, function(data) {
            var data = eval('('+data+')');
            if(data)
            {
                updateData(data);
                
            }
        });
    });

    $('.checksubmit').click(function(){
        var row = $('#dg').datagrid('getSelected');
        if(row)
        {
            if(row.status!=0)
            {
                alert("请选中未审核的功能");
            }
            else
            {
                $.get('/function/checksubmit',{id:row.id}, function(data) {
                    if(data)
                    {
                        $.messager.show({
                            title: 'message',
                            msg: data
                        });
                        $('#dg').datagrid('reload'); 
                    }
                },'json');
            }
        }
        
    });

    $('.searchBtn').click(function(){
        $('#dg').datagrid({
            url:'/function/GetFunctions?business='+business+'&funname='+$("#searchContent").val()
        })
    })

});