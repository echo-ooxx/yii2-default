<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/23
 * Time: 4:55 PM
 */


use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

?>
<div class="panel-body">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'options'=>[
            'class'=>'form-horizontal'
        ]
    ]);?>
    <?php if($isNewRecord):?>
        <header class="panel-heading"><strong>通用设置</strong></header>
        <br>
        <?= $form->field($model, 'key_text', [
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

        <?= $form->field($model, 'title_text', [
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

        <?= $form->field($model,'type',[
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
            'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
        ])->widget(Select2::class,[
            'data' => \common\symbol\AdditionalSymbol::$map,
            'options' => ['placeholder' => '请选择...']
        ])->label('所属分类')?>
    <?php else: ?>
        <header class="panel-heading"><strong>通用设置</strong></header>
        <br>
        <?php if($model->type == \common\symbol\AdditionalSymbol::TYPE_IMG):?>
            <?= $form->field($model, 'value_text', [
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
            ])->hint('图片');
            ?>
        <?php elseif ($model->type == \common\symbol\AdditionalSymbol::TYPE_IMGS): ?>
            <?= $form->field($model, 'pc', [
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
            ])->hint('图片');
            ?>
            <?= $form->field($model, 'mobile', [
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
            ])->hint('图片');
            ?>
        <?php else:?>
            <?= $form->field($model, 'value_text', [
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
        <?php endif;?>
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