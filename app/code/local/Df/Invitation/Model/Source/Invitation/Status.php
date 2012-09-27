<?php


/**
 * Invitation status source
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Source_Invitation_Status
{
    /**
     * Return list of invitation statuses as options
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            Df_Invitation_Model_Invitation::STATUS_NEW  => df_helper()->invitation()->__('Not Sent'),
            Df_Invitation_Model_Invitation::STATUS_SENT => df_helper()->invitation()->__('Sent'),
            Df_Invitation_Model_Invitation::STATUS_ACCEPTED => df_helper()->invitation()->__('Accepted'),
            Df_Invitation_Model_Invitation::STATUS_CANCELED => df_helper()->invitation()->__('Discarded')
        );
    }

    /**
     * Return list of invitation statuses as options array.
     * If $useEmpty eq to true, add empty option
     *
     * @param boolean $useEmpty
     * @return array
     */
    public function toOptionsArray($useEmpty = false)
    {
        $result = array();

        if ($useEmpty) {
            $result[] = array('value' => '', 'label' => '');
        }
        foreach ($this->getOptions() as $value=>$label) {
            $result[] = array('value' => $value, 'label' => $label);
        }

        return $result;
    }

    /**
     * Return option text by value
     *
     * @param string $option
     * @return string
     */
    public function getOptionText($option)
    {
        $options = $this->getOptions();
        if (isset($options[$option])) {
            return $options[$option];
        }

        return null;
    }
}
