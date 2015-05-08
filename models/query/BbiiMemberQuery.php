<?php

namespace frontend\modules\bbii\models\query;

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

    public function hidden($param = true)
    {

        return $this->andWhere(['show_online' => $param]);
    }

    public function present($param = null)
    {
    	if ($param == null) {
    		$param = date('Y-m-d H:i:s', time() - 900);
    	}
        return $this->andWhere('last_visit > "'. $param.'"')->orderBy('last_visit DESC');
    }

    public function newest($param = 1)
    {

		return $this->orderBy('first_visit DESC')->limit($param);
    }

    public function show($param = 1)
    {
        return $this->andWhere('show_online = '.$param);
    }
}
