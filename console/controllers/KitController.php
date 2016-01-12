<?php

namespace console\controllers;

use Symfony\Component\Process\Process;
use yii\console\Controller;

/**
 * Cute tool list.
 */
class KitController extends Controller
{
    public function actionBackup()
    {
        $process = new Process('ls -lsa');

        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > ' . $buffer;
            } else {
                echo '  ' . $buffer;
            }
        });
    }
}
