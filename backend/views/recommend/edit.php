<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 3:33 PM
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

$products = \common\modelsext\SiteProductExt::find()
                ->select('id,name')
                ->where([
                    'status' => \common\symbol\BaseSymbol::STATUS_NORMAL
                ])
                ->asArray()
                ->all();
$options = null;
if($products){
    foreach ($products as $key => $value){
        $name = \yii\helpers\Json::decode($value['name']);
        $options[$value['id']] = $name['cn'];
    }
}

if($model->products){
    foreach ($model->products as $key => $value){
        $model->product_ids[] = $value['id'];
    }
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
    <?= $form->field($model, 'link', [
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

    <?= $form->field($model,'bg',[
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
    ])->textInput([
        'class' => 'form-control'
    ]) ?>

    <?= $form->field($model, 'cover', [
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
        'template' => '
                                    {label}
                                    <div class="col-lg-10">
                                    {input}                                                              
                                    {error}
                                    {hint}                                   
                                    </div>
                                    ',
    ])->widget('\common\widgets\images\Images', [
        'type' => 'image'
    ], [
        'class' => 'c-md-12'
    ])->hint('封面图');
    ?>
    <?php if($options):?>
        <?= $form->field($model,'product_ids',[
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
            'options' => [
                'placeholder' => '请选择...',
                'multiple' => true,
            ]
        ])->label('所属项目')?>
    <?php endif;?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?php
            echo Html::submitButton($isNewRecord ? '发布' : '更新', [
                'class' => $isNewRecord ? 'btn btn-danger' : 'btn btn-danger',
                'value' => \common\symbol\BaseSymbol::STATUS_NORMAL,
                'name' => $model->formName() . "[status]"
            ])
            ?>
            <?= Html::submitButton('草稿箱', [
                'class' => $isNewRecord ? 'btn btn-warning' : 'btn btn-danger',
                'value' => \common\symbol\BaseSymbol::STATUS_DRAFT,
                'name' => $model->formName() . "[status]"
            ]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>