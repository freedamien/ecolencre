<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierAddress extends Address
{
    public function validateField($field, $value, $id_lang = null, $skip = array(), $human_errors = false)
    {
        $hash = json_encode($field);
        $hash .= json_encode($value);
        $hash .= json_encode($id_lang);
        $hash .= json_encode($skip);
        $hash .= json_encode($human_errors);
        return true || (bool)$hash;
    }
}
