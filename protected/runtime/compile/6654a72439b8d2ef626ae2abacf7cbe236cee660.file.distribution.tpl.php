<?php /* Smarty version Smarty-3.1.18, created on 2015-10-26 10:39:28
         compiled from "/home/work/websites/auth/protected/views/authority/distribution.tpl" */ ?>
<?php /*%%SmartyHeaderCode:868396724562d9260c6cb78-45803711%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6654a72439b8d2ef626ae2abacf7cbe236cee660' => 
    array (
      0 => '/home/work/websites/auth/protected/views/authority/distribution.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '868396724562d9260c6cb78-45803711',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'depart' => 0,
    'key' => 0,
    'val' => 0,
    'data' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_562d9260d23a18_05520873',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_562d9260d23a18_05520873')) {function content_562d9260d23a18_05520873($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<link href="/assets/css/select2.min.css" rel="stylesheet" />
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".js-example-basic-single").select2({ placeholder:"请选择部门"});
    });
</script>
<div class="container">
  <div class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#user" role="tab" data-toggle="tab">用户分配</a></li>
        <li role="presentation"><a href="#role" role="tab" data-toggle="tab">角色分配</a></li>
    </ul>

      <!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane  active well clearfix" style="margin:10px 0 10px 0;" id="user">
        <div class="col-md-12">
            <h4>给用户分配角色</h4>
            </div>
            <div class="col-md-6">
                <div class="col-sm-10" style="padding:0px;">
                    <div class="col-sm-6" style="padding:0px;">
                        <input class="form-control" id="username" name="username" placehodder="用户名"/>
                    </div>
                    <div class="col-sm-4">
                        <button id="btnsub" class="btn btn-success">查询</button>
                    </div>
                </div>
            <div class="col-sm-10" style="padding:0px;margin:10px 0 0 0;">
                <div id="usermsg" class="list-group">
                    <a href="#" class="list-group-item disabled">
                      用户信息
                    </a>
                    <a href="#" class="list-group-item">用&nbsp;&nbsp;户：</a>
                    <a href="#" class="list-group-item">邮&nbsp;&nbsp;箱：</a>
                    <a href="#" class="list-group-item ">
                    <h4 class="list-group-item-heading">头像</h4>
                    <p style="text-align:center" class="list-group-item-text">
                        <img height=200 width=200/>
                    </p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="userform">
                <input id="meusername" style="display:none;" name="meusername"/>
                <select id="userselect" multiple="multiple" size="17" name="userrole[]">
                </select>
                <br>
                <button id="usersubmit" class="btn btn-success">提交</button>
            </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane well  clearfix" style="margin:10px 0 10px 0;" id="role">
      <h4>给角色分配任务</h4>
        <div class="col-md-6" style="padding-left:0px;" >
            <select name="" id="depart" class="js-example-basic-single" style="width:100%;">
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['depart']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                    <optgroup label="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
                        <?php  $_smarty_tpl->tpl_vars['it'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['it']->_loop = false;
 $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['it']->key => $_smarty_tpl->tpl_vars['it']->value) {
$_smarty_tpl->tpl_vars['it']->_loop = true;
 $_smarty_tpl->tpl_vars['val']->value = $_smarty_tpl->tpl_vars['it']->key;
?>
                            <option title="<?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value][$_smarty_tpl->tpl_vars['val']->value]['id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value][$_smarty_tpl->tpl_vars['val']->value]['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['depart']->value[$_smarty_tpl->tpl_vars['key']->value][$_smarty_tpl->tpl_vars['val']->value]['name'];?>
</option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
            <select name="rolelist" size="20"  class="form-control" id="rolelist" style="margin-top:10px;">
                <?php if ($_smarty_tpl->tpl_vars['data']->value!='') {?>
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
                        <option title="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
                    <?php } ?>
                <?php }?>
            </select>
        </div>
        <div class="col-md-6">
            <select id="tasklist"  multiple="multiple" style = "height:234px;" size="20" name="duallistbox_demo1[]">
            </select>
            <br>
            <button id="tasksubmit" class="btn btn-success">提交</button>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script src="/assets/js/distribution.js"></script>
<style>
    #bootstrap-duallistbox-selected-list_userrole[]{
        margin-left: -1px;
    }
</style><?php }} ?>
