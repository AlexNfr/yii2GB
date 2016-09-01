<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Zayavka]].
 *
 * @see Zayavka
 */
class ZayavkaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Zayavka[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Zayavka|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
