<?php

/**
 * Created by PhpStorm.
 * User: liulipeng
 * Date: 16/3/3
 * Time: 下午6:15
 */

namespace common\models\base;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * 重写数据基类
 * @author
 *
 */
class BaseModel extends ActiveRecord
{

    const STATUS_DELETED = -2;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => '正常',
            self::STATUS_INACTIVE => '关闭',
            self::STATUS_DELETED => '删除',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusesAll()
    {
        return ArrayHelper::merge([0 => '全部'], self::getStatuses());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * Deletes the table row corresponding to this active record.
     *
     * This method performs the following steps in order:
     *
     * 1. call [[beforeDelete()]]. If the method returns false, it will skip the
     *    rest of the steps;
     * 2. delete the record from the database;
     * 3. call [[afterDelete()]].
     *
     * In the above step 1 and 3, events named [[EVENT_BEFORE_DELETE]] and [[EVENT_AFTER_DELETE]]
     * will be raised by the corresponding methods.
     *
     * @return integer|false the number of rows deleted, or false if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     * @throws StaleObjectException if [[optimisticLock|optimistic locking]] is enabled and the data
     * being deleted is outdated.
     * @throws \Exception in case delete failed.
     */
    public function delete()
    {
        if (!isset($this->status)) {
            return false;
        }
        $this->status = self::STATUS_DELETED;
        if (!$this->isTransactional(self::OP_DELETE)) {
            $this->save();
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->save();
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Deletes rows in the table using the provided conditions.
     * WARNING: If you do not specify any condition, this method will delete ALL rows in the table.
     *
     * For example, to delete all customers whose status is 3:
     *
     * ```php
     * Customer::deleteAll('status = 3');
     * ```
     *
     * @param string|array $condition the conditions that will be put in the WHERE part of the DELETE SQL.
     * Please refer to [[Query::where()]] on how to specify this parameter.
     * @param array $params the parameters (name => value) to be bound to the query.
     * @return integer the number of rows deleted
     */
    public static function deleteAll($condition = '', $params = [])
    {
        return static::updateAll(['status' => self::STATUS_DELETED], $condition, $params);
    }


}
