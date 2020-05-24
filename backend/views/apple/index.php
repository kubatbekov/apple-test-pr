<?php

namespace backend\views;

use backend\models\Apple;
use backend\models\AppleForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var View $this
 */

$this->title = Yii::t('app', 'Яблоки');
$buttonContent = function (Apple $apple) : string {
    if ($apple->status_id == Apple::STATUS_HANGING) {
        return Html::a(
            Yii::t('app', 'Уронить'),
            ['apple/fell', 'apple_id' => $apple->apple_id],
            ['class' => 'btn btn-success btn-sm']
        );
    }

    if ($apple->status_id == Apple::STATUS_FELL) {
        return Html::a(
            Yii::t('app', 'Есть'),
            '#',
            ['class' => 'btn btn-primary btn-sm', 'onclick' => "return Apple.showModal({$apple->apple_id});"]
        );
    }

    return '';
};

$model = new AppleForm(['scenario' => AppleForm::SCENARIO_EAT]);

?>

 <div class="pull-right">
     <?= Html::a(Yii::t('app', 'Создать яблоки'), ['apple/generate'], ['class' => 'btn btn-success']) ?>
 </div>
<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'rowOptions' => function (Apple $apple) {
        return ['id' => 'apple-id-' . $apple->apple_id];
    },
    'dataProvider' => new ActiveDataProvider([
        'sort' => false,
        'query' => Apple::find()->orderBy(['created_at' => SORT_ASC, 'apple_id' => SORT_ASC]),
        'pagination' => false,
    ]),
    'columns' => [
        [
            'attribute' => 'apple_id',
            'label' => '#',
        ],
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Статус'),
        ],
        [
            'attribute' => 'size',
            'label' => Yii::t('app', 'Остаток'),
            'value' => function (Apple $apple) : string {
                return number_format($apple->size * 100, 1) . '%';
            }
        ],
        [
            'label' => Yii::t('app', 'Цвет'),
            'content' => function (Apple $apple) : string {
                return Html::tag('span', $apple->color, ['style' => 'color: ' .  $apple->color]);
            }
        ],
        [
            'attribute' => 'created_at',
            'label' => Yii::t('app', 'Создано'),
        ],
        [
            'attribute' => 'updated_at',
            'label' => Yii::t('app', 'Упало'),
        ],
        [
            'label' => Yii::t('app', 'Действия'),
            'content' => $buttonContent,
        ]
    ],
]) ?>

<?php
    Modal::begin(['id' => 'appleModal', 'header' => '<h4>Съесть яблоко</h4>']);
    $form = ActiveForm::begin(['action' => ['apple/eat']]);
?>

<?= $form->field($model, 'apple_id')->hiddenInput(['id' => 'appleId'])->label(false) ?>
<?= $form->field($model, 'percent')->textInput()->label(Yii::t('app', 'Процент')) ?>
<div class="form-group">
    <?= Html::button(Yii::t('app', 'Откусить'), ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
</div>
<?php
    ActiveForm::end();
    Modal::end();
?>
