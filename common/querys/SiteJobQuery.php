<?php

namespace common\querys;

/**
 * This is the ActiveQuery class for [[\common\models\SiteJob]].
 *
 * @see \common\models\SiteJob
 */
class SiteJobQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\SiteJob[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\SiteJob|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
