<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\widgets\AdvancedSearch;
use hiqdev\combo\StaticCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>

<?php $form = AdvancedSearch::begin(compact('model')) ?>

    <div class="col-md-4">
        <?= $form->field('client_like') ?>
        <?= $form->field('name') ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('seller_like') ?>
        <?= $form->field('seller_id')->widget(ClientCombo::classname()) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field('state')->widget(StaticCombo::classname(), [
            'data' => $state_data,
            'hasId' => true,
            'pluginOptions' => [
                'select2Options' => [
                    'multiple' => true,
                ]
            ],
        ]) ?>
        <div class="form-group">
            <?= Html::tag('label', 'Registered range', ['class' => 'control-label']); ?>
            <?= DatePicker::widget([
                'model'         => $model,
                'type'          => DatePicker::TYPE_RANGE,
                'attribute'     => 'created_from',
                'attribute2'    => 'created_till',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format'    => 'dd-mm-yyyy'
                ]
            ]) ?>
        </div>
    </div>

<?php $form::end() ?>
