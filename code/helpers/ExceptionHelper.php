<?php

namespace code\helpers;

class ExceptionHelper
{
    const ERROR_GENERAL = -1;
    const INVALID_PARAMETER = -2;
    const TRANSACTION_FAILED = -3;
    const ACCESS_DENIED = -5;
    const CODE_RECORD_NOT_FOUND = -13;
    const CODE_RECORD_NOT_SAVED = -14;
    const CLASS_NOT_FOUND = -20;
    const FILE_NOT_FOUND = -21;
    const NOT_SUPPORTED = -22;
    const USER_NOT_FOUND = -23;
    const STATION_NOT_FOUND = -24;
    const UPLOADING_NOT_FOUND = -25;

    const CODE_SYSTEM_ERROR = -999;
    const INVALID_QUEUE_ITEM = -100;
    const RESTART_REQUIRED = -200;

    const ACCOUNT_NOT_FOUND = 4;
    const INVALID_PASSWORD = 5;
    const INVALID_GA_CODE = 6;
    const PASSWORDS_NOT_EQUAL = 7;
    const NEW_PASSWORD_IS_EMPTY = 8;
    const FIELD_NOT_FOUND = 9;
}