<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierSaveselectedstoreModuleFrontController extends ModuleFrontController
{
    /** @var FsPickupAtStoreCarrier */
    public $module;

    public function initContent()
    {
        $response = array('status' => 'ok');
        $fspasc_id_store = (int)Tools::getValue('fspasc_id_store', 0);
        if (is_numeric($fspasc_id_store) && $fspasc_id_store > 0) {
            $this->module->setSelectedStoreId($fspasc_id_store);

            if (Module::isEnabled('fspayinstore')) {
                $fspis = Module::getInstanceByName('fspayinstore');
                $response['payments'] = $fspis->getStorePayments(
                    Tools::getValue('fspasc_id_store', 0),
                    $this->context->language->id
                );
            }

            $this->module->fshelper->smartyAssign(array(
                'fspasc_time_enable' => Configuration::get('FSPASC_TIME_ENABLE'),
                'fspasc_date_enable' => Configuration::get('FSPASC_DATE_ENABLE'),
                'fspasc_calendar' => $this->module->getCalendarData($this->module->getSelectedStoreId())
            ));

            $response['calendar_html'] = $this->module->fshelper->smartyFetch('hook/calendar_17.tpl', true);

            $id_address = $this->module->getIdAddressByIdStore($fspasc_id_store);
            if ($id_address) {
                $response['summary_address_html'] = addslashes(AddressFormat::generateAddress(
                    new Address($id_address),
                    array(),
                    '<br>'
                ));
            }
        } else {
            $response['error'] = 'fspasc_id_store';
        }

        die(json_encode($response));
    }
}
