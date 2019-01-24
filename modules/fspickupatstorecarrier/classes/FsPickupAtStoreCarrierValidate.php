<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierValidate extends Validate
{
    public static function isOpenHour($open_hour)
    {
        $valid = true;
        $exploded = explode('-', $open_hour);
        if (count($exploded) > 1) {
            $valid = $valid && (bool)$exploded[0];
            $valid = $valid && (bool)$exploded[1];
        }

        return $valid;
    }
}
