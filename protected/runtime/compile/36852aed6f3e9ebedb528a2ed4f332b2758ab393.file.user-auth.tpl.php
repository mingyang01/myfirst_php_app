<?php /* Smarty version Smarty-3.1.18, created on 2015-10-26 13:06:52
         compiled from "/home/work/websites/auth/protected/views/auth/user-auth.tpl" */ ?>
<?php /*%%SmartyHeaderCode:868174834562db4ec8e61f1-71250694%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '36852aed6f3e9ebedb528a2ed4f332b2758ab393' => 
    array (
      0 => '/home/work/websites/auth/protected/views/auth/user-auth.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '868174834562db4ec8e61f1-71250694',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'departid' => 0,
    'project' => 0,
    'u' => 0,
    'business' => 0,
    'user' => 0,
    'menuTree' => 0,
    'item' => 0,
    'key' => 0,
    'secondkey' => 0,
    'seconditem' => 0,
    'thirdkey' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_562db4ec9dff98_10058282',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_562db4ec9dff98_10058282')) {function content_562db4ec9dff98_10058282($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2();
    });
</script>
<script>
    //console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['departid']->value);?>
)
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
                            <label for="">用户</label>
                            <input type="text" id="user" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
"/>
                        </div>
                        <div class="form-group">
                            <button class="search-btn btn btn-success">查询</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-default saveAuth-btn">保存</button>
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
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
    				<li>
    					<div class="btn btn-default">
							<input type="checkbox">
							<label for="">
								<i class="glyphicon glyphicon-folder-open">
								</i> <b data-point="<?php echo $_smarty_tpl->tpl_vars['item']->value['itemid'];?>
" class="menutitle"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</b>
							</label>
						</div>
						<ul> 
						<?php if (count($_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'])==0) {?>
						<?php } else { ?>
							<?php  $_smarty_tpl->tpl_vars['seconditem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['seconditem']->_loop = false;
 $_smarty_tpl->tpl_vars['secondkey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['seconditem']->key => $_smarty_tpl->tpl_vars['seconditem']->value) {
$_smarty_tpl->tpl_vars['seconditem']->_loop = true;
 $_smarty_tpl->tpl_vars['secondkey']->value = $_smarty_tpl->tpl_vars['seconditem']->key;
?>
								<?php if (count($_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child'])==0) {?>
									<li>
										<div class="btn btn-default">
											<input type="checkbox" <?php if ($_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['status']=='true') {?>checked="checked"<?php }?>>
											<label for="">
												<i class="glyphicon glyphicon-leaf"></i> <b data-point="<?php echo $_smarty_tpl->tpl_vars['seconditem']->value['itemid'];?>
" class="menutitle"><?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['name'];?>
</b>
											</label>
										</div>
									</li>
								<?php } else { ?>
									<li>
										<div class="btn btn-default">
											<input type="checkbox"  >
											<label for="">
												<i class="glyphicon glyphicon-minus-sign"></i> <b data-point="<?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['itemid'];?>
" class="menutitle"><?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['name'];?>
</b>
											</label>
										</div>
										<ul>
											<?php  $_smarty_tpl->tpl_vars['thirditem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['thirditem']->_loop = false;
 $_smarty_tpl->tpl_vars['thirdkey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['thirditem']->key => $_smarty_tpl->tpl_vars['thirditem']->value) {
$_smarty_tpl->tpl_vars['thirditem']->_loop = true;
 $_smarty_tpl->tpl_vars['thirdkey']->value = $_smarty_tpl->tpl_vars['thirditem']->key;
?>
											<li>
												<div class="btn btn-default">
													<input type="checkbox" <?php if ($_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child'][$_smarty_tpl->tpl_vars['thirdkey']->value]['status']=='true') {?>checked="checked"<?php }?>>
													<label for="">
														<i class="glyphicon glyphicon-leaf"></i> <b data-point="<?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child'][$_smarty_tpl->tpl_vars['thirdkey']->value]['itemid'];?>
" class="menutitle"><?php echo $_smarty_tpl->tpl_vars['menuTree']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['secondkey']->value]['child'][$_smarty_tpl->tpl_vars['thirdkey']->value]['name'];?>
</b>
													</label>
												</div>
											</li>
											<?php } ?>
										</ul>
									</li>
									
								<?php }?>
							<?php } ?>
						<?php }?>
						</ul>
					</li>
					<?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
						该业务没有菜单，请先创建菜单，<a href="http://developer.meiliworks.com//doc/index?name=newfunction" target="_blank">参考文档</a>
					<?php } ?>
    			</ul>
    		</div>
    	</div>
    </div>
</div>
</div>
<script>
	$('.search-btn').click(function(){
        var business = $('#project').val();
        var user = $('#user').val()
        window.location="/authTree/UserAuthShow?business="+business+"&user="+user
    });
    $('#project').change(function(){
        var business = $('#project').val();
        var user = $('#user').val()
        window.location="/authTree/UserAuthShow?business="+business+"&user="+user
    })

    $('.saveAuth-btn').click(function(){
    	var business = $('#project').val();
        var items = [];
        var user = $('#user').val()
        $('.tree li > div > input').each(function() {
            if($(this).attr('checked')=='checked')
            {
                items.push($(this).next().children('b').attr('data-point'));
            }
        });
        $.post('/authTree/AddUserAuth',{'items':items,'user':user, 'business':business},function(data) {
            if(data)
            {
                alert(data)
            }
        },'json');
    })
</script>
<?php }} ?>
