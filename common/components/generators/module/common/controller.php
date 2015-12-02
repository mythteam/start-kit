<?php
/**
 * This is the template for generating a controller class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo '<?php ';
?>namespace <?= $generator->getControllerNamespace() ?>;

use common\traits\MethodInjectionTrait;
use Yii;
use yii\web\Controller;

/**
 * @package <?= $generator->getControllerNamespace() . "\n" ?>
 */
class DefaultController extends Controller
{
    use MethodInjectionTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            '_access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => [], //empty, apply to all actions
                        'allow' => true,
                        'roles' => ['@'],
                        'verbs' => ['GET']
                    ],
                ],
            ]
        ];
    }

    /**
     * index action
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
