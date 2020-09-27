<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 10:51 AM
 */

use common\dog\Alert;
?>
<section class="wrapper site-min-height">
    <?= Alert::widget();?>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <?= $this->title?>
                </header>
                <?= $this->render($subView, [
                    'model' => $model,
                    'isNewRecord' => $isNewRecord
                ]) ?>
            </section>
        </div>
    </div>
</section>
<!-- 定义数据块 -->
<?php $this->beginBlock('hightlightnav'); ?>
$(function() {
/* 子导航高亮 */
hightlightnav('<?= $highlight?>');
});
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['hightlightnav'], \yii\web\View::POS_END); ?>
