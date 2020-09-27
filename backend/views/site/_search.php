<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 11:50 AM
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php if($search):?>
    <div class="post-search">
        <?php $form = ActiveForm::begin([
            'action' => ['list'],
            'method' => 'get',
        ]); ?>
        <?php foreach($search as $key => $value):?>
            <?= $form->field($model, $key) ?>
        <?php endforeach;?>
        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('重置',['list'],['class' => 'btn btn-default'])?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php endif;?>