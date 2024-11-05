<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\db\Query;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        // Build and execute the query
        $categories = (new Query())
        ->from('category')
        ->all();

        $toDos = (new Query())
        ->from('todo')
        ->all();

        $toDos = (new \yii\db\Query())
    ->select([
        'todo.id',
        'todo.name AS todo_name',
        'category.name AS category_name',
        'todo.timestamp'
    ])
    ->from('todo')
    ->innerJoin('category', 'todo.category_id = category.id')
    ->all();


        return $this->render('index', [
            'categories' => $categories,
            'toDos' => $toDos,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAddTodo()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $toDoName = Yii::$app->request->post('toDoName');
    $categoryId = Yii::$app->request->post('categoryId');

    // Insert the new to-do item using Query Builder
    $id = (new \yii\db\Query())
        ->createCommand()
        ->insert('todo', [
            'name' => $toDoName,
            'category_id' => $categoryId,
            'timestamp' => date('Y-m-d H:i:s'), // Current timestamp
        ])
        ->execute();

    if ($id) {
        return [
            'success' => true,
            'message' => 'To-do item added successfully.',
            'id' => Yii::$app->db->lastInsertID, // Get the ID of the inserted record
        ];
    }

    return [
        'success' => false,
        'message' => 'Failed to add to-do item.',
    ];
}


public function actionDeleteTodo()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $id = Yii::$app->request->post('id'); // Get the ID from POST data

    if ($id) {
        // Use Query Builder to delete the record
        $deleted = Yii::$app->db->createCommand()
            ->delete('todo', ['id' => $id]) // 'todo' is the table name
            ->execute();

        if ($deleted) {
            return ['success' => true, 'message' => 'To-do item deleted successfully.'];
        }
    }

    return ['success' => false, 'message' => 'Failed to delete the to-do item.'];
}
public function actionHome(){
    return $this->render('home');
}

}
