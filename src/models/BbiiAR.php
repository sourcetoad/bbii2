<?php 

namespace sourcetoad\bbii2\models;

use yii\db\ActiveRecord;

class BbiiAR extends ActiveRecord {
    public function getDbConnection() {
        if (\Yii::$app->getController()->module->dbName) {
            if (\Yii::$app->hasComponent(\Yii::$app->getController()->module->dbName)) {
                self::$db = \Yii::$app->getComponent(\Yii::$app->getController()->module->dbName);
            } else {
                self::$db = \Yii::$app->getDb();
            }
        } else {
            if (self::$db !== null) {
                return self::$db;
            }
            self::$db = \Yii::$app->getDb();
        }
        if (self::$db instanceof CDbConnection) {
            return self::$db;
        } else {
            throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
        }
    }
}