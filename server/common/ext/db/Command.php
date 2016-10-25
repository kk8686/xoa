<?php
namespace xoa\common\ext\db;

use Yii;
/**
 * @inheritdoc
 */
class Command extends \yii\db\Command{
	/**
	 * @event 执行完SQL的事件
	 */
	const EVENT_AFTER_EXECUTE = 'after-execute';
	
	/**
	 * @inheritdoc
	 * @author KK
	 */
    public function execute()
    {
        $sql = $this->getSql();

        $rawSql = $this->getRawSql();

        Yii::info($rawSql, __METHOD__);

        if ($sql == '') {
            return 0;
        }

        $this->prepare(false);

        $token = $rawSql;
        try {
            Yii::beginProfile($token, __METHOD__);

            $this->pdoStatement->execute();
            $n = $this->pdoStatement->rowCount();

            Yii::endProfile($token, __METHOD__);

            $this->refreshTableSchema();

			$this->trigger(self::EVENT_AFTER_EXECUTE);
            return $n;
        } catch (\Exception $e) {
            Yii::endProfile($token, __METHOD__);
            throw $this->db->getSchema()->convertException($e, $rawSql);
        }
    }
	
}