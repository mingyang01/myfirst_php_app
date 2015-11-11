

$('#tt').datagrid({
    url: '/apply/checkup?business='+$('#business').val()+'&funname='+$("#funname").val(),
    fitColumns:true,
    singleSelect:false,
    rownumbers:true,
    columns:[[ 
	{field:'id',title:'id',checkbox:'true',width:100}, 
	{field:'business',title:'项目',width:100},
	{field:'funname',title:'功能',width:100}, 
	{field:'item',title:'功能',hidden:'true',width:150}, 
	{field:'description',title:'预览',align:'center',width:120},
	{field:'developer',title:'开发人员',width:250},
	{field:'type',title:'权限',width:100,formatter:function(index){
		if(index==0) return "普通";
		if(index==1) return "共享";
		if(index==2) return "特殊";
 	}},
	{field:'status',title:'状态',width:100,formatter:function(index){
        if(index==1) return '<button disabled class="btn btn-warning btn-sm">待审核</button>';
        if(index==2) return '<button disabled class="btn btn-primary btn-sm">已审核</button>';
        if(index==0) return '<button disabled class="btn btn-warning btn-sm">未提交审核</button>';
	}},
	//{field:'status',title:'状态',width:100},
	{field:'unix',title:'更新时间',width:120}
	]],
	pagination: true,
    pageList:[10,15]
});
$('#submit').click(function(){
	var type=$("#type").val();
	$('#tt').datagrid({
        url: '/apply/checkup?business='+$('#business').val()+'&funname='+$("#funname").val()+'&content='+$('#searchContent').val()+'&type='+type
	});

	return false;
});

$('#checkshow').click(function(){
	var rows = $('#tt').datagrid('getSelections');
	var html = [];
	for(var i=0;i<rows.length;i++)
	{
	html.push('<tr class="datarow"><td style="display:none;">'+rows[i].id+'</td><td>'
		+rows[i].business+'</td><td>'+rows[i].funname+'</td><td>'
		+rows[i].description+'</td><td class="type">'+getvalue(parseInt(rows[i].type),rows[i].id)+'</td><td class="btnbox">'
		+'<button class="showdepart btn btn-default">'
        +'分配到部门'
		+'</button>'
		+'</td></tr>'+
		'<tr class="displaybox" style="display:none;"><td class="depart" colspan=5>'+
		'<div class=" col-sm-12">'
        +'<select size=10 multiple="multiple" class="listBox departlist form-control">'
        +'</select>'
		+'</div>'
		+'</td></tr>'

		);
	}
	html.join(" ");
	$('#tbody').html(html);
    $('.listBox').bootstrapDualListbox({
		nonSelectedListLabel: '未分配的部门',
		selectedListLabel: '已分配的部门',
		preserveSelectionOnMove: 'moved',
		moveOnSelect: true,
		nonSelectedFilter: ''
	});
	$('.datarow').each(function() {
		var typevalue = $(this).children('.type').children('input:checked').val();
		var self = $(this);

        var index = $(this).children('td:first').html();
        $.get('/apply/getdepart',{'id':index}, function(data) {
			if(data) {
				var html = [];
				for(item in data.select) {
					html.push('<option value='+item+'>'+data.select[item]+'</option>');
				}
				for(item in data.selected) {
					html.push('<option selected="delected" value='+item+'>'+data.selected[item]+'</option>');
				}
				html = html.join(" ")
				$(self).next().children('.depart').children('div').children('select').html(html);
				$(".listBox").bootstrapDualListbox('refresh',true);
			}
			},'json');
			if(typevalue !=0)
			{
				$(this).children('.btnbox').children('.showdepart').hide("fast")
			}
			if(typevalue ==0)
			{
				$(this).children('.btnbox').children('.showdepart').show("fast")
			}
		
	    });
	

    });
$('#tbody').on('click','.showdepart',function(){
	$('.showdepart').each(function() {
		$(this).parent().parent().next().stop().hide('fast');
	});
	$(this).parent().parent().next().stop().show('fast');
});

