<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class AdminDeliverySlipController extends AdminDeliverySlipControllerCore
{
    public function renderForm()
    {
        if (Module::isEnabled('fspickupatstorecarrier')) {
            $fspasc = Module::getInstanceByName('fspickupatstorecarrier');
            $context = Context::getContext();

            $carriers = array(array('id_carrier' => 0,'name' => $fspasc->l('All Carrier')));
            $carriers = array_merge(
                $carriers,
                Carrier::getCarriers($context->language->id, false, false, false, null, Carrier::ALL_CARRIERS)
            );

            $stores = array(array('id_store' => 0,'name' => $fspasc->l('All Store')));
            $stores = array_merge($stores, $fspasc->getStores());

            $this->fields_form = array(
                'legend' => array(
                    'title' => $fspasc->l('Print PDF delivery slips'),
                    'icon' => 'icon-print'
                ),
                'input' => array(
                    array(
                        'type' => 'date',
                        'label' => $fspasc->l('From'),
                        'name' => 'date_from',
                        'maxlength' => 10,
                        'required' => true,
                        'hint' => $fspasc->l('Format: 2011-12-31 (inclusive).')
                    ),
                    array(
                        'type' => 'date',
                        'label' => $fspasc->l('To'),
                        'name' => 'date_to',
                        'maxlength' => 10,
                        'required' => true,
                        'hint' => $fspasc->l('Format: 2012-12-31 (inclusive).')
                    ),
                    array(
                        'type' => 'select',
                        'label' => $fspasc->l('Carrier filter:'),
                        'name' => 'fspasc_id_carrier',
                        'options' => array(
                            'query' => $carriers,
                            'id' => 'id_carrier',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $fspasc->l('Store filter:'),
                        'name' => 'fspasc_id_store',
                        'options' => array(
                            'query' => $stores,
                            'id' => 'id_store',
                            'name' => 'name',
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $fspasc->l('Generate PDF file'),
                    'icon' => 'process-icon-download-alt'
                )
            );

            $this->fields_value = array(
                'date_from' => Tools::getValue('date_from', date('Y-m-d')),
                'date_to' => Tools::getValue('date_to', date('Y-m-d')),
                'fspasc_id_carrier' => Tools::getValue('fspasc_id_carrier', 0),
                'fspasc_id_store' => Tools::getValue('fspasc_id_store', 0)
            );

            return AdminController::renderForm();
        }

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdddelivery')) {
            $context = Context::getContext();
            if (!Validate::isDate(Tools::getValue('date_from'))) {
                $this->errors[] = Tools::displayError('Invalid \'from\' date');
            }
            if (!Validate::isDate(Tools::getValue('date_to'))) {
                $this->errors[] = Tools::displayError('Invalid \'to\' date');
            }
            if (!count($this->errors)) {
                if (count(OrderInvoice::getByDeliveryDateInterval(
                    Tools::getValue('date_from'),
                    Tools::getValue('date_to')
                ))) {
                    $url = $context->link->getAdminLink('AdminPdf').'&submitAction=generateDeliverySlipsPDF';
                    $url .= '&date_from='.urlencode(Tools::getValue('date_from'));
                    $url .= '&date_to='.urlencode(Tools::getValue('date_to'));
                    $url .= '&fspasc_id_carrier='.Tools::getValue('fspasc_id_carrier', 0);
                    $url .= '&fspasc_id_store='.Tools::getValue('fspasc_id_store', 0);
                    Tools::redirectAdmin($url);
                } else {
                    $this->errors[] = Tools::displayError('No delivery slip was found for this period.');
                }
            }
        } else {
            parent::postProcess();
        }
    }
}
