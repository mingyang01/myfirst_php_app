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
<style type="text/css" media="screen">
    .table_show{margin-top: 10px; height:400px; overflow: auto;}
    .meassage_box{font-size:16px; margin:10px 0px 10px 70px;}
    .row{margin:10px 0px 10px 10px;}
</style>
<div class="container" style="padding:10px 0;">
<ul class="nav nav-tabs"  role="tablist" id="myTab">
  <li role="presentation" ><a href="statistic">功能统计总览</a></li>
  <li role="presentation" ><a href="functiondetail">功能统计详细</a></li>
  <li role="presentation" class="active"><a href="userpv">用户统计总览</a></li>
</ul>
</div>
    <div class="container tab-pane active well" role="tabpanel" id="tableshow">
    <form action="userpv" method="post">
      <div class="col-md-12">
        <div  class="col-sm-3  date datepicker">
          <input name="datefrom"  type="text" value="{/date("Y-m-d")/}"  data-date-format="yyyy-mm-dd">
          <i class="glyphicon glyphicon-calendar add-on"></i>  
        </div>
        <div  class="col-sm-3  input-append date datepicker">
          <input name="dateto" type="text" value="{/date("Y-m-d")/}"  data-date-format="yyyy-mm-dd">
          <i class="glyphicon glyphicon-calendar add-on"></i>   
        </div>

        <div class="col-sm-">
        <button type="submit" class="btn btn-success">提交</button>
      </div>
      </div>
   </div>
    </form>
  </div>
<div class="container table-bordered" style=" margin-bottom:50px;"> 

    <div id="chartshow" style="height:500px;"></div> 
</div> 
    <!-- ECharts单文件引入 -->
    <script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
    <script type="text/javascript">
        var menu = {/json_encode($data)/};
        var datapv={/json_encode($pv)/};
        // 路径配置
        require.config({
            paths: {
                echarts: 'http://echarts.baidu.com/build/dist'
            }
        });
        
        // 使用
        require(
            [
                'echarts',
                'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec) {
                
            var myChart = ec.init(document.getElementById('chartshow'));
            var option = {
                        title : {
                            text: '用户的PV'
                        },
                        tooltip : {
                            trigger: 'axis',
                            
                        },
                        legend: {
                            data:['pv']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataZoom : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        dataZoom : {
                            show : true,
                            realtime: true,
                            start : 0,
                            end : 20
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : true,
                                axisTick: {onGap:false},
                                splitLine: {show:false},
                                data : menu
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                         series : [
                                          
                                            {
                                                name:'PV',
                                                type:'bar',
                                                data:datapv
                                                
                                            }
                                        ]
       
                    };
                    
                // 为echarts对象加载数据 
                myChart.setOption(option); 
            }
        );
        
    
    </script>