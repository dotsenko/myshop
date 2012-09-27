<?php


class Df_Logging_Model_Mysql4_Event extends Mage_Core_Model_Mysql4_Abstract
{
   /**
    * Constructor
    */
    protected function _construct()
    {
        $this->_init('df_logging/event', 'log_id');
    }

    /**
     * Before save ip convertor
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $event)
    {
        $event->setData('ip', ip2long($event->getIp()));
        $event->setTime($this->formatDate($event->getTime()));
    }

    /**
     * Rotate logs - get from database and pump to CSV-file
     *
     * @param int $lifetime
     */
    public function rotate($lifetime)
    {
        try {
            $this->beginTransaction();

            // make sure folder for dump file will exist


			/** @var Df_Logging_Model_Archive $archive  */
            $archive = Mage::getModel('df_logging/archive');


			$archive->createNew();

            $table = $this->getTable('df_logging/event');

            // get the latest log entry required to the moment
            $clearBefore = $this->formatDate(time() - $lifetime);

            $latestLogEntry = $this->_getWriteAdapter()->fetchOne("SELECT log_id FROM {$table}
                WHERE `time` < '{$clearBefore}' ORDER BY 1 DESC LIMIT 1");
            if (!$latestLogEntry) {
                return;
            }

            // dump all records before this log entry into a CSV-file
            $csv = fopen($archive->getFilename(), 'w');
            foreach ($this->_getWriteAdapter()->fetchAll("SELECT *, INET_NTOA(ip)
                FROM {$table} WHERE log_id <= {$latestLogEntry}") as $row) {
                fputcsv($csv, $row);
            }
            fclose($csv);
            $this->_getWriteAdapter()->query("DELETE FROM {$table} WHERE log_id <= {$latestLogEntry}");
            $this->commit();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * Select all values of specified field from main table
     *
     * @param string $field
     * @param bool $order
     * @return array
     */
    public function getAllFieldValues($field, $order = true)
    {
        return $this->_getReadAdapter()->fetchCol("SELECT DISTINCT
            {$this->_getReadAdapter()->quoteIdentifier($field)} FROM {$this->getMainTable()}"
            . (null !== $order ? ' ORDER BY 1' . ($order ? '' : ' DESC') : '')
        );
    }

    /**
     * Get all admin usernames that are currently in event log table
     *
     * Possible SQL-performance issue
     *
     * @return array
     */
    public function getUserNames()
    {
        $select = $this->_getReadAdapter()->select()
            ->distinct()
            ->from(array('admins' => $this->getTable('admin/user')), 'username')
            ->joinInner(array('events' => $this->getTable('df_logging/event')),
                'admins.username = events.user', array());
        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Get event change ids of specified event
     *
     * @param int $eventId
     * @return array
     */
    public function getEventChangeIds($eventId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('df_logging/event_changes'), array('id'))
            ->where('event_id = ?', $eventId);
        return $adapter->fetchCol($select);
    }
}
