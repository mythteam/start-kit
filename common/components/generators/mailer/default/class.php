<?= '<?php ' ?>namespace common\business\mail;

/**
 * Send the email to register successfully user.
 * @package common\business\mail
 */
class <?= $generator->mailerName ?> extends BaseMail
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @inheritdoc
     */
    protected function beforeSend()
    {
        //the logic, params, email etc..
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getTemplate()
    {
        return 'path/template';
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return 'subject';
    }
}
