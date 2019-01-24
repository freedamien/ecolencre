<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class OrderInvoice extends OrderInvoiceCore
{
    public static function getByDeliveryDateInterval($date_from, $date_to)
    {
        $items = parent::getByDeliveryDateInterval($date_from, $date_to);
        if (Module::isEnabled('fspickupatstorecarrier')) {
            $fspasc = Module::getInstanceByName('fspickupatstorecarrier');
            $items = $fspasc->orderInvoiceGetByDeliveryDateInterval($items);
        }
        return $items;
    }
}
