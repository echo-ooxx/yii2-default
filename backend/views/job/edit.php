<?php
/**
 * Created by IntelliJ IDEA.
 * User: leezhang
 * Date: 2020/5/25
 * Time: 11:53 AM
 */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;

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
    <header class="panel-heading"><strong>通用设置</strong></header>
    <br>
    <?= $form->field($model, 'content', [
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
        'template' => '
                                        {label}
                                        <div class="col-lg-10">
                                        {input}                                                              
                                        {error}                                   
                                        </div>
                                        ',
    ])->widget('\backend\components\ueditor\UEditor', [
        'clientOptions' => [
            'serverUrl' => Url::to(['site/ueditor']), //确保serverUrl正确指向后端地址
            'lang' => 'zh-cn', //中文为 zh-cn
            'initialFrameWidth' => '100%',
            'initialFrameHeight' => '400',
            //定制菜单，参考http://fex.baidu.com/ueditor/#start-toolbar
            'toolbars' => [
                [
                    'fullscreen', 'source', 'undo', 'redo', '|',
                    'fontsize',
                    'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                    'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                    'forecolor', 'backcolor', '|',
                    'lineheight', '|',
                    'indent', '|',
                ],
                ['preview', 'simpleupload', 'insertimage', 'link', 'emotion', 'map', 'insertvideo', 'insertcode',]
            ]
        ]
    ], ['class' => 'c-md-7']);
    ?>

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