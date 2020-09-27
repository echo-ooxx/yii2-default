<?php

/* @var $this yii\web\View */

$this->registerCssFile('@web/statics/assets/font-awesome/css/font-awesome.css', ['depends'=>'backend\assets\AppAsset']);
$this->registerCssFile('@web/statics/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css', ['depends'=>'backend\assets\AppAsset']);
$this->registerCssFile('@web/statics/css/owl.carousel.css', ['depends'=>'backend\assets\AppAsset']);

$this->registerJsFile('@web/statics/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js', ['depends'=>'backend\assets\AppAsset']);
$this->registerJsFile('@web/statics/js/owl.carousel.js', ['depends'=>'backend\assets\AppAsset']);
$this->registerJsFile('@web/statics/js/jquery.customSelect.min.js', ['depends'=>'backend\assets\AppAsset']);
$this->registerJsFile('@web/statics/js/respond.min.js', ['depends'=>'backend\assets\AppAsset']);
$this->registerJsFile('@web/statics/js/sparkline-chart.js', ['depends'=>'backend\assets\AppAsset']);
$this->registerJsFile('@web/statics/js/easy-pie-chart.js', ['depends'=>'backend\assets\AppAsset']);
$this->registerJsFile('@web/statics/js/count.js', ['depends'=>'backend\assets\AppAsset']);

$this->registerJs("
      //owl carousel

      $(document).ready(function() {
          $(\"#owl-demo\").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true,
			  autoPlay:true

          });
      });

      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });
");

$this->title = '丹健国际后台';
?>
<section class="wrapper">


    <div class="row">
        <div class="col-lg-12" style="min-height: 90vh;">
            <!--timeline start-->
            <section class="panel">
                <div class="panel-body">
                    管理系统
                </div>
            </section>
            <!--timeline end-->
        </div>
    </div>

</section>
