<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/23
 * Time: 9:17 AM
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Json;
use kartik\select2\Select2;

if(!$isNewRecord){
    //初始化数据
    $imgs = Json::decode($model->src);

    $model->pc = $imgs['pc'];
    $model->mobile = $imgs['mobile'];

    if($model->name){
        $names = Json::decode($model->name);
        $model->name_cn = $names['cn'];
        $model->name_en = $names['en'];
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
        'data' => [
            \common\symbol\ScrollSymbol::TYPE_HOME => '首页轮播',
            \common\symbol\ScrollSymbol::TYPE_CONTACT=> '联系我们轮播'
        ],
        'options' => ['placeholder' => '请选择...']
    ])->label('所属分类')?>

    <?= $form->field($model, 'name_cn', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                {hint}
                                </div>
                                ',
    ])->textInput([
        'maxlength' => 128,
        'class' => 'form-control'
    ])->hint('选择首页轮播的时候，请务必填写') ?>

    <?= $form->field($model, 'name_en', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                {hint}
                                </div>
                                ',
    ])->textInput([
        'maxlength' => 128,
        'class' => 'form-control'
    ])->hint('选择首页轮播的时候，请务必填写') ?>

    <?= $form->field($model, 'category_text', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                {hint}
                                </div>
                                ',
    ])->textInput([
        'maxlength' => 128,
        'class' => 'form-control'
    ])->hint('选择首页轮播的时候，请务必填写') ?>

    <?= $form->field($model, 'sort', [
        'labelOptions' => ['class'=>'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                {hint}
                                </div>
                                ',
    ])->input('number',[
        'value' => ($isNewRecord) ? '99' : $model->sort
    ]) ?>

    <header class="panel-heading"><strong>图片设置</strong></header>
    <br>
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
    ])->hint('尺寸');
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
    ])->hint('尺寸');
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