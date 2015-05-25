<?php

namespace frontend\modules\bbii\models\_query;

use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes
 *
 * @since  0.0.5
 */
class BbiiForumQuery extends ActiveQuery
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

    public function categories()
    {

        return $this->andWhere(['type' => 0])->orderBy('sort');
    }

    public function category()
    {

        return $this->andWhere(['type' => 0]);
    }

    public function forum()
    {

        return $this->andWhere(['type' => 1]);
    }

    public function ispublic()
    {

        return $this->andWhere(['public' => 1]);
    }

    public function sorted()
    {

        return $this->orderBy('sort');
    }

    public function membergroup()
    {
        return $this->andWhere('id > 0');
    }
}
