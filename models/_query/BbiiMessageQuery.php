<?php

namespace frontend\modules\bbii\models\_query;

use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes
 *
 * @since  0.0.5
 */
class BbiiMessageQuery extends ActiveQuery
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

    public function inbox()
    {

        return $this->andWhere(['inbox' => 1]);
    }

    public function outbox()
    {

        return $this->andWhere(['outbox' => 1]);
    }

    public function unread()
    {

        return $this->andWhere(['read_indicator' => 0]);
    }

    public function report()
    {

        return $this->andWhere(['sendto' => 0]);
    }
}
