<?php /* Smarty version Smarty-3.1.18, created on 2015-10-26 10:37:18
         compiled from "/home/work/websites/auth/protected/views/layouts/navigator.tpl" */ ?>
<?php /*%%SmartyHeaderCode:27951921055da8dada0c0e7-73673545%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6d9659b458cc3fb4fe269b47cf040c3c50941c88' => 
    array (
      0 => '/home/work/websites/auth/protected/views/layouts/navigator.tpl',
      1 => 1445827029,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27951921055da8dada0c0e7-73673545',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55da8dada7d9f2_69052564',
  'variables' => 
  array (
    'speed' => 0,
    'navbox' => 0,
    'key' => 0,
    'u' => 0,
    'm' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55da8dada7d9f2_69052564')) {function content_55da8dada7d9f2_69052564($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['navbox'] = new Smarty_variable(MenuManager::developerMenu($_smarty_tpl->tpl_vars['speed']->value->id,"false"), null, 0);?>
<header class="navbar-inverse" id="top" role="banner" style="margin-bottom:20px;">
    <div class="navbar-header">
        <a class="navbar-brand" href="/" style="color:white">权限管理平台</a>
    </div>

    <nav class="collapse  navbar-collapse" role="navigation">
        <ul class="nav navbar-nav">
        <?php  $_smarty_tpl->tpl_vars['it'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['it']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['navbox']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['it']->key => $_smarty_tpl->tpl_vars['it']->value) {
$_smarty_tpl->tpl_vars['it']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['it']->key;
?>
            <li class="dropdown" >
                <?php if (count($_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'])>0) {?>
                    <a href="#" data-toggle="dropdown"  class="dropdown-toggle"><span class="text"><?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['name'];?>
</span><b class="caret"></b></a>
                <?php } else { ?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['url'];?>
" ><span class="text"><?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['name'];?>
</span></a>
                <?php }?>
                <ul class="dropdown-menu">
                <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['i']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['i']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value) {
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['u']->value = $_smarty_tpl->tpl_vars['i']->key;
 $_smarty_tpl->tpl_vars['i']->iteration++;
 $_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['list']['last'] = $_smarty_tpl->tpl_vars['i']->last;
?>
                    <?php if ($_smarty_tpl->tpl_vars['navbox']->value!='') {?>
                        <?php if (count($_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['child'])>0) {?>
                        <li class="dropdown-submenu"><a title="" href="<?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['url'];?>
"><i class="icon-bar-chart"></i> <?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['name'];?>
</a>
                        <?php } else { ?>
                        <li><a title="" href="<?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['url'];?>
"><i class="icon-bar-chart"></i> <?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['name'];?>
</a>
                        <?php }?>
                        <ul class="dropdown-menu">
                            <?php if (count($_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['child'])>0) {?>
                                <?php  $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['n']->_loop = false;
 $_smarty_tpl->tpl_vars['m'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['n']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['n']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['n']->key => $_smarty_tpl->tpl_vars['n']->value) {
$_smarty_tpl->tpl_vars['n']->_loop = true;
 $_smarty_tpl->tpl_vars['m']->value = $_smarty_tpl->tpl_vars['n']->key;
 $_smarty_tpl->tpl_vars['n']->iteration++;
 $_smarty_tpl->tpl_vars['n']->last = $_smarty_tpl->tpl_vars['n']->iteration === $_smarty_tpl->tpl_vars['n']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['list']['last'] = $_smarty_tpl->tpl_vars['n']->last;
?>
                                    <li><a  href="<?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['child'][$_smarty_tpl->tpl_vars['m']->value]['url'];?>
" ><i class="icon-bar-chart"></i> <?php echo $_smarty_tpl->tpl_vars['navbox']->value[$_smarty_tpl->tpl_vars['key']->value]['child'][$_smarty_tpl->tpl_vars['u']->value]['child'][$_smarty_tpl->tpl_vars['m']->value]['name'];?>
</a></li>
                                    <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['list']['last']) {?>
                                        <?php } else { ?>
                                            <li class="divider"></li>
                                        <?php }?>
                                <?php } ?>
                            <?php }?>
                        </ul>
                        </li>
                        <?php }?>
                        <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['list']['last']) {?>
                        <?php } else { ?>
                        <li class="divider"></li>
                        <?php }?>
                <?php } ?>
                </ul>
            </li>

        <?php } ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text"><?php echo Yii::app()->user->name;?>
</span><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a target="_blank" href="http://speed.meilishuo.com/user/profile/"><i class="icon-user"></i> 个人信息</a></li>
                    <li class="divider"></li>
                    <li><a target="_blank" href="http://speed.meilishuo.com/time/time_manage"><i class="icon-check"></i> 我的日程</a></li>
                    <li class="divider"></li>
                    <li><a href="/site/logout"><i class="icon-key"></i> 退出登录</a></li>
                </ul>
            </li>
            <li class="" data-original-title="" data-content="请加入qq群 305249959 进行问题反馈 !" data-placement="bottom" data-toggle="popover" data-container="body" title="">
				<a href="javascript:void(0);">问题求助</a>
			</li>
            <li class=""><a title="" href="/site/logout"><i class="icon icon-share-alt"></i> <span class="text">退出登录</span></a></li>
        </ul>
    </nav>
</header>
<script>
	$(function () {
        $("[data-toggle='popover']").popover();
	});
</script>

<?php }} ?>
