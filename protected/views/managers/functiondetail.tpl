{/include file="layouts/navigator.tpl"/}
<script>
  $(function(){
        $('.datepicker').datepicker({
          format: "yyyy-mm-dd",
          autoclose: true
        }).on('changeDate', function(ev){
        $(this).datepicker('hide');
      });
        
      });

</script>


<!-- <script src="/js/shopManage.js"></script>  -->
    <style type="text/css" media="screen">
        .table_show{margin-top: 10px; height:400px; overflow: auto;}
        .meassage_box{font-size:16px; margin:10px 0px 10px 70px;}
        .row{margin:10px 0px 10px 10px;}
     </style>
<div class="container" style="padding:10px 0;">
<ul class="nav nav-tabs"  role="tablist" id="myTab">
 <li role="presentation" ><a href="statistic">功能统计总览</a></li>
  <li role="presentation" class="active"><a href="functiondetail">功能统计详细</a></li>
  <li role="presentation"><a href="userpv">用户统计总览</a></li>
</ul>
</div>
    <div id="test" class="container tab-pane active controls well" role="tabpanel" id="tableshow">
    <form action="staall" method="post">
      <div class="col-md-12">
        <div  class="col-sm-3  date datepicker">
          <input id="datefrom" name="datefrom"  type="text" value="{/date("Y-m-d")/}"  data-date-format="yyyy-mm-dd">
          <i class="glyphicon glyphicon-calendar add-on"></i> 
        </div>
        <div  class="col-sm-3  input-append date datepicker">
          <input id="dateto" name="dateto" type="text" value="{/date("Y-m-d")/}"  data-date-format="yyyy-mm-dd">
          <i class="glyphicon glyphicon-calendar add-on"></i>   
        </div>
      </div>

      <div class="col-md-12" style="padding-top:10px; padding-left:4px;">
        <div class="col-sm-3">
          <input type="text"   placeholder="Enter username" name="username"/>
        </div> 
        <div class="col-sm-3">
          <input type="text"  placeholder="Enter Action" name="action"/>
        </div>
        <div class="col-sm-3">
        <button type="submit" class="btn btn-success">提交</button>
      </div>
      </div>
    </form>
  </div>
<div class="container table-bordered "> 


<div class="tab-content " style="padding-top:10px; margin-bottom:50px;" >
  <div role="tabpanel" class="tab-pane active" id="home">
    <div class="container table_show"> 
    <div>
      <table  class="table table-bordered table-hover" style="width:800px;">
          <thead>
              <tr class="active">                    
                  <th >用户名</a></th>
                  <th >功能</a></th>
                  <th >访问的时间</a></th>
              </tr>            
          </thead>
        {/foreach from=$data key=u item=i/}
        <tr>
          <td>  
               {/$data[$u].username/}
          </td>
          <td>
               {/$data[$u].action/}
          </td>
          <td>
               {/$data[$u].time/}
          </td>
        </tr>
        {//foreach/}
        </table>
    </div>
  </div>
  </div>
</div>
<script src="/js/focus_format.js"></script>