$('#tbody').on('click','.datarow',function(){
	var typevalue = $(this).children('.type').children('input:checked').val();
    var rows = $('#tt').datagrid('getSelections');
    var index = $(this).children('td:first').html();
	if(typevalue !=0)
	{
		$(this).children('.btnbox').children('.showdepart').attr('disabled','true');
		$(this).next().hide('fast');
	}
	if(typevalue ==0)
	{
		$(this).children('.btnbox').children('.showdepart').removeAttr('disabled');

	}
	var index = $(this).children('td:first').html();
	for(var i=0;i<rows.length;i++)
	{
		if(rows[i].id==index)
		{
			rows[i].type = typevalue;
		}
	}
	
});

$('#tbody').on('change','.listBox',function(){
	var index = $(this).parent().parent().parent().prev().children('td:first').html();
	var rows = $('#tt').datagrid('getSelections');
	var selected = $(this).val();
	for(var i=0;i<rows.length;i++)
	{
		if(rows[i].id==index)
		{
			rows[i].depart = selected;
		}
	}
})

function getvalue(num,flag){
	var inputhtml = "";
	switch(num)
	{
		case 0: inputhtml ='<input name="type'+flag+'" checked="checked" type="radio" value="0">普通 '+
							'<input name="type'+flag+'" type="radio" value="1">共享 '+
							'<input name="type'+flag+'" type="radio" value="2">特殊 ';break;
		case 1: inputhtml ='<input name="type'+flag+'"  type="radio" value="0">普通 '+
							'<input name="type'+flag+'" checked type="radio" value="1">共享 '+
							'<input name="type'+flag+'" type="radio" value="2">特殊 ';break;
		case 2: inputhtml ='<input name="type'+flag+'"  type="radio" value="0">普通 '+
							'<input name="type'+flag+'" type="radio" value="1">共享 '+
							'<input name="type'+flag+'" checked type="radio" value="2">特殊 '; break;
	}
	return inputhtml;
}

function getStatus(num){
  var html = "";
  switch(num)
  {
  	case 1: html = '待审核';break;
  	case 2: html = '已审核';break;
  }
}

$("#savechange").click(function(){
	var rows = $('#tt').datagrid('getSelections');
	console.log(rows)
	$.post('/apply/changecheck',{'rows':rows}, function(data) {
		if (data) {
			$('.checkshow').hide("fast");
            $.messager.show({
                title: 'message',
                msg: data
            });
            setTimeout(function(){
            	window.location.reload();
            },500);
		}
	},'json');

});

$('.btn-box').on('click','.btn-all',function(){
	$('.depart-wramp').show();
	$('.modal-footer').html('<button type="button" id="savechange" class="sb-btn btn btn-success">保存</button><button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>');
	$('.depart-wramp').html('<select id="departList" size=10 multiple="multiple" class="listBox departlist form-control"></select>');
	$('#departList').bootstrapDualListbox({
		nonSelectedListLabel: '未分配的部门',
		selectedListLabel: '已分配的部门',
		preserveSelectionOnMove: 'moved',
		moveOnSelect: true,
		nonSelectedFilter: ''
	});
	$.get('/apply/getdepartAll', function(data) {
		if(data)
		{
			var  html = [];
			for (value in data) {
				html.push('<option value='+value+'>'+data[value]+'</option>');
			};
			html =html.join('');
			$('#departList').html(html);
			$("#departList").bootstrapDualListbox('refresh',true);

		}
	},'json');
})

$('.btn-box').on('click','#cancel-btn',function(){
	$('.depart-wramp').hide();
	$('.btn-box').html('<button class="btn btn-default btn-block btn-all btn-primary">一健分配到部门</button>');
})
$('.modal-footer').on('click','.sb-btn',function(){
	var departList = $('#departList').val();
	var rows = $('#tt').datagrid('getSelections');
	var flag = true;
	for (value in rows) {
		if(rows[value]['type']!=0)
		{
			alert("有不是普通权限的功能，不能进行一键分配");
			flag = false;
		}
	};
	if(!rows)
	{
		flag = false;
	}
	if(flag)
	{
		$.post('/apply/distAll',{rows:rows,departlist:departList},function(data) {
			if(data)
			{
				$('.checkshow').hide("fast");
	            $.messager.show({
	                title: 'message',
	                msg: data
	            });
	            setTimeout(function(){
	            	window.location.reload();
	            },500);
			}
		},'json');
	}
	
})




