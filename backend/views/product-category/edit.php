<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 4:31 PM
 */
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Json;

if(!$isNewRecord){
    //初始化数据
    list($model->name_cn,$model->name_en) = array_values(Json::decode($model->name, true));
    list($model->icon_pc,$model->icon_mobile) = array_values(Json::decode($model->icon, true));
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

    <header class="panel-heading"><strong>中文设置</strong></header>
    <br>
    <?= $form->field($model, 'name_cn', [
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

    <header class="panel-heading"><strong>英文设置</strong></header>
    <br>
    <?= $form->field($model, 'name_en', [
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

    <header class="panel-heading"><strong>通用设置</strong></header>
    <br>
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
    <header class="panel-heading"><strong>图片设置</strong></header>
    <br>

    <?= $form->field($model, 'icon_pc', [
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
    ])->hint('该分类的主机端icon图标');
    ?>

    <?= $form->field($model, 'icon_mobile', [
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
    ])->hint('该分类的移动端icon图标');
    ?>

    <?= $form->field($model, 'src', [
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
    ])->hint('该分类的展示背景图，用于主机端');
    ?>




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