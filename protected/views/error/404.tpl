{/include file="layouts/header.tpl"/}
        <div class="container">
            <div class="header" style="margin-top:100px"></div>
            <div class="row">
                <div class="span12" style="text-align:center">
            <h1>{/if $message/}{/$message/}{/else/}您访问的页面正在保养中!{//if/}</h1>   
                </div>
            </div>
            <div class="row">
                <div class="span12" style="text-align:center">
                <img src="/assets/images/404.jpg" alt="" />
            </div>
            </div>
        </div>
    </body>
</html>