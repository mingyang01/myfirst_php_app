<?php /* Smarty version Smarty-3.1.18, created on 2015-09-23 19:30:43
         compiled from "/home/work/websites/auth/protected/views/menu/menu-tree.tpl" */ ?>
<?php /*%%SmartyHeaderCode:156859298556028d63e25216-90349072%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98c78edc0c55e9cef4e74129a125c0612c6f4510' => 
    array (
      0 => '/home/work/websites/auth/protected/views/menu/menu-tree.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156859298556028d63e25216-90349072',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'menuTree' => 0,
    'project' => 0,
    'u' => 0,
    'business' => 0,
    'key' => 0,
    'secondkey' => 0,
    'thirdkey' => 0,
    'functions' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_56028d63eceb66_63002725',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56028d63eceb66_63002725')) {function content_56028d63eceb66_63002725($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<script>
	console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['menuTree']->value);?>
);
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
	                        <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
	                            <option <?php if ($_smarty_tpl->tpl_vars['u']->value['business']==$_smarty_tpl->tpl_vars['business']->value) {?>selected<?php }?>  value="<?php echo $_smarty_tpl->tpl_vars['u']->value['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['cname'];?>
</option>
	                        <?php } ?>
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
    				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuTree']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['item']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
 $_smarty_tpl->tpl_vars['item']->iteration++;
 $_smarty_tpl->tpl_vars['item']->last = $_smarty_tpl->tpl_vars['item']->iteration === $_smarty_tpl->tpl_vars['item']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['firstkey']['last'] = $_smarty_tpl->tpl_vars['item']->last;
?>
    				<li>
						<span><i class="glyphicon glyphicon-folder-open"></i> <b class="menutitle"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>

						</b></span>
						<ul> 
						<?php if (count($_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'])==0) {?>
						<li style="width:150px;">
	                    	<button class="add-secondmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
	                	</li>
						<?php } else { ?>
							<?php  $_smarty_tpl->tpl_vars['seconditem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['seconditem']->_loop = false;
 $_smarty_tpl->tpl_vars['secondkey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['seconditem']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['seconditem']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['seconditem']->key => $_smarty_tpl->tpl_vars['seconditem']->value) {
$_smarty_tpl->tpl_vars['seconditem']->_loop = true;
 $_smarty_tpl->tpl_vars['secondkey']->value = $_smarty_tpl->tpl_vars['seconditem']->key;
 $_smarty_tpl->tpl_vars['seconditem']->iteration++;
 $_smarty_tpl->tpl_vars['seconditem']->last = $_smarty_tpl->tpl_vars['seconditem']->iteration === $_smarty_tpl->tpl_vars['seconditem']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['secondkey']['last'] = $_smarty_tpl->tpl_vars['seconditem']->last;
?>
								<?php if (count($_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child'])==0) {?>
									<li>
										<span><i class="glyphicon glyphicon-minus-sign"></i><b class="menutitle"><?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['name'];?>
</b></span>
										<ul>
											<li style="width:150px;">
						                    	<button class="add-thirdmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
						                	</li>
										</ul>
									</li>
								<?php } else { ?>
									<li>
										<span><i class="glyphicon glyphicon-minus-sign"></i><b class="menutitle"><?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['name'];?>
</b></span>
										<ul>
											<?php  $_smarty_tpl->tpl_vars['thirditem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['thirditem']->_loop = false;
 $_smarty_tpl->tpl_vars['thirdkey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['thirditem']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['thirditem']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['thirditem']->key => $_smarty_tpl->tpl_vars['thirditem']->value) {
$_smarty_tpl->tpl_vars['thirditem']->_loop = true;
 $_smarty_tpl->tpl_vars['thirdkey']->value = $_smarty_tpl->tpl_vars['thirditem']->key;
 $_smarty_tpl->tpl_vars['thirditem']->iteration++;
 $_smarty_tpl->tpl_vars['thirditem']->last = $_smarty_tpl->tpl_vars['thirditem']->iteration === $_smarty_tpl->tpl_vars['thirditem']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['thirdkey']['last'] = $_smarty_tpl->tpl_vars['thirditem']->last;
?>
											<li>
												<span><i class="glyphicon glyphicon-leaf"></i><?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child'][$_smarty_tpl->tpl_vars['thirdkey']->value]['name'];?>
</span>
											</li>
											<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['thirdkey']['last']) {?>
											<li style="width:150px;">
						                    	<button class="add-thirdmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
						                	</li>
						                    <?php }?>
											<?php } ?>
										</ul>
									</li>
									
								<?php }?>
								<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['secondkey']['last']) {?>
								<li style="width:150px;">
			                    	<button class="add-secondmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
			                	</li>
			                    <?php }?>
							<?php } ?>
						<?php }?>
						</ul>
					</li>
					<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['firstkey']['last']) {?>
					<li style="width:150px;">
                    	<button class="add-firstmenu btn btn-default btn-block" data-toggle="modal" data-target="#dlg" style="padding:4px;"><i class="glyphicon glyphicon-plus-sign"></i></button>
                	</li>
                    <?php }?>
					<?php } ?>
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
                            <input id="business" required="true" name="business" type="text" class="form-control" readOnly value="<?php echo $_smarty_tpl->tpl_vars['business']->value;?>
">
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
                                <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['project']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                                <option <?php if ($_smarty_tpl->tpl_vars['u']->value['business']==$_smarty_tpl->tpl_vars['business']->value) {?>selected<?php }?>  value="<?php echo $_smarty_tpl->tpl_vars['u']->value['business'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['cname'];?>
</option>
                            <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">功能绑定:</label>
                            <select id="showfun" name="function" class="js-example-basic-single form-control" style="width:100%;">
                                <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['functions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                                <option value="<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</option>
                                <?php } ?>
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
<?php }} ?>
