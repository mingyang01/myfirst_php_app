{/include file="layouts/header.tpl"/}
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<script>
	console.log({/json_encode($menuTree)/});
</script>
<link rel="stylesheet" type="text/css" href="/assets/css/menu-tree.css" />
<script type="text/javascript">
$(function(){
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');
        }
        e.stopPropagation();
    });
});
</script>
</head>
<body>
<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li><a href="/publish/index">项目发布</a></li>
	            <li class="active">树形菜单</li>
	        </ol>
	    </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<div id="well" class="well">
	            <div class="form-inline" role="form" id="form">
	                <div class="form-group">
	                    <select id="project" class="form-control">
	                        {/foreach from=$project key=i item=u/}
	                            <option {/if $u['business']==$business/}selected{//if/}  value="{/$u['business']/}">{/$u['cname']/}</option>
	                        {//foreach/}
	                    </select>
	                </div>
	            </div>
	        </div>
		</div>
	</div>
    <div class="row">
    	<div class="col-md-12">
    		<div class="tree well clearfix">
    			<ul class="fisrtmenu-wrap">
    				{/foreach from=$menuTree item=item key=key name=firstkey/}
    				<li>
						<span><i class="glyphicon glyphicon-folder-open"></i> <b class="menutitle">{/$key/}
						</b></span>
						<ul> 
						{/if count($menuTree[$key].child) eq 0 /}
						<li style="width:150px;">
	                    	<button class="add-secondmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
	                	</li>
						{/else/}
							{/foreach from=$menuTree[$key].child item=seconditem key=secondkey name=secondkey/}
								{/if count($menuTree[$key].child[$secondkey].child) eq 0 /}
									<li>
										<span><i class="glyphicon glyphicon-minus-sign"></i><b class="menutitle">{/$menuTree[$key].child[$secondkey].name/}</b></span>
										<ul>
											<li style="width:150px;">
						                    	<button class="add-thirdmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
						                	</li>
										</ul>
									</li>
								{/else/}
									<li>
										<span><i class="glyphicon glyphicon-minus-sign"></i><b class="menutitle">{/$menuTree[$key].child[$secondkey].name/}</b></span>
										<ul>
											{/foreach from=$menuTree[$key].child[$secondkey].child item=thirditem key=thirdkey name=thirdkey/}
											<li>
												<span><i class="glyphicon glyphicon-leaf"></i>{/$menuTree[$key].child[$secondkey].child[$thirdkey].name/}</span>
											</li>
											{/if $smarty.foreach.thirdkey.last/}
											<li style="width:150px;">
						                    	<button class="add-thirdmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
						                	</li>
						                    {//if/}
											{//foreach/}
										</ul>
									</li>
									
								{//if/}
								{/if $smarty.foreach.secondkey.last/}
								<li style="width:150px;">
			                    	<button class="add-secondmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
			                	</li>
			                    {//if/}
							{//foreach/}
						{//if/}
						</ul>
					</li>
					{/if $smarty.foreach.firstkey.last/}
					<li style="width:150px;">
                    	<button class="add-firstmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
                	</li>
                    {//if/}
					{//foreach/}
    			</ul>
    		</div>
    	</div>
    </div>
</div>
<div id="dlg" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">菜单添加</h4>
                </div>
                <div class="modal-body">
                    <form id="fm" method="post">
                        <div class="form-group" style="display:none;">
                            <label for="exampleInputEmail1">id:</label>
                            <input required="true" name="id" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">项目:</label>
                            <input id="business" required="true" name="business" type="text" class="form-control" readOnly value="{/$business/}">
                        </div>

                        <div class="form-group first-group">
                            <label for="exampleInputEmail1">一级菜单:</label>
                            <input required="true" id="first-input" name="first" type="text" class="form-control">
                        </div>

                        <div class="form-group second-group">
                            <label for="exampleInputEmail1">二级菜单:</label>
                            <input name="second" id="second-input" type="text" class="form-control">
                        </div>

                        <div class="form-group third-group">
                            <label for="exampleInputEmail1">三级菜单</label>
                            <input name="third" id="third-input" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">项目绑定:</label>
                            <select id="selectBusiness" name="businesses" class="js-example-basic-single form-control" style="width:100%;">
                                {/foreach from=$project key=i item=u/}
                                <option {/if $u['business']==$business/}selected{//if/}  value="{/$u['business']/}">{/$u['cname']/}</option>
                            {//foreach/}
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">功能绑定:</label>
                            <select id="showfun" name="function" class="js-example-basic-single form-control" style="width:100%;">
                                {/foreach from=$functions key=i item=u/}
                                <option value="{/$u.id/}">{/$u.name/}</option>
                                {//foreach/}
                            </select>
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
</div>
<script>
	var htmlnull = null;
	$('.add-firstmenu').click(function(){
		$('.first-group').children('input').removeAttr('readOnly');
		$('.second-group').hide()
		$('.third-group').hide()
		$('#first-input').val(htmlnull);
		$('#second-input').val(htmlnull);
		$('#third-input').val(htmlnull);
	})
	$('.add-secondmenu').click(function(){
		$('.third-group').hide();
		$('.second-group').show()
		$('.first-group').children('input').attr('readOnly', 'readOnly');
		$('#second-input').val(htmlnull);
		$('#third-input').val(htmlnull);
		$('.second-group').children('input').removeAttr('readOnly');
		var menuTile = $(this).parent().parent().parent().children('span').children('.menutitle').html()
		$('#first-input').val(menuTile.trim());
	})
	$('.add-thirdmenu').click(function(){
		$('.third-group').show();
		$('.second-group').show();
		$('.first-group').children('input').attr('readOnly', 'readOnly');
		var firstTile = $(this).parent().parent().parent().parent().parent().children('span').children('.menutitle').html()
		var secondTile = $(this).parent().parent().parent().children('span').children('.menutitle').html()
		$('#first-input').val(firstTile.trim());
		$('#second-input').val(secondTile.trim());
		$('.second-group').children('input').attr('readOnly', 'readOnly');
	})
	$("#submit").click(function (e){

        $('#fm').form('submit',{
            url: '/menuTree/addmenutree',
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result){
                	if(result.flag)
                	{
                		$.messager.show({
	                        title: 'message',
	                        msg: result.msg
	                    });
	                    $('#dlg').modal('hide')
	                    window.location.reload();
                	}
                	else
                	{
                		$.messager.show({
	                        title: 'message',
	                        msg: result.msg
	                    });
	                    $('#dlg').modal('hide')
	                    //window.location.reload();
                	}
                    

                }
            }
        });
    });

    $('#project').change(function(){
    	window.location='/menuTree/index?business='+$(this).val();
    })
</script>
