<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 10:51 AM
 */

use yii\helpers\Html;
use common\dog\Alert;

$this->params['breadcrumbs'][] = $this->title;

?>
    <section class="wrapper site-min-height">
        <?= Alert::widget(); ?>
        <!-- page start-->
        <section class="panel">
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <div class="btn-group">
                            <?= Html::a('新建 <i class="fa fa-plus"></i>', [$createRoute], ['class' => 'btn btn-success', 'style' => 'margin-bottom:15px;']) ?>
                            <?php if($subNav):?>
                                <?php
                                    if(isset($modelClass->formName()['type_id'])){
                                        $typeId = intval(Yii::$app->request->get($modelClass->formName())['type_id']);
                                    }else{
                                        $typeId = 0;
                                    }
                                ?>
                                <?php foreach ($subNav as $key => $value):?>
                                    <?php
                                        $className = $value['id'] == $typeId ? 'btn btn-warning':'btn btn-primary'
                                    ?>
                                    <?php
                                        $name = \yii\helpers\Json::decode($value['name']);
                                    ?>
                                    <?= Html::a($name['cn'] . ' <i class="fa fa-angle-double-right"></i>', ['product/list',$modelClass->formName() => [
                                            'type_id' => $value['id']
                                    ]], ['class' => $className , 'style' => 'margin-bottom:15px;margin-left:10px;']) ?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="space15"></div>
                    <?= $this->render('_search', [
                        'model' => $modelClass,
                        'search' => $search
                    ]) ?>
                    <!--table-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <?= \yii\grid\GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'columns' => $columns
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
    <!-- 定义数据块 -->
<?php $this->beginBlock('hightlightnav'); ?>
    $(function() {
    /* 子导航高亮 */
        hightlightnav('<?= $highlight ?>');
        $('.changeLabelTemplate').click(function(e) {
            e.preventDefault();
            const $modal = $('#product-info-add');
            const id = $(e.target).data('id');
            const url = `/product-info/edit?product_id=${id}`
            $modal.find('form').attr('action',url);
            $modal.find('.jumplist').attr('href',`/product-info/list?id=${id}`);
            $modal.modal('show');
        });
    });
<?php $this->endBlock() ?>
    <!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['hightlightnav'], \yii\web\View::POS_END); ?>