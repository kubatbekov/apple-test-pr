<?php

namespace backend\controllers;

use backend\models\Apple;
use backend\models\AppleForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class AppleController
 */
class AppleController extends Controller
{
    /**
     * @var $request
     */
    private $request;

    /**
     * @var $form
     */
    private $form;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->request = Yii::$app->request;
        $this->form = new AppleForm();
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'eat' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Action index
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Action generate apples
     */
    public function actionGenerate()
    {
        AppleForm::generateApples();

        return $this->redirect(['index']);
    }

    /**
     * Action eat apple
     */
    public function actionEat()
    {
        $this->form->setScenario(AppleForm::SCENARIO_EAT);
        $this->form->load($this->request->post());


        if ($this->form->validate() && $this->form->eatApple()) {
            return $this->redirect(['index', '#' => 'apple-id-' . $this->form->apple_id]);
        }

        $messages = implode(' ', $this->form->getErrorSummary(true));

        throw new BadRequestHttpException($messages);
    }

    /**
     * Action fell apple
     */
    public function actionFell()
    {
        $this->form->load($this->request->get(), '');

        if ($this->form->validate() && $this->form->fallToGround()) {
            return $this->redirect(['index', '#' => 'apple-id-' . $this->form->apple_id]);
        }

        $messages = implode(' ', $this->form->getErrorSummary(true));

        throw new BadRequestHttpException($messages);
    }
}
