{/include file="layouts/header.tpl"/}
<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
		$(".js-example-basic-single").select2({placeholder: "请选择部门"});
	});
 
</script>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">后台管理</li>
	        </ol>
	        <div class="well clearfix">
				<div class="col-sm-5">
					<div class="form-group">
						<label class="col-sm-2 control-label" style="padding-left:0px; paddding-right:0px;"><h4>部门:</h4></label>
						<div class="col-sm-10" style="padding-left:0px; padding-right:0px;">
							<select id="depart" class="js-example-basic-single col-sm-12">
								<option value="" selected=selected></option>
								 {/foreach from=$depart item=item key=key/}
								 		<optgroup label="{/$key/}">
									 		{/foreach from=$depart.$key item=it key=val/}
									 			<option value="{/$depart.$key.$val.id/}">{/$depart.$key.$val.name/}</option>
									 		{//foreach/}
								 		</optgroup>
								 {//foreach/}
							</select>
						</div>
					</div>
					<select name="rolelist" id="rolelist" size="26" id="" class="form-control"></select>
				</div>
				<div class="col-sm-7">
					<div class="col-sm-12">
						<select name="actionlist" multiple="multiple" size="19" id="actionlist" class="change_list"></select>
					</div>
					<div class="col-sm-12" style="padding-left:0px; margin-top:10px;">
						<button id="submit" class="btn btn-default btn-block">确定</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
</script>
<script>
var listBox = $('#actionlist').bootstrapDualListbox({
	nonSelectedListLabel: '未分配的功能',
	selectedListLabel: '已分配的功能',
	preserveSelectionOnMove: 'moved',
	moveOnSelect: true,
	nonSelectedFilter: ''
});
$('#depart').change(function(){
	$.get('/apply/getRoleList',{departid:$('#depart').val()},function(data){
		if(data)
		{
			var html = [];
			for(var i=0;i<data.length;i++)
			{
				html.push('<option value='+data[i]['id']+'>'+data[i]['name']+'</option>');
			}
			html = html.join(" ");
			$("#rolelist").html(html);
		}
	},'json');
});
$('#rolelist').change(function(){
	$.get('/apply/getActionList',{roleid:$('#rolelist').val()},function(data){
		if(data)
		{
			console.log(data);
			var html = [];
			$("#actionlist").html("");
			for(var i=0;i<data['selected'].length;i++)
			{
				html.push('<option selected="selected" value="'+data['selected'][i]+'">'+data['selected'][i]+'</option>');
			}
			for(var i=0;i<data['select'].length;i++)
			{
				html.push('<option value="'+data['select'][i]+'">'+data['select'][i]+'</option>');
			}
			html.join(" ");
			$("#actionlist").html(html);
			$('#actionlist').bootstrapDualListbox('refresh',true);
		}
	},'json')
});
$("#submit").click(function(){
	var actionlist=$('#actionlist').val();
	var roleid=$('#rolelist').val();
	$.post('/apply/updateaction',{'roleid':roleid,'actionlist':actionlist},function(data) {
		if(data)
		{
			alert(data)
		}
	},'json');
});

</script>

