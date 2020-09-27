<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/9/22
 * Time: 3:33 PM
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Json;
use kartik\select2\Select2;

$types = \common\modelsext\SiteProductCategoryExt::getTypes();
$temp = null;
if($types){
    foreach ($types as $key => $value){
        $name = Json::decode($value['name']);
        $temp[$value['id']] = $name['cn'];
    }
}

if(!$isNewRecord){
    //初始化数据
    list($model->name_cn,$model->name_en) = array_values(Json::decode($model->name, true));
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

    <?= $form->field($model,'type_id',[
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
        'template' => '
                                {label}
                                <div class="col-lg-10">
                                {input}
                                {error}
                                </div>
                                ',
    ])->widget(Select2::class,[
        'data' => $temp,
        'options' => [
            'placeholder' => '请选择...',
        ]
    ])->label('所属分类')?>

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
    ])->hint('该分类的icon图标');
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