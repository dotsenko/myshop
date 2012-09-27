<?php


class Df_AdminNotification_Model_Resource_Inbox extends Mage_AdminNotification_Model_Mysql4_Inbox {



    /**
     * Save notifications (if not exists)
     *
     * @param Mage_AdminNotification_Model_Inbox $object
     * @param array $data
     */
    public function parse(Mage_AdminNotification_Model_Inbox $object, array $data) {

		if (
				/**
				 * Для совместимости с модулем M-Turbo, который вызывает метод parse
				 * прямо из установочного скрипта
				 */
				function_exists ('df_enabled')
			&&
				df_enabled (Df_Core_Feature::TWEAKS_ADMIN)
			&&
				df_cfg()->admin()->system()->notifications()->getFixReminder()
		) {

			$this->parseDf ($object, $data);

		}

		else {

			parent::parse ($object, $data);

		}

    }





    /**
     * Save notifications (if not exists)
     *
     * @param Mage_AdminNotification_Model_Inbox $object
     * @param array $data
     */
    public function parseDf (Mage_AdminNotification_Model_Inbox $object, array $data) {

		/** @var Varien_Db_Adapter_Pdo_Mysql $adapter */
        $adapter = $this->_getWriteAdapter();

		/**
		 * В Magento ранее версии 1.6 отсутствует интерфейс Varien_Db_Adapter_Interface,
		 * поэтому проводим грубую проверку на класс Varien_Db_Adapter_Pdo_Mysql
		 */
		df_assert ($adapter instanceof Varien_Db_Adapter_Pdo_Mysql);


		foreach ($data as $item) {

			/** @var array $item  */
			df_assert_array ($item);


			/** @var Varien_Db_Select $select */
            $select =
				$adapter->select()
					->from (
						$this->getMainTable()
					)
					->where('url=? OR url IS NULL', $item['url'])
					->where('title=?', $item['title'])
			;

			df_assert ($select instanceof Varien_Db_Select);



			/** @var array|false|null $row  */
			$row = false;

            if (isset($item['internal'])) {
                $row = false;
                unset($item['internal']);
            } else {
                $row = $adapter->fetchRow($select);
            }

            if (!$row) {
                $adapter->insert($this->getMainTable(), $item);
            }
        }
    }



}

