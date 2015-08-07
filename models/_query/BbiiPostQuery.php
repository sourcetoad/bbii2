<?php

namespace sourcetoad\bbii2\models\_query;

use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes.
 *
 * @since  2.01
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