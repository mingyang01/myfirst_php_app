{/include file="layouts/header.tpl"/}
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<script>
    //console.log({/json_encode($departid)/})
</script>
<link rel="stylesheet" type="text/css" href="/assets/css/menu-tree.css" />
<script type="text/javascript" src="/assets/js/auth-tree.js">
</script>
</head>
<body>
<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">角色权限</li>
	        </ol>
	    </div>
    </div>
    <div class="row">
		<div class="col-md-12">
			<div id="well" class="well clearfix">
                <div class="col-md-11">
                    <div class="form-inline" role="form" id="form">
                        <div class="form-group">
                            <label for="">平台</label>
                            <select id="project" class="form-control">
                                {/foreach from=$project key=i item=u/}
                                    <option {/if $u['business']==$business/}selected{//if/}  value="{/$u['business']/}">{/$u['cname']/}</option>
                                {//foreach/}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">角色</label>
                            <input disabled="true" id="role" value="{/$role/}"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-default saveAuth-btn"></button>
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
    					<div class="btn btn-default">
							<input type="checkbox" checked="checked">
							<label for="">
								<i class="glyphicon glyphicon-folder-open">
								</i> <b data-point="{/$menuTree[$key].itemid/}" class="menutitle">{/$key/}</b>
							</label>
						</div>
						<ul> 
						{/if count($menuTree[$key].child) eq 0 /}
						{/else/}
							{/foreach from=$menuTree[$key].child item=seconditem key=secondkey name=secondkey/}
								{/if count($menuTree[$key].child[$secondkey].child) eq 0 /}
									<li>
										<div class="btn btn-default">
											<input type="checkbox" checked="checked">
											<label for="">
												<i class="glyphicon glyphicon-leaf"></i> <b data-point="{/$menuTree[$key].child[$secondkey].itemid/}" class="menutitle">{/$menuTree[$key].child[$secondkey].name/}</b>
											</label>
										</div>
									</li>
								{/else/}
									<li>
										<div class="btn btn-default">
											<input type="checkbox" checked="checked" >
											<label for="">
												<i class="glyphicon glyphicon-minus-sign"></i> <b data-point="{/$menuTree[$key].child[$secondkey].itemid/}" class="menutitle">{/$menuTree[$key].child[$secondkey].name/}</b>
											</label>
										</div>
										<ul>
											{/foreach from=$menuTree[$key].child[$secondkey].child item=thirditem key=thirdkey name=thirdkey/}
											<li>
												<div class="btn btn-default">
													<input type="checkbox" checked="checked">
													<label for="">
														<i class="glyphicon glyphicon-leaf"></i> <b data-point="{/$menuTree[$key].child[$secondkey].child[$thirdkey].itemid/}" class="menutitle">{/$menuTree[$key].child[$secondkey].child[$thirdkey].name/}</b>
													</label>
												</div>
											</li>
											{//foreach/}
										</ul>
									</li>
									
								{//if/}
							{//foreach/}
						{//if/}
						</ul>
					</li>
					{/foreachelse/}
						该业务下没有有权限的功能
					{//foreach/}
    			</ul>
    		</div>
    	</div>
    </div>
</div>

<script>

	 var depart = "{/$departid/}";
	var role_pre = "{/$pre/}"
	
	$('#project').change(function(){
        var business = $(this).val();
        var role = $('#role').val();
        window.location="/authTree/Index?business="+business+"&role="+role+'&depart='+depart + "&pre=" + role_pre;
    });
    
    $('.saveAuth-btn').click(function(){
    	var business = $('#project').val();
        var items = [];
        var role = $('#role').val();
        $('.tree li > div > input').each(function() {
            if($(this).attr('checked')=='checked')
            {
                items.push($(this).next().children('b').attr('data-point'));
            }
        });
        console.log(items);
        $.post('/authTree/AddRoleAuth',{'items':items,'role':role, 'business':business},function(data) {
            if(data) {
                alert(data)
            }
        },'json');
    })
</script>
