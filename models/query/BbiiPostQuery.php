<?php

namespace frontend\modules\bbii\models\query;

use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes
 *
 * @since  0.0.5
 */
class BbiiPostQuery extends ActiveQuery
{
	public function find()
	{

		return $this;
	}

	public function findAll()
	{

		return $this;
	}

    public function approved()
    {

        return $this->andWhere(['approved' => 1]);
    }

    public function unapproved()
    {

        return $this->andWhere(['approved' => 0]);
    }
}