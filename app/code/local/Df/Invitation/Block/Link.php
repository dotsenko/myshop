<?php


/**
 * Front end helper block to add links
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Link extends Mage_Core_Block_Template
{
    /**
     * Adding link to account links block link params if invitation
     * is allowed globaly and for current website
     *
     * @param string $block
     * @param string $name
     * @param string $path
     * @param string $label
     * @param array $urlParams
     * @return Df_Invitation_Block_Customer_Link
     */
    public function addAccountLink($block, $label, $url='', $title='', $prepare=false, $urlParams=array(),
        $position=null, $liParams=null, $aParams=null, $beforeText='', $afterText='')
    {
        if (df_helper()->invitation()->config()->isEnabledOnFront()) {
            $blockInstance = $this->getLayout()->getBlock($block);
            if ($blockInstance) {
                $blockInstance->addLink($label, $url, $title, $prepare, $urlParams,
                    $position, $liParams, $aParams, $beforeText, $afterText);
            }
        }
        return $this;
    }

    /**
     * Adding link to account links block link params if invitation
     * is allowed globaly and for current website
     *
     * @param string $block
     * @param string $name
     * @param string $path
     * @param string $label
     * @param array $urlParams
     * @return Df_Invitation_Block_Customer_Link
     */
    public function addDashboardLink($block, $name, $path, $label, $urlParams = array())
    {
        if (df_helper()->invitation()->config()->isEnabledOnFront()) {
            $blockInstance = $this->getLayout()->getBlock($block);
            if ($blockInstance) {
                $blockInstance->addLink($name, $path, $label, $urlParams);
            }
        }
        return $this;
    }
}
