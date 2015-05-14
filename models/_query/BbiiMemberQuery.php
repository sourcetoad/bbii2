<?php

namespace frontend\modules\bbii\models\_query;

use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes
 *
 * @since  0.0.5
 */
class BbiiMemberQuery extends ActiveQuery
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
        return $this->andWhere(
        	'last_visit > \''.date('Y-m-d H:i:s', time() - 900).'\''
        )->orderBy('last_visit DESC');
    }


    public function show()
    {

        return $this->andWhere(['show_online' => 1]);
    }

    public function newest()
    {

        return $this->orderBy('first_visit DESC')->limit(1);
    }

    public function moderator()
    {

        return $this->andWhere(['moderator' => 1]);
    }

    public function hidden()
    {
        return true;
    }
}
