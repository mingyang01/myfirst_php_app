{/include file="layouts/header.tpl"/}
<div class="container">
	<div class="row">
	    <div class="col-md-12">
	        <ol class="breadcrumb">
	            <li><a href="/">Home</a></li>
	            <li class="active">更新菜单</li>
	        </ol>
	        <div id="well" class="well clearfix">
	            <div class="col-sm-3">
	                <button class="btn btn-default">更新菜单</button>
	            </div>
	        </div>
	        <div class="alert alert-warning" role="alert">
				<span>点击”更新菜单按钮“进行更新。</span>
        	</div>
        	<div class="progressShow" style="display:none">
		        <div class="alert alert-success" role="alert">
					<span>正在更新，请稍等。</span>
	        	</div>
		        <div class="progress">
					<div id="progressbar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					<span>0%</span>
					</div>
				</div>
			</div>
	    </div>
	</div>
</div>
<script>
var uid = {/yii::app()->user->id/};
$(function(){
	var timer =null;
	$('.btn').click(function(){
		var i=0
		if(timer!=null){

			return;
		}
		$('.alert-warning').hide();
		$('.progressShow').show();

		var cWith = parseInt(Math.random()*10+20);
		
		timer=setInterval(function(){
			i=i+1;
			if(i<=100)
			{
				$('#progressbar').css('width',cWith+i+'%');
				$('.progress span').html(cWith+i+'%');
			}
			else
			{
				clearTimeout(timer);
			}
		},500)

		$.get('/apply/AjaxUpdateMenu',{'uid':uid}, function(data) {
		if(data)
		{
			clearTimeout(timer);
			$('#progressbar').animate({'width':'100%'},6000,function(){
				$('.progress span').html('100%');

			})
			
		}
	},'json');
	})


})
	
</script>
