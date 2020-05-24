<?php

namespace backend\models;

use backend\behaviors\EventCallbackBehavior;
use yii\behaviors\AttributesBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "apple"
 *
 * @property-read $apple_id
 * @property-read $status_id
 * @property string $color
 * @property float $size
 * @property int $created_at
 * @property string $updated_at
 * @property-read string $status
 */
class Apple extends ActiveRecord
{
    /** @var int Hanging on a tree */
    public const STATUS_HANGING = 1;

    /** @var int Fell to the ground */
    public const STATUS_FELL = 2;

    /** @var int Rotted */
    public const STATUS_ROTTED = 3;

    /** @var string[] Colors list */
    private const COLORS = ['red', 'yellow', 'green'];

    /** @var int Freshness time */
    private const FRESHNESS_TIME = 18000;

    /** @var float Min size */
    private const MINIMAL_SIZE = 0.0;

    /** @var float Max size */
    private const MAXIMAL_SIZE = 1.0;

    /** @var float Persent */
    private const PERCENT_RATIO = 100.0;

    /** @var bool Status updated table */
    private static $statusUpdated = false;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'attributes' => [
                'class' => AttributesBehavior::class,
                'attributes' => [
                    'size' => [
                        self::EVENT_INIT => self::MAXIMAL_SIZE,
                    ],
                    'status_id' => [
                        self::EVENT_INIT => self::STATUS_HANGING,
                    ],
                    'color' => [
                        self::EVENT_INIT => [$this, 'generateColor'],
                    ],
                    'created_at' => [
                        self::EVENT_INIT => date('Y-m-d H:i:s', self::getRandomUnixTime()),
                    ],
                ],
            ],
            'callback' => [
                'class' => EventCallbackBehavior::class,
                'events' => [
                    self::EVENT_INIT => [self::class, 'updateStatus'],
                ]
            ],
        ];
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return self::getStatusList()[$this->status_id];
    }

    /**
     * Eat apple
     */
    public function eat(float $percent): void
    {
        if ($this->status_id != self::STATUS_FELL) {
            throw new AppleException(AppleException::NOT_ON_GROUND);
        }

        if ($this->size * self::PERCENT_RATIO < $percent) {
            throw new AppleException(AppleException::INVALID_PERCENT);
        }

        $this->size -= ($percent / self::PERCENT_RATIO);

        if ($this->size <= self::MINIMAL_SIZE) {
            throw new AppleException(AppleException::FULL_CONSUME); // Далее нужно удалить текущую запись
        }
    }

    /**
     * Fall to ground apple
     */
    public function fallToGround(): void
    {
        if ($this->status_id != self::STATUS_HANGING) {
            throw new AppleException(AppleException::DOES_NOT_HANG);
        }

        $this->status_id = self::STATUS_FELL;
    }

    /**
     * Generate random color
     */
    public function generateColor(): string
    {
        $index = array_rand(self::COLORS);

        return self::COLORS[$index];
    }

    /**
     * Update apple status
     */
    public static function updateStatus(): void
    {
        if (self::$statusUpdated) {
            return;
        }

        $columns = ['status_id' => self::STATUS_ROTTED];
        $condition = [
            'and',
            ['=', 'status_id', self::STATUS_FELL],
            ['<', 'updated_at', new Expression('DATE_SUB(NOW(), INTERVAL :interval SECOND)')],
        ];
        $params = [':interval' => self::FRESHNESS_TIME];

        self::$statusUpdated = true;
        self::getDb()
            ->createCommand()
            ->update(self::tableName(), $columns, $condition, $params)
            ->execute();
    }

    /**
     * Get apple string status
     */
    private static function getStatusList(): array
    {
        return [
            self::STATUS_HANGING => Yii::t('app', 'Висит'),
            self::STATUS_FELL => Yii::t('app', 'Упало'),
            self::STATUS_ROTTED => Yii::t('app', 'Сгнило'),
        ];
    }

    /**
     * Generate random date
     */
    public static function getRandomUnixTime()
    {
        $date_start = strtotime('-2 day');
        $date_end = strtotime('-1 day');

        return rand($date_start, $date_end);
    }
}
