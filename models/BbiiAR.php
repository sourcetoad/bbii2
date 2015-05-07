<?php 
namespace frontend\modules\bbii\models;

use yii\db\ActiveRecord;

class BbiiAR extends ActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @deprecated 2.0.0
	 * @param string $className active record class name.
	 * @return BbiiSetting the static model class
	 */
	/*public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}*/

	public function getDbConnection() {
		if(Yii::$app->getController()->module->dbName) {
			if(Yii::$app->hasComponent(Yii::$app->getController()->module->dbName)) {
				self::$db = Yii::$app->getComponent(Yii::$app->getController()->module->dbName);
			} else {
				self::$db = Yii::$app->getDb();
			}
		} else {
			if(self::$db!==null) {
				return self::$db;
			}
			self::$db = Yii::$app->getDb();
		}
		if(self::$db instanceof CDbConnection) {
			return self::$db;
		} else {
			throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
		}
	}
}