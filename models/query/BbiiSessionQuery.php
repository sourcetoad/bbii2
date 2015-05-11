<?php

namespace frontend\modules\bbii\models\query;

use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes
 *
 * @since  0.0.5
 */
class BbiiSessionQuery extends ActiveQuery
{
	public function find()
	{
		return $this;
	}

	public function findAll()
	{
		return $this;
	}

    // custom query methods
    public function present()
    {
        return $this->andWhere('last_visit > \''.date('Y-m-d H:i:s', time() - 900).'\'');
    }
}
