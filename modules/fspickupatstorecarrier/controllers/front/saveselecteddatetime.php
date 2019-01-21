<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierSaveselecteddatetimeModuleFrontController extends ModuleFrontController
{
    /** @var FsPickupAtStoreCarrier */
    public $module;

    public function initContent()
    {
        $response = array('status' => 'ok');
        $this->module->setSelectedPickupDateTime(Tools::getValue('fspasc_date_time', 0));

        die(json_encode($response));
    }
}
