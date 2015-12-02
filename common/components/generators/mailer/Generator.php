<?php

namespace common\components\generators\mailer;

use Yii;
use yii\gii\CodeFile;

/**
 * Mailer job generator.
 */
class Generator extends \yii\gii\Generator
{
    public $mailerName;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Mailer Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return '快速生成发送邮件类';
    }
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];
        $files[] = new CodeFile(
            Yii::getAlias('@common/business/mail') . '/' . $this->mailerName . '.php',
            $this->render('class.php')
        );

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['mailerName'], 'trim'],
            [['mailerName'], 'required'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['class.php', 'usage.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['mailerName']);
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'mailerName' => 'This is the class name for the mailer. You should provide a camecase class name, e.g., <code>RegisterSuccessNotify</code>.',
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function successMessage()
    {
        $code = highlight_string($this->render('usage.php'), true);

        return <<<EOD
<p>The class has been generated successfully.</p>
<p>Usage:</p>
<pre>$code</pre>
EOD;
    }
}
