<?php


namespace code\helpers;


class DBR
{
    /**
     * @param $sql
     * @param array $params
     * @param null $fetchMode
     * @return array
     * @throws \yii\db\Exception
     */
    public static function query($sql, $params = [], $fetchMode = null){
        return \Yii::$app->db->slave->createCommand($sql, $params)->queryAll($fetchMode);
    }
}