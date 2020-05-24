<?php

namespace backend\models;

use Exception;
use Yii;

/**
 * Class AppleException
 */
class AppleException extends Exception
{
    /** @var int Apple not on the ground */
    public const NOT_ON_GROUND = 1;

    /** @var int Invalis percent */
    public const INVALID_PERCENT = 2;

    /** @var int Apple does not hang */
    public const DOES_NOT_HANG = 3;

    /** @var int Apple full consume */
    public const FULL_CONSUME = 4;

    /**
     * AppleException constructor.
     *
     * @param int $code
     */
    public function __construct(int $code)
    {
        parent::__construct($this->getMessages()[$code], $code);
    }

    /**
     * Get messages
     *
     * @return string[]
     */
    private static function getMessages() : array
    {
        return [
            self::NOT_ON_GROUND => Yii::t('app', 'Съесть нельзя, яблоко не на земле.'),
            self::INVALID_PERCENT => Yii::t('app', 'Нельзя съесть больше, чем есть.'),
            self::DOES_NOT_HANG => Yii::t('app', 'Нельзя уронить, яблоко не на дереве.'),
            self::FULL_CONSUME => Yii::t('app', 'Полностью поглащено.'),
        ];
    }
}
