<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 23.05.2018
 * Time: 16:19
 */

namespace code\console;


use code\helpers\Log;

class Controller extends \yii\console\Controller {
    /**
     * @throws \Exception
     */
    public function init() {
        parent::init();
        Log::setFileLog( $this->module->requestedRoute );
    }
}