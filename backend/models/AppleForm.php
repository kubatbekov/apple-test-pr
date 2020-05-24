<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Class AppleForm
 */
class AppleForm extends Model
{
    /** @var string Scenario eat */
    public const SCENARIO_EAT = 'eat';

    /** @var float Percent ratio max */
    private const PERCENT_RATIO_MAX = 100.0;

    /** @var float Percent ratio min*/
    private const PERCENT_RATIO_MIN = 0.0;

    /** @var int Min count generate apples */
    private const GENERATE_MIN_COUNT = 2;

    /** @var int Max count generate apples */
    private const GENERATE_MAX_COUNT = 10;

    /**
     * @var int $apple_id
     */
    public $apple_id;

    /**
     * @var float $percent
     */
    public $percent;

    /**
     * @var $apple
     */
    private $apple;

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'apple_id' => Yii::t('app', 'Идентификатор яблока'),
            'percent' => Yii::t('app', 'Процент'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            ['apple_id', 'required'],
            ['apple_id', 'integer'],
            ['apple_id', 'exist', 'targetClass' => Apple::class, 'targetAttribute' => ['apple_id' => 'apple_id']],
            ['percent', 'required'],
            ['percent', 'compare', 'type' => 'number', 'compareValue' => self::PERCENT_RATIO_MIN, 'operator' => '>'],
            ['percent', 'compare', 'type' => 'number', 'compareValue' => self::PERCENT_RATIO_MAX, 'operator' => '<='],
        ];
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['apple_id'],
            self::SCENARIO_EAT => ['apple_id', 'percent'],
        ];
    }

    /**
     * Eat apple
     *
     * @return bool
     * @throws
     */
    public function eatApple() : bool
    {
        $apple = $this->getApple();

        try {
            $apple->eat($this->percent);

            return $apple->save();
        } catch (AppleException $exception) {
            if ($exception->getCode() == AppleException::FULL_CONSUME) {
                return $apple->delete();
            }

            $this->addError('apple_id', $exception->getMessage());
        }

        return false;
    }

    /**
     * Fall to ground
     *
     * @return bool
     */
    public function fallToGround() : bool
    {
        try {
            $apple = $this->getApple();
            $apple->fallToGround();
            $apple->updated_at = date('Y-m-d H:i:s');

            return $apple->save();
        } catch (AppleException $exception) {
            $this->addError('apple_id', $exception->getMessage());
        }

        return false;
    }

    /**
     * Generate new apples
     *
     * @inheritDoc
     * @throws
     */
    public static function generateApples() : void
    {
        $count = rand(self::GENERATE_MIN_COUNT, self::GENERATE_MAX_COUNT);
        $rows = [];

        while(($count--) > 0) {
            $rows[] = (new Apple())->getAttributes();
        }

        if (empty($rows)) {
            return;
        }

        Apple::getDb()
            ->createCommand()
            ->batchInsert(Apple::tableName(), array_keys($rows[0]), $rows)
            ->execute();
    }

    /**
     * Get apple by id
     *
     * @return Apple|null
     */
    private function getApple() : ?Apple
    {
        if ($this->apple === null && !$this->hasErrors('apple_id')) {
            $this->apple = Apple::findOne($this->apple_id);
        }

        return $this->apple;
    }
}
