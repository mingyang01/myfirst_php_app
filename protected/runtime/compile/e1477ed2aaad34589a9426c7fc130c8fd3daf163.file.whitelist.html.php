<?php /* Smarty version Smarty-3.1.18, created on 2015-09-15 12:41:48
         compiled from "/home/work/websites/auth/protected/views/doc/whitelist.html" */ ?>
<?php /*%%SmartyHeaderCode:156964810055f7a18cedbda1-23316060%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e1477ed2aaad34589a9426c7fc130c8fd3daf163' => 
    array (
      0 => '/home/work/websites/auth/protected/views/doc/whitelist.html',
      1 => 1440386026,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156964810055f7a18cedbda1-23316060',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55f7a18cf08445_98851286',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f7a18cf08445_98851286')) {function content_55f7a18cf08445_98851286($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("layouts/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div class="container"><h1 id="developer-menu-doc">developer whitelist doc</h1>

<p>为某一功能或者接口设置白名单，既绕过权限过滤系统。</p>
<p><span class="danger">注：即使没有权限的人，也可访问的功能和接口,请确保加入白名单的功能或接口，允许任何人访问。</span></p>

<hr>



<h1>白名单设置说明</h1>



<h2 id="地址">i.将功能添加到置白名单</h2>

<blockquote>
  <p><strong>1).在功能管理页面 <a>http://developer.meiliworks.com/function/index?business=xxx</a> 中添加功能及其action</strong></p>
  <p><strong>2).在白名单管理页面 <a>http://developer.meiliworks.com/whitelist/index?project=xxx</a> 设置对应的namespace,point</strong></p>
</blockquote>

<blockquote class="active">
    <h3>示例</h3>
    <h5>以“权限申请”功能为例，将其添加到白名单</h5>
    <h5>1.添加功能</h5>
    <p>在功能管理页面<a>http://developer.meiliworks.com/function/index?business=xxx</a>添加功能“权限申请”</p>
    <p>如下：</p>
<table>
    <thead>
        <tr>
            <th>项目</th><th>功能</th><th>路径</th><th>签名</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>developer</td><td>权限申请</td><td>/apply/index</td><td>apply/index</td>
        </tr>
    </tbody>
</table>
    <h5>2.添加action</h5>
    <p>在<a href="">http://developer.meiliworks.com/function/index?business=xxx</a>页面中，选择上一步骤中添加的“权限申请“功能</p>
    <p>为其添加action，如下：</p>
    <p>namespace:apply &nbsp; &nbsp; &nbsp;  point:index</p>
    <h5>3.添加白名单</h5>
    <p>在<a href="">http://developer.meiliworks.com/whitelist/index?project=xxx</a>添加功能为“权限申请”的白名单，如下</p>
    <table>
    <thead>
        <tr>
            <th>项目</th><th>namespace</th><th>point</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>developer</td><td>developer</td><td>权限申请</td>
        </tr>
    </tbody>
</table>
</blockquote>



<h2>ii. 为接口设置白名单</h2>
<blockquote>
    <h5>直接在在白名单中添加  <a>http://developer.meiliworks.com/whitelist/index?project=xxx</a></h5>
    <p>以菜单接口为例，菜单接口url为<a href="">http://developer.meiliworks.com/api/menu?business=xxx&user=xxx&token=xxx</a></p>
    <p>则在白名单中，如下添加即可</p>
<table>
    <thead>
        <tr>
            <th>项目</th><th>namespace</th><th>point</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>developer</td><td>api</td><td>menu</td>
        </tr>
    </tbody>
</blockquote><?php }} ?>
