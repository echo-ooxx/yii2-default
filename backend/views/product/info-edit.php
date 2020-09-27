<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/26
 * Time: 3:40 PM
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

$products = \common\modelsext\SiteProductExt::find()
    ->where([
        'status' => \common\symbol\BaseSymbol::STATUS_NORMAL,
    ])
    ->orderBy('sort desc,created_at desc')
    ->asArray()
    ->all();
$options = [];
foreach ($products as $key => $product){
    $name = \yii\helpers\Json::decode($product['name']);
    $options[$product['id']] = $name['cn'];
}

?>
<div class="panel-body">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'options'=>[
            'class'=>'form-horizontal'
        ]
    ]);?>
    <header class="panel-heading"><strong>通用设置</strong></header>
    <br>

    <?= $form->field($model,'product_id',[
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
    ])->widget(Select2::class,[
        'data' => $options,
        'options' => ['placeholder' => '请选择...']
    ])->label('关联项目')?>

    <?= $form->field($model, 'title', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
    ])->textInput([
        'maxlength' => 128,
        'class' => 'form-control'
    ]) ?>

    <?= $form->field($model, 'content', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
    ])->textarea([
        'class' => 'form-control'
    ]) ?>

    <?= $form->field($model, 'sort', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
    ])->input('number',[
        'value' => ($isNewRecord) ? '99' : $model->sort
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?php
            echo Html::submitButton($isNewRecord ? '发布' : '更新', [
                'class' => $isNewRecord ? 'btn btn-danger' : 'btn btn-danger',
                'value' => \common\symbol\BaseSymbol::STATUS_NORMAL,
                'name' => $model->formName() . "[status]"
            ])
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>