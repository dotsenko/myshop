<?php


/**
 * Invitation Adminhtml Block
 *
 * @category   Df
 * @package    Df_Invitation
 */

class Df_Invitation_Block_Adminhtml_Invitation extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize invitation manage page
     *
     * @return void
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_invitation';
        $this->_blockGroup = 'df_invitation';
        $this->_headerText = df_helper()->invitation()->__('Manage Invitations');
        $this->_addButtonLabel = df_helper()->invitation()->__('Add Invitations');
        parent::__construct();
    }

    public function getHeaderCssClass() {
        return 'icon-head head-invitation';
    }

}
