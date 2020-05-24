<?php

namespace backend\behaviors;

use yii\base\Behavior;
use yii\base\InvalidConfigException;

/**
 * Class EventCallbackBehavior
 */
class EventCallbackBehavior extends Behavior
{
    /**
     * @var string[] $events
     */
    public $events = [];

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->events)) {
            throw new InvalidConfigException();
        }

        array_walk($this->events, function ($value) {
            if (!is_callable($value)) {
                throw new InvalidConfigException();
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function events()
    {
        return $this->events;
    }
}
