<?php



/**
 * Reward rate grid container
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Reward_Rate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'df_reward';
        $this->_controller = 'adminhtml_reward_rate';
        $this->_headerText = df_helper()->reward()->__('Manage Reward Exchange Rates');
        parent::__construct();
        $this->_updateButton('add', 'label', df_helper()->reward()->__('Add New Rate'));
    }
}
