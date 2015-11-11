<?php /* Smarty version Smarty-3.1.18, created on 2015-10-28 10:36:52
         compiled from "/home/work/websites/auth/protected/views/authority/roleuser.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1799885476563034c427fdc7-36783819%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '303b1e88564fb5b521ffac0980bccf9aa6fdc51b' => 
    array (
      0 => '/home/work/websites/auth/protected/views/authority/roleuser.tpl',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1799885476563034c427fdc7-36783819',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'selected' => 0,
    'whole' => 0,
    'u' => 0,
    'depart' => 0,
    'role' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_563034c430bcf0_54415227',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563034c430bcf0_54415227')) {function content_563034c430bcf0_54415227($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<link rel="stylesheet" href="/assets/css/bootstrap-duallistbox.min.css" />
<script src="/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
<script>
    console.log(<?php echo json_encode($_smarty_tpl->tpl_vars['selected']->value);?>
);
</script>
<div class="container">
    <div class="row" style="padding-top:20px;">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/auth/role">角色管理</a></li>
                <li class="active">角色成员</li>
            </ol>
        </div>
    </div>

    <div>
        <select multiple="multiple" size="10" name="duallistbox" class="listBox" style="display: none;">
            <?php  $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['u']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['whole']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['u']->key;
?>
                <option <?php if (in_array($_smarty_tpl->tpl_vars['u']->value['id'],$_smarty_tpl->tpl_vars['selected']->value)) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</option>
            <?php } ?>
        </select>
    </div>

    <div class="row" style="margin-top:20px">
        <div class="col-md-12">
            <button id="submit" class="col-md-12 btn btn-default">确定</button>
        </div>
    </div>

<script>
    var depart = "<?php echo $_smarty_tpl->tpl_vars['depart']->value;?>
";
    var role = "<?php echo $_smarty_tpl->tpl_vars['role']->value;?>
";

    var listBox = $('.listBox').bootstrapDualListbox({
      nonSelectedListLabel: '未分配的人员',
      selectedListLabel: '已分配的人员',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: true,
      nonSelectedFilter: ''
    });

    $('#submit').click(function(e){
        var selected = $('.listBox').val();
        var url = '/auth/roleUserAdd';
        $.post(url, {"selected": selected, "role": role,"departid":depart}, function(data){
        	alert(data.msg);
            window.location.reload();
        }, 'json')
    });
</script>
<style>
    #bootstrap-duallistbox-selected-list_duallistbox, #bootstrap-duallistbox-nonselected-list_duallistbox {
        height: 300px !important;
    }
</style><?php }} ?>
