<?php

namespace common\business\mail\monitor;

use Yii;

/**
 * The logic of email after send event
 * ~~~
 * $mailer = Yii::$app->mailer;
 * $mailer->on(BaseMailer::EVENT_AFTER_SEND, ['common\business\mail\monitor\TrackAfterSend', 'listen'])
 * ...
 * ~~~.
 */
class TrackAfterSend
{
    /**
     * Listen the mailer after send event.
     *
     * @param yii\mail\MailEvent $event
     */
    public static function listen($event)
    {
        if (!$event->isSuccessful) {
            $data[] = "\n\r";
            $data[] = date('Y-m-d H:i:s');
            $data[] = "\n";
            $data[] = '主邮件: ';
            $data[] = implode('___', array_keys($event->message->getTo()));
            $data[] = "\n";
            if ($event->message->getCc()) {
                $data[] = '抄送邮件: ';
                $data[] = implode('___', array_keys($event->message->getCc()));
                $data[] = "\n";
            }
            file_put_contents(Yii::getAlias('@app/runtime/logs/_send_email.log'), implode($data), FILE_APPEND);
        }
    }
}
