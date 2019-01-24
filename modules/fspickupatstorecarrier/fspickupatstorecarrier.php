<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$class_folder = dirname(__FILE__).'/classes/';
require_once($class_folder.'FsPickupAtStoreCarrierHelper.php');
require_once($class_folder.'FsPickupAtStoreCarrierAddress.php');
require_once($class_folder.'FsPickupAtStoreCarrierEncoding.php');
require_once($class_folder.'FsPickupAtStoreCarrierModel.php');
require_once($class_folder.'FsPickupAtStoreCarrierStore.php');
require_once($class_folder.'FsPickupAtStoreCarrierValidate.php');

class FsPickupAtStoreCarrier extends CarrierModule
{
    public $contact_us_url;

    public $fshelper;

    public static $stores;

    private static $hook_action_validate_order_params = null;

    public function __construct()
    {
        $this->fshelper = FsPickupAtStoreCarrierHelper::getInstance();
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->bootstrap = true;
        $this->author = 'ModuleFactory';

        $this->name = 'fspickupatstorecarrier';
        $this->tab = 'shipping_logistics';
        $this->version = '2.1.0';
        $this->ps_versions_compliancy['min'] = '1.7';
        $this->module_key = '341dafefae9b399b80eeb7b94b10ae17';
        $this->contact_us_url = 'https://addons.prestashop.com/en/contact-us?id_product=19849';
        $this->displayName = $this->l('Personal Pickup at Store');
        $this->description = $this->l('Create an "In-Store" pickup delivery option to your customers.');

        $this->fshelper->addHooks(array(
            'actionValidateOrder',
            'actionOrderStatusPostUpdate',
            'actionObjectStoreAddAfter',
            'displayHeader',
            'displayAdminOrder',
            'displayBackOfficeHeader',
            'displayPDFDeliverySlip',
            'displayOrderDetail',
            'displayCarrierExtraContent',
            'sendMailAlterTemplateVars',
        ));

        $default_id_store = '0';
        $store_ids = array();
        $store_collection = new PrestaShopCollection('Store');
        $stores = $store_collection->getResults();
        if ($stores) {
            $default_id_store = $stores[0]->id;
            foreach ($stores as $store) {
                $store_ids[] = $store->id;
            }
        }

        $this->fshelper->addDefaultConfig(array(
            'FSPASC_ID_CARRIER' => '0',
            'FSPASC_MAP_ENABLE' => '1',
            'FSPASC_MAP_HEIGHT' => '400',
            'FSPASC_MAP_ZOOM' => '12',
            'FSPASC_STORE_DEFAULT' => $default_id_store,
            'FSPASC_STORES_ENABLE' => json_encode($store_ids),
            'FSPASC_MA_OVERRIDER' => '0',
            'FSPASC_MAP_API_KEY' => '',
            'FSPASC_EMAIL_ENABLE' => '0',
            'FSPASC_DATE_ENABLE' => '0',
            'FSPASC_PICKUP_MIN_DAYS' => '0',
            'FSPASC_PICKUP_MAX_DAYS' => '30',
            'FSPASC_PICKUP_CUT_OFF_TIME' => '0',
            'FSPASC_TIME_ENABLE' => '0',
            'FSPASC_PICKUP_MIN_HOURS' => '0',
            'FSPASC_DATE_TIME_FORMAT' => $this->fshelper->generateMultilangField('%Y. %M %D. at %T24'),
            'FSPASC_STORE_ORDER_NAME' => '0',
            'FSPASC_DISPLAY_PHONE' => '0',
            'FSPASC_OPEN_INFO_WINDOW' => '0',
            'FSPASC_INFO_WINDOW_IMAGE' => '',
            'FSPASC_FORCE_SELECT_STORE' => '0',
            'FSPASC_ENABLE_STORE_LOCATOR' => '0',
            'FSPASC_ASYNC_UI_INIT' => '0',
            'FSPASC_OPC_CONTROLLER' => '',
        ));

        $this->fshelper->addSqlTable(array(
            'name' => 'fspickupatstorecarrier',
            'columns' => array(
                array(
                    'name' => 'id_fspickupatstorecarrier',
                    'params' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'is_primary_key' => true,
                ),
                array(
                    'name' => 'id_order',
                    'params' => 'int(10) unsigned NOT NULL',
                    'is_key' => true,
                ),
                array(
                    'name' => 'id_store',
                    'params' => 'int(10) unsigned NOT NULL',
                ),
                array(
                    'name' => 'date_pickup',
                    'params' => 'datetime NOT NULL',
                ),
                array(
                    'name' => 'date_add',
                    'params' => 'datetime NOT NULL',
                ),
                array(
                    'name' => 'date_upd',
                    'params' => 'datetime NOT NULL',
                ),
            ),
        ));

        $this->fshelper->addSqlTable(array(
            'name' => 'fspickupatstorecarrier_cache',
            'columns' => array(
                array(
                    'name' => 'id_hash',
                    'params' => 'varchar(100) NOT NULL',
                    'is_primary_key' => true,
                ),
                array(
                    'name' => 'id_address',
                    'params' => 'int(10) unsigned NOT NULL',
                ),
            ),
        ));

        $this->fshelper->addSqlTable(array(
            'name' => 'fspickupatstorecarrier_cart',
            'columns' => array(
                array(
                    'name' => 'id_cart',
                    'params' => 'int(10) unsigned NOT NULL',
                    'is_primary_key' => true,
                ),
                array(
                    'name' => 'id_store',
                    'params' => 'int(10) unsigned NOT NULL',
                ),
                array(
                    'name' => 'date_pickup',
                    'params' => 'datetime NOT NULL',
                ),
            ),
        ));

        $this->fshelper->setTabSection(Tools::getValue(
            'tab_section',
            FsPickupAtStoreCarrierHelper::DEFAULT_TAB_SECTION
        ));

        parent::__construct();
    }

    public function addCarrier()
    {
        $context = Context::getContext();

        $carrier = new Carrier();
        $carrier->name = $this->l('Pickup at Store');
        $carrier->is_module = true;
        $carrier->active = 1;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = $this->name;
        $carrier->shipping_method = 2;
        $carrier->is_free = 1;
        $carrier->grade = 9;

        foreach (Language::getLanguages() as $lang) {
            $carrier->delay[$lang['id_lang']] = $this->l('Pickup your package at the selected store');
        }

        if ($carrier->add()) {
            Tools::copy(dirname(__FILE__).'/views/img/carrier.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg');

            if (Shop::isFeatureActive()) {
                Shop::setContext(Shop::CONTEXT_ALL);
            }

            Configuration::updateValue('FSPASC_ID_CARRIER', (int)$carrier->id);

            $zones = Zone::getZones(true);
            foreach ($zones as $zone) {
                $carrier->addZone($zone['id_zone']);
            }

            $groups_ids = array();
            $groups = Group::getGroups($context->language->id);
            foreach ($groups as $group) {
                $groups_ids[] = $group['id_group'];
            }
            $carrier->setGroups($groups_ids);

            return true;
        }

        return false;
    }

    public function deleteCarrier()
    {
        $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));
        if (Validate::isLoadedObject($carrier)) {
            $carrier->delete();
        }
        return true;
    }

    public function install()
    {
        $return = true;
        $return = $return && parent::install() && $this->fshelper->install();
        $return = $return && $this->installMails();
        $return = $return && $this->addCarrier();

        Configuration::updateValue('FSPASC_INSTALLED', 1);

        return $return;
    }

    public function uninstall()
    {
        $return = $this->deleteCarrier();
        $return = $return && $this->fshelper->uninstall();
        $return = $return && parent::uninstall();
        return $return;
    }

    public function installMails()
    {
        $exclude_langs = array('en');
        $mails_dir = dirname(__FILE__).'/mails/';
        $template_sources = Tools::scandir($mails_dir.'en/');

        $languages = $this->fshelper->getLanguagesForForm();
        foreach ($languages as $lang) {
            if (!in_array($lang['iso_code'], $exclude_langs)) {
                $mail_lang_dir = $mails_dir.$lang['iso_code'].'/';
                if (!file_exists($mail_lang_dir)) {
                    mkdir($mail_lang_dir);
                }

                foreach ($template_sources as $template) {
                    if (!file_exists($mail_lang_dir.$template)) {
                        Tools::copy($mails_dir.'en/'.$template, $mail_lang_dir.$template);
                    }
                }
            }
        }

        return true;
    }

    #################### ADMIN ####################

    public function getContent()
    {
        $this->fshelper->addCSS('admin.css');
        $this->fshelper->addJS('admin.js');

        $html = $this->fshelper->getMessagesHtml();

        if ($this->fshelper->isSubmit('save_'.$this->name)) {
            $form_values = array();
            foreach ($this->fshelper->getConfigKeys() as $config_key) {
                if ($this->fshelper->isSubmit($config_key)) {
                    $form_values[$config_key] = $this->fshelper->getValue($config_key);
                }
            }

            if ($this->fshelper->getConfigKeysMultilang()) {
                foreach ($this->fshelper->getConfigKeysMultilang() as $multilang_config_key) {
                    if ($this->fshelper->isSubmitMultilang($multilang_config_key)) {
                        $form_values[$multilang_config_key] = $this->fshelper->getValueMultilang($multilang_config_key);
                    }
                }
            }

            $valid = true;

            if (isset($_FILES['fspasc_marker_icon'])
                && isset($_FILES['fspasc_marker_icon']['tmp_name'])
                && !empty($_FILES['fspasc_marker_icon']['tmp_name'])
                && !$_FILES['fspasc_marker_icon']['error']) {
                $module_img_dir = _PS_MODULE_DIR_.$this->name.'/views/img/';
                $module_marker = 'map_marker-'.time().'.png';
                move_uploaded_file($_FILES['fspasc_marker_icon']['tmp_name'], $module_img_dir.$module_marker);

                $images = Tools::scandir($module_img_dir, 'png');
                if ($images) {
                    foreach ($images as $image) {
                        if ($this->fshelper->startsWith($image, 'map_marker-') && $module_marker != $image) {
                            unlink($module_img_dir.$image);
                        }
                    }
                }
            }

            $is_map_enable = Configuration::get('FSPASC_MAP_ENABLE');
            if ($is_map_enable) {
                if (!Validate::isInt($this->fshelper->getValue('FSPASC_MAP_HEIGHT', false))) {
                    $valid = false;
                    $this->fshelper->addErrorMessage(
                        $this->l('Please enter a valid map height, must be number')
                    );
                }
            }

            if ($valid) {
                if ($this->fshelper->isSubmit('FSPASC_STORE_DEFAULT')) {
                    $form_values['FSPASC_STORES_ENABLE'] = array();
                    $stores = $this->getStores();
                    if ($stores) {
                        foreach ($stores as $store) {
                            $value = $this->fshelper->getValue('FSPASC_STORES_ENABLE_'.$store['id_store']);
                            if ($value) {
                                $form_values['FSPASC_STORES_ENABLE'][] = $value;
                            }
                        }
                    }
                    $form_values['FSPASC_STORES_ENABLE'] = json_encode($form_values['FSPASC_STORES_ENABLE']);
                }

                foreach ($form_values as $option_key => $form_value) {
                    Configuration::updateValue($option_key, $form_value, true);
                }

                if ($this->fshelper->isSubmit('FSPASC_MA_OVERRIDER')) {
                    $mailalerts = Module::getInstanceByName('ps_emailalerts');
                    if ($this->fshelper->getValue('FSPASC_MA_OVERRIDER')) {
                        if ($mailalerts->isRegisteredInHook('actionValidateOrder')) {
                            $mailalerts->unregisterHook('actionValidateOrder');
                        }
                    } else {
                        if (!$mailalerts->isRegisteredInHook('actionValidateOrder')) {
                            $mailalerts->registerHook('actionValidateOrder');
                        }
                    }
                }

                $this->fshelper->addSuccessMessage($this->l('Update successful'));
            }

            $this->fshelper->redirect($this->fshelper->getAdminModuleUrlTab());
        } elseif ($this->fshelper->isSubmit('store_import_'.$this->name)) {
            $column_definition = FsPickupAtStoreCarrierStore::getColumnDefinition();

            $valid = true;
            $data = array();
            $csv_field_separator = $this->fshelper->getValue('csv_field_separator', '');
            $csv_field_enclosure = $this->fshelper->getValue('csv_field_enclosure', '');
            $file_attachment = Tools::fileAttachment('csv');

            if (empty($csv_field_separator)) {
                $this->fshelper->addErrorMessage($this->l('Please enter a valid field separator'));
                $valid = false;
            }

            if (!isset($file_attachment['content'])) {
                $this->fshelper->addErrorMessage($this->l('Please upload a CSV file'));
                $valid = false;
            }

            if ($valid) {
                $rows = array();
                $tmp_csv_file = dirname(__FILE__).'/tmp/import.csv';
                $tmp_csv_file_handle = fopen($tmp_csv_file, 'w');
                fwrite($tmp_csv_file_handle, $file_attachment['content']);
                fclose($tmp_csv_file_handle);

                $tmp_csv_file_handle = fopen($tmp_csv_file, 'r');
                $header = null;
                while ($row = fgetcsv($tmp_csv_file_handle, 0, $csv_field_separator, $csv_field_enclosure)) {
                    if ($header === null) {
                        $header = array();
                        foreach ($row as $column) {
                            $header[] = Tools::strtolower(trim($column));
                        }

                        foreach ($column_definition as $column_name => $info) {
                            if (isset($info['required']) && $info['required']) {
                                if (!in_array($column_name, $header)) {
                                    $valid = false;
                                    $this->fshelper->addErrorMessage(
                                        $this->l('Missing required column:').' '.$column_name
                                    );
                                }
                            }
                        }

                        continue;
                    }
                    foreach ($row as &$column) {
                        $column = trim($column);
                        if (!mb_detect_encoding($column, 'UTF-8', true)) {
                            $column = FsPickupAtStoreCarrierEncoding::toUTF8($column);
                        }
                    }
                    $rows[] = array_combine($header, $row);
                }

                if ($rows) {
                    if ($valid) {
                        $current_row = 1;
                        foreach ($rows as $row) {
                            $current_row++;

                            if (count($header) !== count($row)) {
                                $valid = false;
                                $this->fshelper->addErrorMessage(
                                    $this->l('Invalid row:').' '.
                                    $this->l('Row:').' '.$current_row
                                );
                                continue;
                            }

                            foreach ($column_definition as $column_name => $info) {
                                $value = '';
                                if (isset($row[$column_name])) {
                                    $value = $row[$column_name];
                                    if (isset($info['required']) && $info['required']) {
                                        if (!(bool)$value) {
                                            $valid = false;
                                            $this->fshelper->addErrorMessage(
                                                $this->l('Missing required value:').' '.
                                                $this->l('Row:').' '.$current_row.' - '.
                                                $this->l('Column:').' '.$column_name
                                            );
                                        }
                                    }

                                    if (isset($info['validate']) && (bool)$value) {
                                        if (is_callable(array(
                                            'FsPickupAtStoreCarrierValidate',
                                            $info['validate']
                                        ))) {
                                            $is_valid = call_user_func(
                                                array('FsPickupAtStoreCarrierValidate', $info['validate']),
                                                $value
                                            );
                                            if (!$is_valid) {
                                                $valid = false;
                                                $this->fshelper->addErrorMessage(
                                                    $this->l('Invalid value:').' '.
                                                    $this->l('Row:').' '.$current_row.' - '.
                                                    $this->l('Column:').' '.$column_name.' - '.
                                                    $this->l('Value:').' '.$value
                                                );
                                            }
                                        }
                                    }

                                    if (isset($info['size']) && (bool)$value) {
                                        if (Tools::strlen($value) > $info['size']) {
                                            $valid = false;
                                            $this->fshelper->addErrorMessage(
                                                $this->l('Invalid value:').' '.
                                                $this->l('Row:').' '.$current_row.' - '.
                                                $this->l('Column:').' '.$column_name.' - '.
                                                $this->l('Value:').' '.$value.' - '.
                                                $this->l('Reason:').' '.
                                                $this->l(sprintf('Too long, max %d chars', $info['size']))
                                            );
                                        }
                                    }
                                }

                                if ($column_name == 'country') {
                                    if (is_numeric($value)) {
                                        $country = new Country((int)$value);
                                        if (Validate::isLoadedObject($country)) {
                                            $data[$current_row]['id_country'] = $country->id;
                                        }
                                    } else {
                                        $data[$current_row]['id_country'] = (int)Country::getIdByName(null, $value);
                                    }

                                    if (!$data[$current_row]['id_country']) {
                                        $valid = false;
                                        $this->fshelper->addErrorMessage(
                                            $this->l('Invalid value:').' '.
                                            $this->l('Row:').' '.$current_row.' - '.
                                            $this->l('Column:').' '.$column_name.' - '.
                                            $this->l('Value:').' '.$value
                                        );
                                    }
                                }

                                if ($column_name == 'state') {
                                    if ((bool)$value) {
                                        if (is_numeric($value)) {
                                            $state = new State((int)$value);
                                            if (Validate::isLoadedObject($state)) {
                                                $data[$current_row]['id_state'] = $state->id;
                                            }
                                        } else {
                                            $data[$current_row]['id_state'] = (int)State::getIdByName($value);
                                        }

                                        if (!$data[$current_row]['id_state']) {
                                            $valid = false;
                                            $this->fshelper->addErrorMessage(
                                                $this->l('Invalid value:').' '.
                                                $this->l('Row:').' '.$current_row.' - '.
                                                $this->l('Column:').' '.$column_name.' - '.
                                                $this->l('Value:').' '.$value
                                            );
                                        }
                                    } else {
                                        $data[$current_row]['id_state'] = 0;
                                    }
                                }

                                $data[$current_row][$column_name] = $value;
                            }

                            $id_state = $data[$current_row]['id_state'];
                            $id_country = $data[$current_row]['id_country'];
                            $country = new Country((int)$id_country);

                            if ($id_country && $country && !(int)$country->contains_states && $id_state) {
                                $valid = false;
                                $this->fshelper->addErrorMessage(
                                    $this->l('Invalid value:').' '.
                                    $this->l('Row:').' '.$current_row.' - '.
                                    $this->l('You\'ve added a state for a country that does not contain states.')
                                );
                            }

                            if ((int)$country->contains_states && !$id_state) {
                                $valid = false;
                                $this->fshelper->addErrorMessage(
                                    $this->l('Invalid value:').' '.
                                    $this->l('Row:').' '.$current_row.' - '.
                                    $this->l(
                                        'An address located in a country containing states must have a state added.'
                                    )
                                );
                            }
                        }

                        if ($valid) {
                            foreach ($data as $row) {
                                $is_new = true;
                                $store = FsPickupAtStoreCarrierStore::getByName($row['name']);

                                if (Validate::isLoadedObject($store)) {
                                    $is_new = false;
                                }

                                $store->copyFromImportRow($row);
                                $store->save();

                                if (Validate::isLoadedObject($store)) {
                                    if ($is_new) {
                                        Hook::exec('actionObjectStoreAddAfter', array('object' => $store));
                                    }

                                    if (isset($row['imageurl']) && $row['imageurl']) {
                                        $image_local_path = _PS_STORE_IMG_DIR_.$store->id.'.jpg';
                                        @copy($row['imageurl'], $image_local_path);
                                    }
                                }
                            }

                            $error_string = $this->l('See "%s" -> "%s".');
                            $menu_1 = $this->l('Preferences');
                            $menu_2 = $this->l('Store Contacts');

                            $this->fshelper->addSuccessMessage(
                                $this->l('Import successful').' ('.($current_row-1).' '.$this->l('stores').'). '.
                                sprintf(
                                    $error_string,
                                    $menu_1,
                                    $menu_2
                                )
                            );
                        }
                    }
                } else {
                    $this->fshelper->addErrorMessage($this->l('Nothing to import'));
                }

                if (file_exists($tmp_csv_file)) {
                    unlink($tmp_csv_file);
                }
            }

            $this->fshelper->redirect($this->fshelper->getAdminModuleUrlTab());
        } elseif (Tools::isSubmit('change_store_'.$this->name)) {
            if ($id_order = Tools::getValue('fspasc_id_order')) {
                $order = new Order($id_order);
                $store = new Store(Tools::getValue('fspasc_id_store'));

                $valid = true;
                if (!Validate::isLoadedObject($store)) {
                    $valid = false;
                    $this->fshelper->addErrorMessage(
                        $this->l('No store selected!')
                    );
                }

                if (Configuration::get('FSPASC_DATE_ENABLE')) {
                    $date_pickup = Tools::getValue('fspasc_date_pickup');
                    if (!Validate::isDate($date_pickup)) {
                        $valid = false;
                        $this->fshelper->addErrorMessage(
                            $this->l('Please provide a valid date!')
                        );
                    }
                }

                if ($valid) {
                    $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($order->id);
                    $fspasc->id_order = $order->id;
                    $fspasc->id_store = $store->id;

                    if (Configuration::get('FSPASC_DATE_ENABLE')) {
                        $date_pickup = Tools::getValue('fspasc_date_pickup');
                        $fspasc->date_pickup = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_pickup));
                    }

                    if (!$fspasc->date_pickup) {
                        $fspasc->date_pickup = $order->date_add;
                    }

                    $fspasc->save();

                    $order->id_address_delivery = $this->getIdAddressByIdStore($fspasc->id_store);
                    $order->save();

                    if (Configuration::get('FSPASC_MA_OVERRIDER')) {
                        $this->fixCustomizationAddressForOrder($order);
                    }

                    $this->savePickupInfoAsMessage($fspasc, $order);

                    $sql = 'INSERT IGNORE INTO `'._DB_PREFIX_.'fspickupatstorecarrier_cart` (`id_cart`, `id_store`) ';
                    $sql .= 'VALUES ('.(int)$order->id_cart.', '.(int)$fspasc->id_store.')';
                    Db::getInstance()->Execute($sql);

                    $sql = 'UPDATE `'._DB_PREFIX_.'fspickupatstorecarrier_cart` SET `id_store` = ';
                    $sql .= (int)$fspasc->id_store.', `date_pickup` = \''.pSQL($fspasc->date_pickup).'\' ';
                    $sql .= 'WHERE `id_cart` = '.(int)$order->id_cart;
                    Db::getInstance()->Execute($sql);

                    $this->fshelper->addSuccessMessage(
                        $this->l('Pickup information changed!')
                    );
                }

                $redirect = $this->context->link->getAdminLink('AdminOrders').'&vieworder&id_order='.$id_order;
            } else {
                $this->fshelper->addErrorMessage(
                    $this->l('Unknown error occurred!')
                );
                $redirect = $this->context->link->getAdminLink('AdminOrders');
            }

            $this->fshelper->redirect($redirect);
        } else {
            if (Configuration::get('FSPASC_INSTALLED')) {
                $this->fshelper->setTabSection('fspasc_help_tab');
                Configuration::deleteByName('FSPASC_INSTALLED');
            }

            $error_string = $this->l('Please turn off "%s" option in "%s" -> "%s" -> "%s" panel!');
            $menu_1 = $this->l('Advanced Parameters');
            $menu_2 = $this->l('Performance');
            $panel = $this->l('Debug Mode');

            if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
                $html .= $this->displayError(sprintf(
                    $error_string,
                    'Disable non PrestaShop modules',
                    $menu_1,
                    $menu_2,
                    $panel
                ));
            }

            //Check for warehouses
            $has_warehouse_carrier_assignment = (bool)Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'warehouse_carrier` LIMIT 1'
            );

            if ($has_warehouse_carrier_assignment) {
                $msg = $this->l('Advanced stock management is enabled.');
                $msg .= ' '.$this->l('You may need to enable this carrier for warehouses.');
                $html .= $this->displayWarning($msg);
            }

            //Check product carrier relations
            $has_product_carrier_assignment = (bool)Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'product_carrier` LIMIT 1'
            );

            if ($has_product_carrier_assignment) {
                $msg = $this->l('You have product-carrier assignments.');
                $msg .= ' '.$this->l('You may need to enable this carrier for products.');
                $html .= $this->displayWarning($msg);
            }

            $tab_content = array();
            $forms_fields_value = $this->fshelper->getAdminConfig();

            $stores = $this->getStores();
            if ($stores) {
                $stores_enable = json_decode($forms_fields_value['FSPASC_STORES_ENABLE'], true);
                if (!$stores_enable) {
                    $stores_enable = array();
                }
                foreach ($stores as $store) {
                    if (in_array($store['id_store'], $stores_enable)) {
                        $forms_fields_value['FSPASC_STORES_ENABLE_'.$store['id_store']] = true;
                    } else {
                        $forms_fields_value['FSPASC_STORES_ENABLE_'.$store['id_store']] = false;
                    }
                }
            }

            $tab_content_general = $this->renderGeneralSettingsForm($forms_fields_value);
            $tab_content[] = array(
                'id' => 'fspasc_general_tab',
                'title' => $this->l('General Settings'),
                'content' => $tab_content_general
            );

            $tab_content_email = $this->renderEmailSettingsForm($forms_fields_value);
            $tab_content[] = array(
                'id' => 'fspasc_email_tab',
                'title' => $this->l('Email Notification Settings'),
                'content' => $tab_content_email
            );

            $tab_content_date_time = $this->renderPickupDateTimeSettingsForm($forms_fields_value);
            $tab_content[] = array(
                'id' => 'fspasc_pickup_date_time_tab',
                'title' => $this->l('Pickup Date/Time Settings'),
                'content' => $tab_content_date_time
            );

            $tab_content_store_importer = $this->renderStoreImporterForm($forms_fields_value);
            $tab_content[] = array(
                'id' => 'fspasc_store_importer_tab',
                'title' => $this->l('Store Importer'),
                'content' => $tab_content_store_importer
            );

            $tab_content_advanced = $this->renderAdvancedSettingsForm($forms_fields_value);
            $tab_content[] = array(
                'id' => 'fspasc_advanced_tab',
                'title' => $this->l('Advanced Settings'),
                'content' => $tab_content_advanced
            );

            $edit_carrier_url = $this->context->link->getAdminLink('AdminCarrierWizard').
                '&id_carrier='.$this->getIdCarrier();

            $this->fshelper->smartyAssign(array(
                'fspasc_help_stores_url' => $this->context->link->getAdminLink('AdminStores'),
                'fspasc_help_carriers_url' => $this->context->link->getAdminLink('AdminCarriers'),
                'fspasc_help_countries_url' => $this->context->link->getAdminLink('AdminCountries'),
                'fspasc_help_states_url' => $this->context->link->getAdminLink('AdminStates'),
                'fspasc_help_images_url' => $this->context->link->getAdminLink('AdminImages'),
                'fspasc_help_carrier_url' => $edit_carrier_url,
                'fspasc_help_sample_csv_url' => $this->fshelper->getModuleBaseUrl().'tmp/sample.csv',
                'fspasc_contact_us_url' => $this->contact_us_url,
            ));

            $tab_content_help = $this->fshelper->smartyFetch('admin/help.tpl');
            $tab_content[] = array(
                'id' => 'fspasc_help_tab',
                'title' => $this->l('Help'),
                'content' => $tab_content_help
            );

            $html .= $this->renderTabLayout($tab_content, $this->fshelper->getTabSection());
        }

        return $html;
    }

    protected function renderTabLayout($layout, $active_tab)
    {
        $this->fshelper->smartyAssign(array(
            'fspasc_tab_layout' => $layout,
            'fspasc_active_tab' => $active_tab
        ));

        return $this->fshelper->smartyFetch('admin/tab_layout.tpl');
    }

    protected function renderGeneralSettingsForm($fields_values)
    {
        $fields_form = array();
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $maps_desc = 'If you experience map malfunction or the keyless quota reached, please create a %s.';
        $maps_help = '<a href="https://goo.gl/ojhzyo" target="_blank">'.$this->l('Google Map API Key').'</a>';
        $maps_desc = sprintf($maps_desc, $maps_help);

        $gm = '';
        if (!$this->hasGoogleMapsApiKey()) {
            $gm = '<br /><br /><span class="fspasc-bold">';
            $gm .= $this->l(sprintf('To use this feature you need to setup a %s.', $this->l('Google Maps API Key')));
            $gm .= '</span>';
        }

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('General Settings')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'tab_section',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable map:'),
                    'name' => 'FSPASC_MAP_ENABLE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_MAP_ENABLE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_MAP_ENABLE_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('Displays a map under the store selector with additional information')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Google Maps API Key:'),
                    'name' => 'FSPASC_MAP_API_KEY',
                    'size' => 70,
                    'desc' => $maps_desc
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Google Maps Marker Icon:'),
                    'name' => 'fspasc_marker_icon',
                    'files' => array(
                        array(
                            'type' => 'image',
                            'image' => '<img src="'.$this->getMapMarkerIcon().'">',
                        )
                    ),
                    'desc' => $this->l('For the best view, please upload a 30 x 30 pixel size PNG image file.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Map height:'),
                    'name' => 'FSPASC_MAP_HEIGHT',
                    'size' => 70,
                    'required' => true,
                    'suffix' => 'px',
                    'desc' => $this->l('Recommended').': 400px'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Map zoom level:'),
                    'name' => 'FSPASC_MAP_ZOOM',
                    'options' => array(
                        'query' => array(
                            array('id' =>  0, 'name' =>  0),
                            array('id' =>  1, 'name' =>  1),
                            array('id' =>  2, 'name' =>  2),
                            array('id' =>  3, 'name' =>  3),
                            array('id' =>  4, 'name' =>  4),
                            array('id' =>  5, 'name' =>  5),
                            array('id' =>  6, 'name' =>  6),
                            array('id' =>  7, 'name' =>  7),
                            array('id' =>  8, 'name' =>  8),
                            array('id' =>  9, 'name' =>  9),
                            array('id' => 10, 'name' => 10),
                            array('id' => 11, 'name' => 11),
                            array('id' => 12, 'name' => 12),
                            array('id' => 13, 'name' => 13),
                            array('id' => 14, 'name' => 14),
                            array('id' => 15, 'name' => 15),
                            array('id' => 16, 'name' => 16),
                            array('id' => 17, 'name' => 17),
                            array('id' => 18, 'name' => 18),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Recommended').': 12'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Default store:'),
                    'name' => 'FSPASC_STORE_DEFAULT',
                    'options' => array(
                        'query' => $this->getStores(),
                        'id' => 'id_store',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Store selector default value, also centers the map to this location')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Force customer to select store:'),
                    'name' => 'FSPASC_FORCE_SELECT_STORE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_FORCE_SELECT_STORE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_FORCE_SELECT_STORE_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('If you select yes, then no default store selected in the checkout process.')
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Enabled stores:'),
                    'name' => 'FSPASC_STORES_ENABLE',
                    'values' => array(
                        'query' => $this->getStores(),
                        'id' => 'id_store',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Only selected stores available in the selector'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Sort stores alphabetically:'),
                    'name' => 'FSPASC_STORE_ORDER_NAME',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_STORE_ORDER_NAME_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_STORE_ORDER_NAME_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('The stores in the dropdown sorted by alphabetically.')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display phone number:'),
                    'name' => 'FSPASC_DISPLAY_PHONE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_DISPLAY_PHONE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_DISPLAY_PHONE_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('The selected store\'s phone phone number visible under the address.')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Open info window:'),
                    'name' => 'FSPASC_OPEN_INFO_WINDOW',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_OPEN_INFO_WINDOW_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_OPEN_INFO_WINDOW_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('The selected store\'s info window on the map automatically opened.')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Info window store image size:'),
                    'name' => 'FSPASC_INFO_WINDOW_IMAGE',
                    'options' => array(
                        'query' => $this->getImageSizesByType('stores'),
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '',
                            'label' => $this->l('No image displayed'),
                        ),
                    ),
                    'desc' => $this->l('Select a predefined image size to display store image in the info window.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable nearest store locator:'),
                    'name' => 'FSPASC_ENABLE_STORE_LOCATOR',
                    'disabled' => !$this->hasGoogleMapsApiKey(),
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_ENABLE_STORE_LOCATOR_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_ENABLE_STORE_LOCATOR_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('An address search filed under the map.').$gm,
                ),
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fspasc_general_settings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->fshelper->getLanguagesForForm();
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'save_'.$this->name;
        $helper->fields_value = $fields_values;
        $helper->fields_value['tab_section'] = 'fspasc_general_tab';

        $fields_form[0]['form']['submit'] = array('title' => $this->l('Save'));

        return $helper->generateForm($fields_form);
    }

    protected function renderEmailSettingsForm($fields_values)
    {
        $fields_form = array();
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Email Notification Settings')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'tab_section',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('New order notification to store:'),
                    'name' => 'FSPASC_EMAIL_ENABLE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_EMAIL_ENABLE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_EMAIL_ENABLE_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('If you select yes, the selected store will receive a notification email.').' '
                        .$this->l('You can edit the email template in International -> Translation.').' '
                        .$this->l('Select Email templates translations for type and Core (no theme).')

                )
            )
        );

        $mailalerts_enabled = Module::isEnabled('ps_emailalerts');

        if ($mailalerts_enabled) {
            $fields_form[0]['form']['input'][] = array(
                'type' => 'switch',
                'label' => $this->l('Mail alerts module integration:'),
                'name' => 'FSPASC_MA_OVERRIDER',
                'class' => 't',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'FSPASC_MA_OVERRIDER_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'FSPASC_MA_OVERRIDER_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    ),
                ),
                'desc' => $this->l('If you select yes, the selected stores address will send as delivery address.')
            );
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fspasc_email_settings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->fshelper->getLanguagesForForm();
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'save_'.$this->name;
        $helper->fields_value = $fields_values;
        $helper->fields_value['tab_section'] = 'fspasc_email_tab';

        $fields_form[0]['form']['submit'] = array('title' => $this->l('Save'));

        return $helper->generateForm($fields_form);
    }

    protected function renderStoreImporterForm($fields_values)
    {
        $sample_csv_url = $this->fshelper->getModuleBaseUrl().'tmp/sample.csv';
        $fields_values['csv_field_separator'] = ',';
        $fields_values['csv_field_enclosure'] = '';

        $fields_form = array();
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Store Importer')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'tab_section',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Field separator:'),
                    'lang' => false,
                    'name' => 'csv_field_separator',
                    'size' => 70,
                    'required' => true,
                    'desc' => $this->l('Enter in the character that is used in your CSV import file that separates each field or column. Make sure this character does not appear elsewhere in the CSV file. Usually in a CSV (Comma Separated Values) file this character is a \',\'. Make sure your columns themselves don\'t contain commas by searching and replacing all commas with just a blank value.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Field enclosure:'),
                    'lang' => false,
                    'name' => 'csv_field_enclosure',
                    'size' => 70,
                    'required' => false,
                    'desc' => $this->l('Which character is each field enclosed by? All fields must have this character around them. For example, a record might look like this:').
                        '<br/><br/>"Test Store","Test st.","","Miami","33135"',
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('CSV file:'),
                    'lang' => false,
                    'name' => 'csv',
                    'size' => 70,
                    'required' => true,
                    'desc' => $this->l('The CSV file must contains a header row!').' <a href="'.$sample_csv_url.
                        '" target="_blank">'.$this->l('Download sample CSV file').'</a>.<br /><br />'.
                        $this->l('If you import images, please regenerate the store image thumbnails.'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fspasc_store_importer';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->fshelper->getLanguagesForForm();
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'store_import_'.$this->name;
        $helper->fields_value = $fields_values;
        $helper->fields_value['tab_section'] = 'fspasc_store_importer_tab';

        $fields_form[0]['form']['submit'] = array('title' => $this->l('Import'));

        return $helper->generateForm($fields_form);
    }

    protected function renderPickupDateTimeSettingsForm($fields_values)
    {
        $fields_form = array();
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $hours_desc = ' '.$this->l('This information comes from the store\'s opening hours.');
        $hours_desc .= ' '.$this->l('Please see our help how to fill properly the opening hours.');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Pickup Date/Time Settings')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'tab_section',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable pickup date selection:'),
                    'name' => 'FSPASC_DATE_ENABLE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_DATE_ENABLE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_DATE_ENABLE_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('If you select yes, the customers are able to select a pickup date.').$hours_desc
                )
            )
        );

        if (Configuration::get('FSPASC_DATE_ENABLE')) {
            $fields_form[0]['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Minimum days:'),
                'name' => 'FSPASC_PICKUP_MIN_DAYS',
                'size' => 70,
                'suffix' => $this->l('days'),
                'desc' =>
                    $this->l('The minimum open days what the customer needs to wait before can pickup the package.')
            );

            $fields_form[0]['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Maximum days:'),
                'name' => 'FSPASC_PICKUP_MAX_DAYS',
                'size' => 70,
                'suffix' => $this->l('days'),
                'desc' => $this->l('The maximum open days until the customer can pickup the package.')
                    .' '.$this->l('Relative to the next available date.')
            );

            $fields_form[0]['form']['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Cut off time:'),
                'name' => 'FSPASC_PICKUP_CUT_OFF_TIME',
                'options' => array(
                    'query' => array(
                        array('id' =>  0, 'name' => $this->l('Disable')),
                        array('id' =>  1, 'name' => '01:00'),
                        array('id' =>  2, 'name' => '02:00'),
                        array('id' =>  3, 'name' => '03:00'),
                        array('id' =>  4, 'name' => '04:00'),
                        array('id' =>  5, 'name' => '05:00'),
                        array('id' =>  6, 'name' => '06:00'),
                        array('id' =>  7, 'name' => '07:00'),
                        array('id' =>  8, 'name' => '08:00'),
                        array('id' =>  9, 'name' => '09:00'),
                        array('id' => 10, 'name' => '10:00'),
                        array('id' => 11, 'name' => '11:00'),
                        array('id' => 12, 'name' => '12:00'),
                        array('id' => 13, 'name' => '13:00'),
                        array('id' => 14, 'name' => '14:00'),
                        array('id' => 15, 'name' => '15:00'),
                        array('id' => 16, 'name' => '16:00'),
                        array('id' => 17, 'name' => '17:00'),
                        array('id' => 18, 'name' => '18:00'),
                        array('id' => 19, 'name' => '19:00'),
                        array('id' => 20, 'name' => '20:00'),
                        array('id' => 21, 'name' => '21:00'),
                        array('id' => 22, 'name' => '22:00'),
                        array('id' => 23, 'name' => '23:00'),
                    ),
                    'id' => 'id',
                    'name' => 'name',
                ),
                'desc' => $this->l('After this time the minimum days rolls over to the next available day.')
            );

            $fields_form[0]['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Date/Time format:'),
                'name' => 'FSPASC_DATE_TIME_FORMAT',
                'lang' => true,
                'size' => 70,
                'desc' => $this->l('Default: %Y. %M %D. at %T24').'<br />'
                    .$this->l('Example: ').$this->formatDate('2016-08-09 10:00:00').'<br /><br />'
                    .$this->l('%Y: Year').'<br />'
                    .$this->l('%M: Month').'<br />'
                    .$this->l('%D: Day').'<br />'
                    .$this->l('%T12: Time, 12 hour format').'<br />'
                    .$this->l('%T24: Time, 24 hour format')

            );

            $fields_form[0]['form']['input'][] = array(
                'type' => 'switch',
                'label' => $this->l('Enable pickup time selection:'),
                'name' => 'FSPASC_TIME_ENABLE',
                'class' => 't',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'FSPASC_TIME_ENABLE_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'FSPASC_TIME_ENABLE_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    ),
                ),
                'desc' => $this->l('If you select yes, the customers are able to select a pickup time as well.').
                    $hours_desc
            );

            if (Configuration::get('FSPASC_TIME_ENABLE')) {
                $fields_form[0]['form']['input'][] = array(
                    'type' => 'text',
                    'label' => $this->l('Minimum hours:'),
                    'name' => 'FSPASC_PICKUP_MIN_HOURS',
                    'size' => 70,
                    'suffix' => $this->l('hours'),
                    'desc' =>
                        $this->l('The minimum hours what the customer needs to wait before can pickup the package.')
                );
            }
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fspasc_pickup_date_time_settings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->fshelper->getLanguagesForForm();
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'save_'.$this->name;
        $helper->fields_value = $fields_values;
        $helper->fields_value['tab_section'] = 'fspasc_pickup_date_time_tab';

        $fields_form[0]['form']['submit'] = array('title' => $this->l('Save'));

        return $helper->generateForm($fields_form);
    }

    protected function renderChangeStoreForm($fields_values)
    {
        $fields_form = array();
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Change Pickup Information')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'fspasc_id_order',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Pickup Location:'),
                    'name' => 'fspasc_id_store',
                    'options' => array(
                        'query' => $this->getStores(),
                        'id' => 'id_store',
                        'name' => 'name',
                        'default' => array(
                            'value' => '0',
                            'label' => $this->l('- Please Select a Store -'),
                        ),
                    ),
                ),
            )
        );

        $pickup_date = $fields_values['fspasc_date_pickup'];
        if (!Validate::isDate($pickup_date)) {
            $pickup_date = strftime('%Y-%m-%d 00:00:00');
        }

        if (Configuration::get('FSPASC_DATE_ENABLE')) {
            if (Configuration::get('FSPASC_TIME_ENABLE')) {
                $fields_form[0]['form']['input'][] = array(
                    'type' => 'datetime',
                    'label' => $this->l('Pickup Date:'),
                    'name' => 'fspasc_date_pickup',
                );
            } else {
                $pickup_date = strftime('%Y-%m-%d', strtotime($pickup_date));
                $fields_form[0]['form']['input'][] = array(
                    'type' => 'date',
                    'label' => $this->l('Pickup Date:'),
                    'name' => 'fspasc_date_pickup',
                );
            }
        }

        $fields_values['fspasc_date_pickup'] = $pickup_date;

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fspasc_change_store';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->fshelper->getLanguagesForForm();
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'change_store_'.$this->name;
        $helper->fields_value = $fields_values;

        $fields_form[0]['form']['submit'] = array('title' => $this->l('Save'));

        return $helper->generateForm($fields_form);
    }

    protected function renderAdvancedSettingsForm($fields_values)
    {
        $fields_form = array();
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced Settings')
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'tab_section',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Async UI initialization:'),
                    'name' => 'FSPASC_ASYNC_UI_INIT',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'FSPASC_ASYNC_UI_INIT_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'FSPASC_ASYNC_UI_INIT_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ),
                    ),
                    'desc' => $this->l('If you are using a One Page Checkout module, you may need to enabled this.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Unknown OPC controller name:'),
                    'name' => 'FSPASC_OPC_CONTROLLER',
                    'size' => 70,
                    'desc' => $this->l('This filed is for us! Please do NOT change!')
                ),
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fspasc_advanced_settings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->fshelper->getLanguagesForForm();
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'save_'.$this->name;
        $helper->fields_value = $fields_values;
        $helper->fields_value['tab_section'] = 'fspasc_advanced_tab';

        $fields_form[0]['form']['submit'] = array('title' => $this->l('Save'));

        return $helper->generateForm($fields_form);
    }

    #################### ADMIN HOOKS ####################

    public function hookDisplayPDFDeliverySlip($params)
    {
        $html = '';
        $id_order = $params['object']->id_order;
        $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($id_order);
        if ($fspasc->id) {
            $stores = $this->getStores();
            if (isset($stores[$fspasc->id_store])) {
                $html .= '<span style="color:#000000;text-decoration:underline;">';
                $html .= $this->l('Selected pickup location').':</span> ';
                $html .= $stores[$fspasc->id_store]['name'].' - '.$stores[$fspasc->id_store]['full_address'];

                if (Configuration::get('FSPASC_DATE_ENABLE')) {
                    $html .= '<br /><span style="color:#000000;text-decoration:underline;">';
                    $html .= $this->l('Selected pickup date').':</span> ';
                    $html .= strftime('%A, %Y-%m-%d', strtotime($fspasc->date_pickup));
                    if (Configuration::get('FSPASC_TIME_ENABLE')) {
                        $html .= ' at '.strftime('%H:%M', strtotime($fspasc->date_pickup));
                    }
                }
            }
        }
        return $html;
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        $context = Context::getContext();
        if (in_array($context->controller->controller_name, array('AdminDeliverySlip'))) {
            $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));
            $this->fshelper->smartyAssign(array(
                'fspasc_id_carrier' => $carrier->id,
                'fspasc_params_hash' => sha1(json_encode($params))
            ));

            return $this->fshelper->smartyFetch('admin/css_js.tpl');
        }

        if (in_array($context->controller->controller_name, array('AdminOrders')) && Tools::isSubmit('addorder')) {
            $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));

            $date_time_mode = 'disabled';
            if (Configuration::get('FSPASC_DATE_ENABLE')) {
                $date_time_mode = 'date';
                if (Configuration::get('FSPASC_TIME_ENABLE')) {
                    $date_time_mode = 'datetime';
                }
            }

            $this->fshelper->smartyAssign(array(
                'fspasc_id_carrier' => $carrier->id,
                'fspasc_date_time_mode' => $date_time_mode,
                'fspasc_stores' => $this->getStores()
            ));

            return $this->fshelper->smartyFetch('admin/css_js_order_add.tpl');
        }
    }

    public function hookDisplayAdminOrder($params)
    {
        $id_order = $params['id_order'];
        $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($id_order);
        if ($fspasc->id) {
            $stores = $this->getStores();
            if (isset($stores[$fspasc->id_store])) {
                $message = '<strong>'.$this->l('Selected pickup location').':</strong> ';
                $message .= $stores[$fspasc->id_store]['name'].' - '.$stores[$fspasc->id_store]['full_address'];

                if (Configuration::get('FSPASC_DATE_ENABLE')) {
                    $message .= '<br /><strong>'.$this->l('Selected pickup date').':</strong> ';
                    $message .= strftime('%A, %Y-%m-%d', strtotime($fspasc->date_pickup));
                    if (Configuration::get('FSPASC_TIME_ENABLE')) {
                        $message .= ' at '.strftime('%H:%M', strtotime($fspasc->date_pickup));
                    }
                }

                $this->adminDisplayWarning($message);
            }
        }

        $order = new Order($id_order);
        $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));
        $carrier_ids = $this->getCarrierHistory((int)$carrier->id);

        if (in_array((int)$order->id_carrier, $carrier_ids)) {
            $this->context->controller->confirmations = array_merge(
                $this->context->controller->confirmations,
                $this->fshelper->getSuccessMessages()
            );

            $this->context->controller->errors = array_merge(
                $this->context->controller->errors,
                $this->fshelper->getErrorMessages()
            );

            return $this->renderChangeStoreForm(array(
                'fspasc_id_order' => $id_order,
                'fspasc_id_store' => $fspasc->id_store,
                'fspasc_date_pickup' => $fspasc->date_pickup
            ));
        }

        return '';
    }

    public function hookActionObjectStoreAddAfter($params)
    {
        $store = $params['object'];
        if (Shop::isFeatureActive()) {
            $shop_ids = $store->getAssociatedShops();
            if ($shop_ids) {
                foreach ($shop_ids as $id_shop) {
                    $shop = new Shop($id_shop);
                    $id_shop_group = $shop->id_shop_group;
                    $stores_enable = json_decode(Configuration::get(
                        'FSPASC_STORES_ENABLE',
                        null,
                        $id_shop_group,
                        $id_shop
                    ), true);
                    if (!$stores_enable) {
                        $stores_enable = array();
                    }
                    $stores_enable[] = $store->id;
                    $stores_enable = json_encode(array_unique($stores_enable));
                    Configuration::updateValue('FSPASC_STORES_ENABLE', $stores_enable, false, $id_shop_group, $id_shop);
                }
            }
        } else {
            $stores_enable = json_decode(Configuration::get('FSPASC_STORES_ENABLE'), true);
            if (!$stores_enable) {
                $stores_enable = array();
            }
            $stores_enable[] = $store->id;
            $stores_enable = json_encode(array_unique($stores_enable));
            Configuration::updateValue('FSPASC_STORES_ENABLE', $stores_enable);
        }
    }

    public function displayInfoByCart($id_cart)
    {
        $html = '';
        $id_order = Order::getOrderByCartId($id_cart);
        $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($id_order);
        if ($fspasc->id) {
            $stores = $this->getStores();
            if (isset($stores[$fspasc->id_store])) {
                $message = '<strong>'.$this->l('Selected pickup location').':</strong> ';
                $message .= $stores[$fspasc->id_store]['name'].' - '.$stores[$fspasc->id_store]['full_address'];

                if (Configuration::get('FSPASC_DATE_ENABLE')) {
                    $message .= '<br /><strong>'.$this->l('Selected pickup date').':</strong> ';
                    $message .= strftime('%A, %Y-%m-%d', strtotime($fspasc->date_pickup));
                    if (Configuration::get('FSPASC_TIME_ENABLE')) {
                        $message .= ' at '.strftime('%H:%M', strtotime($fspasc->date_pickup));
                    }
                }

                if (is_callable(array($this, 'displayWarning'))) {
                    $html .= $this->displayWarning($message);
                }
            }
        }
        return $html;
    }

    #################### FRONT HOOKS ####################

    public function hookActionValidateOrder($params)
    {
        self::$hook_action_validate_order_params = $params;

        $order = $params['order'];
        $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));

        if ($order->id_carrier == $carrier->id) {
            $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($order->id);
            if (!$fspasc->id) {
                $fspasc->id_order = $order->id;

                if (defined('_PS_ADMIN_DIR_') && Tools::isSubmit('submitAddOrder')) {
                    $fspasc->id_store = Tools::getValue('fspasc_id_store');
                    if (Configuration::get('FSPASC_DATE_ENABLE')) {
                        $date_pickup = Tools::getValue('fspasc_date_pickup');
                        $fspasc->date_pickup = strftime('%Y-%m-%d %H:%M:%S', strtotime($date_pickup));
                    } else {
                        $fspasc->date_pickup = $order->date_add;
                    }
                } else {
                    $fspasc->id_store = $this->getSavedStoreIdByIdCart($order->id_cart);
                    if (!$fspasc->id_store) {
                        $fspasc->id_store = $this->getSelectedStoreId();
                    }

                    if (Configuration::get('FSPASC_DATE_ENABLE')) {
                        $fspasc->date_pickup = $this->getSavedPickupDateTimeByIdCart($order->id_cart);
                    } else {
                        $fspasc->date_pickup = $order->date_add;
                    }

                    if (!$fspasc->date_pickup) {
                        $fspasc->date_pickup = $order->date_add;
                    }
                }

                $fspasc->save();

                if ($fspasc->id_store) {
                    $this->savePickupInfoAsMessage($fspasc, $order);
                }
            }
        } else {
            //In this case other carrier used, just send the new order notification
            if (Configuration::get('FSPASC_MA_OVERRIDER')) {
                $this->useMailAlerts(self::$hook_action_validate_order_params);
            }
        }
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        $order = new Order($params['id_order']);
        $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($order->id);
        if ($fspasc->id_store) {
            $id_address_delivery = $this->getIdAddressByIdStore($fspasc->id_store);
            if ($id_address_delivery) {
                $order->id_address_delivery = $id_address_delivery;
                $order->save();

                //Check for we are in the checkout process
                if (is_array(self::$hook_action_validate_order_params)) {
                    //Modify the params to send the correct address
                    self::$hook_action_validate_order_params['order'] = $order;

                    if (Configuration::get('FSPASC_MA_OVERRIDER')) {
                        $this->fixCustomizationAddressForOrder($order);
                        //Use MailAlerts validate order hook to send new order notification
                        $this->useMailAlerts(self::$hook_action_validate_order_params);
                    }

                    if (Configuration::get('FSPASC_EMAIL_ENABLE')) {
                        $this->sendOrderEmailToStore(self::$hook_action_validate_order_params, $fspasc);
                    }
                }
            }
        }
    }

    public function hookSendMailAlterTemplateVars($params)
    {
        if ($params['template'] == 'order_conf' && is_array(self::$hook_action_validate_order_params)) {
            $order = self::$hook_action_validate_order_params['order'];
            $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($order->id);
            if ($fspasc->id_store) {
                $date_pickup = $this->formatDate($fspasc->date_pickup);
                $date_pickup_txt = "\n".$this->l('Pickup Time:').' '.$date_pickup;
                $date_pickup_html = '<br /><span style="font-weight:bold;">'.$this->l('Pickup Time:');
                $date_pickup_html .= '</span> '.$date_pickup;

                if (!isset($params['template_vars']['{delivery_block_txt}'])) {
                    $params['template_vars']['{delivery_block_txt}'] = '';
                }

                if (!isset($params['template_vars']['{delivery_block_html}'])) {
                    $params['template_vars']['{delivery_block_html}'] = '';
                }

                $params['template_vars']['{delivery_block_txt}'] .= $date_pickup_txt;
                $params['template_vars']['{delivery_block_html}'] .= $date_pickup_html;
            }
        }
    }

    public function hookDisplayHeader($params)
    {
        $display = false;
        if (isset($this->context->controller->php_self)
            && in_array($this->context->controller->php_self, array('order', 'order-opc'))) {
            $display = true;
        }

        $opc_controllers = array(
            'module-bestkit_opc-checkout'
        );
        $extra_opc_controller = Configuration::get('FSPASC_OPC_CONTROLLER');
        if ($extra_opc_controller) {
            $opc_controllers[] = $extra_opc_controller;
        }

        if (isset($this->context->controller->page_name)
            && in_array($this->context->controller->page_name, $opc_controllers)) {
            $display = true;
        }

        if ($display) {
            $this->fshelper->addCSS('front.css');
            $this->fshelper->addJS('front.js');
            $this->fshelper->addJS('calendar.js');

            $this->fshelper->addCSS('calendar.css');
            $this->fshelper->addCSS('fspasc-font-awesome.min.css');

            $this->fshelper->smartyAssign(array(
                'fspasc_params_hash' => sha1(json_encode($params))
            ));

            return $this->getCssAndJs();
        }

        return '';
    }

    public function hookDisplayOrderDetail($params)
    {
        if (Configuration::get('FSPASC_DATE_ENABLE')) {
            $id_order = $params['order']->id;
            $fspasc = FsPickupAtStoreCarrierModel::getByIdOrder($id_order);
            if (Validate::isLoadedObject($fspasc) && Configuration::get('FSPASC_DATE_ENABLE')) {
                $this->fshelper->smartyAssign(array('fspasc_pickup_date' => $this->formatDate($fspasc->date_pickup)));
                return $this->fshelper->smartyFetch('hook/order_detail_17.tpl', true);
            }
        }

        return '';
    }

    public function hookDisplayCarrierExtraContent($params)
    {
        $stores = $this->getStoresFront();
        if (Configuration::get('FSPASC_FORCE_SELECT_STORE')) {
            $default_store = $stores[$this->getSelectedStoreId()];
            $default_store['id_store'] = 0;
            $default_store['name'] = $this->l('- Please select a store -');
            $default_store['full_address'] = '-';
            if ($default_store['phone']) {
                $default_store['phone'] = '-';
            }
            $stores = array(0 => $default_store) + $stores;
        }

        $customer_address = '';
        if ($this->context->cart->id_address_delivery && false) {
            $delivery_address = new Address($this->context->cart->id_address_delivery);
            if (Validate::isLoadedObject($delivery_address)) {
                $customer_address = $delivery_address->address1;
                if ($delivery_address->address2) {
                    $customer_address .= ' '.$delivery_address->address2;
                }

                $customer_address .= ' '.$delivery_address->city;
                if ($delivery_address->id_state) {
                    $state = new State($delivery_address->id_state);
                    $customer_address .= ', '.$state->iso_code;
                }

                $customer_address .= ' '.$delivery_address->postcode;
            }
        }

        $this->fshelper->smartyAssign(array(
            'fspasc_stores' => $stores,
            'fspasc_map_enable' => Configuration::get('FSPASC_MAP_ENABLE'),
            'fspasc_time_enable' => Configuration::get('FSPASC_TIME_ENABLE'),
            'fspasc_date_enable' => Configuration::get('FSPASC_DATE_ENABLE'),
            'fspasc_calendar' => $this->getCalendarData($this->getSelectedStoreId()),
            'fspasc_selected_date_time' => $this->getSelectedPickupDateTime(),
            'fspasc_enable_store_locator' => Configuration::get('FSPASC_ENABLE_STORE_LOCATOR') &&
                $this->hasGoogleMapsApiKey(),
            'fspasc_customer_address' => $customer_address,
            'fspasc_async_ui_init' => Configuration::get('FSPASC_ASYNC_UI_INIT'),
        ));

        $this->fshelper->smartyAssign(array(
            'fspasc_calendar_html' => $this->fshelper->smartyFetch('hook/calendar_17.tpl', true),
            'fspasc_params_hash' => sha1(json_encode($params))
        ));

        return $this->fshelper->smartyFetch('hook/carrier_extra_content_17.tpl', true);
    }

    #################### FUNCTIONS ####################

    public function getOrderShippingCost($params, $shipping_cost)
    {
        $this->fshelper->smartyAssign(array('fspasc_params_hash' => sha1(json_encode($params))));
        return $shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        return $this->getOrderShippingCost($params, 0);
    }

    public function getCssAndJs()
    {
        $context = Context::getContext();
        $language_code_array = explode('-', $context->language->language_code);
        $language_code_for_map = 'en';
        if (isset($language_code_array[0])) {
            $language_code_for_map = $language_code_array[0];
        }

        $fspasc_css = array(
            'map_height' => Configuration::get('FSPASC_MAP_HEIGHT')
        );

        $display_phone = 0;
        if (Configuration::get('FSPASC_DISPLAY_PHONE')) {
            $display_phone = 1;
        }

        $async_ui_init = 0;
        if (Configuration::get('FSPASC_ASYNC_UI_INIT')) {
            $async_ui_init = 1;
        }

        $open_info_window = 0;
        if (Configuration::get('FSPASC_OPEN_INFO_WINDOW')) {
            $open_info_window = 1;
        }

        $selected_id_store = $this->getSelectedStoreId();
        if (Configuration::get('FSPASC_FORCE_SELECT_STORE') && !$this->getSavedStoreIdByIdCart($context->cart->id)) {
            $selected_id_store = 0;
        }

        $stores = $this->getStoresFront();
        if (Configuration::get('FSPASC_FORCE_SELECT_STORE')) {
            $default_store = $stores[$this->getSelectedStoreId()];
            $default_store['id_store'] = 0;
            $default_store['name'] = $this->l('- Please select a store -');
            $default_store['full_address'] = '-';
            if ($default_store['phone']) {
                $default_store['phone'] = '';
            }
            $stores = array(0 => $default_store) + $stores;
        }

        $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));

        $formatted_address = '';
        $id_store = $this->getSelectedStoreId();
        if ($id_store) {
            $id_address = $this->getIdAddressByIdStore($id_store);
            if ($id_address) {
                $formatted_address = addslashes(AddressFormat::generateAddress(
                    new Address($id_address),
                    array(),
                    '<br>'
                ));
            }
        }

        $fspasc_js = array(
            'callback_url' => $context->link->getModuleLink(
                'fspickupatstorecarrier',
                'saveselectedstore',
                array('ajax' => 1),
                Configuration::get('PS_SSL_ENABLED')
            ),
            'callback_url_dt' => $context->link->getModuleLink(
                'fspickupatstorecarrier',
                'saveselecteddatetime',
                array('ajax' => 1),
                Configuration::get('PS_SSL_ENABLED')
            ),
            'geocode_url' => $context->link->getModuleLink(
                'fspickupatstorecarrier',
                'geocode',
                array('ajax' => 1),
                Configuration::get('PS_SSL_ENABLED')
            ),
            'stores' => json_encode($stores),
            'selected_id_store' => $selected_id_store,
            'marker_url' => $this->getMapMarkerIcon(),
            'marker_type' => 'icon',
            'map_zoom' => Configuration::get('FSPASC_MAP_ZOOM'),
            'map_enable' => (Configuration::get('FSPASC_MAP_ENABLE')?'true':'false'),
            'language_code_for_map' => $language_code_for_map,
            'map_api_key' => trim(Configuration::get('FSPASC_MAP_API_KEY')),
            'time_enable' => Configuration::get('FSPASC_TIME_ENABLE'),
            'date_enable' => Configuration::get('FSPASC_DATE_ENABLE'),
            'date_time_format' => Configuration::get(
                'FSPASC_DATE_TIME_FORMAT',
                $context->language->id
            ),
            'display_phone' => $display_phone,
            'open_info_window' => $open_info_window,
            'module_id_carrier' => $carrier->id,
            'selected_id_carrier' => $context->cart->id_carrier,
            'formatted_address' => $formatted_address,
            'async_ui_init' => $async_ui_init,
        );

        if ($this->getMapMarkerIcon()) {
            $fspasc_js['marker_type'] = 'image';
        }

        $this->fshelper->smartyAssign(array(
            'fspasc_css' => $fspasc_css,
            'fspasc_js' => $fspasc_js,
            'fspasc_translated_months' => $this->getTranslatedMonths()
        ));

        return $this->fshelper->smartyFetch('front/css_js_17.tpl', true);
    }

    public function getMapMarkerIcon()
    {
        $module_img_dir = _PS_MODULE_DIR_.$this->name.'/views/img/';
        $module_marker = 'map_marker.png';

        $images = Tools::scandir($module_img_dir, 'png');
        if ($images) {
            foreach ($images as $image) {
                if ($this->fshelper->startsWith($image, 'map_marker-')) {
                    $module_marker = $image;
                    break;
                }
            }
        }

        if (file_exists($module_img_dir.$module_marker)) {
            $module_img_uri = _MODULE_DIR_.$this->name.'/views/img/';
            return $this->context->link->getMediaLink($module_img_uri.$module_marker);
        }

        return '';
    }

    public function getStoresFront()
    {
        if (!self::$stores) {
            $stores_enable = json_decode(Configuration::get('FSPASC_STORES_ENABLE'), true);
            self::$stores = array();
            $collection_store = new PrestaShopCollection('Store');
            if (Configuration::get('FSPASC_STORE_ORDER_NAME')) {
                if ($this->fshelper->isPsMin173()) {
                    $stores = $collection_store->orderBy('l.name')->getResults();
                } else {
                    $stores = $collection_store->orderBy('name')->getResults();
                }
            } else {
                $stores = $collection_store->getResults();
            }

            if ($stores) {
                $days_translations = array(
                    0 => $this->l('Monday'),
                    1 => $this->l('Tuesday'),
                    2 => $this->l('Wednesday'),
                    3 => $this->l('Thursday'),
                    4 => $this->l('Friday'),
                    5 => $this->l('Saturday'),
                    6 => $this->l('Sunday'),
                );

                foreach ($stores as $store) {
                    if (in_array($store->id, $stores_enable)) {
                        $store_hours = json_decode($this->fshelper->getLangValue($store->hours), true);
                        foreach ($store_hours as $key => $store_hour) {
                            $store_hours[$key] = implode(' | ', $store_hour);
                        }

                        $tmp = array();
                        $tmp['id_country'] = $store->id_country;
                        $tmp['id_store'] = $store->id;
                        $tmp['name'] = $this->fshelper->getLangValue($store->name);
                        $tmp['address1'] = $this->fshelper->getLangValue($store->address1);
                        $tmp['address2'] = $this->fshelper->getLangValue($store->address2);
                        $tmp['postcode'] = $store->postcode;
                        $tmp['city'] = $store->city;
                        $tmp['latitude'] = $store->latitude;
                        $tmp['longitude'] = $store->longitude;
                        $tmp['hours'] = $store_hours;
                        $tmp['phone'] = $store->phone;
                        $tmp['state_iso_code'] = '';
                        $tmp['state_name'] = '';

                        if ($store->id_state) {
                            $state = new State($store->id_state);
                            $tmp['state_iso_code'] = $state->iso_code;
                            $tmp['state_name'] = $state->name;
                        }

                        $tmp['img'] = '';
                        $img_size = Configuration::get('FSPASC_INFO_WINDOW_IMAGE');
                        if ($img_size && file_exists(_PS_STORE_IMG_DIR_.(int)$store->id.'-'.$img_size.'.jpg')) {
                            $img_path = _THEME_STORE_DIR_.(int)$store->id.'-'.$img_size.'.jpg';
                            $tmp['img'] = $this->context->link->getMediaLink($img_path);
                        }

                        $this->fshelper->smartyAssign(array(
                            'fspasc_store' => $tmp,
                            'fspasc_days_translations' => $days_translations,
                            'fspasc_get_direction_url' => 'https://maps.google.com/maps?saddr=&daddr=('.
                                $tmp['latitude'].','.$tmp['longitude'].')',
                        ));

                        $is_front_controller = true;
                        if ($this->context->controller instanceof AdminController ||
                            $this->context->controller instanceof AdminControllerCore) {
                            $is_front_controller = false;
                        }

                        if ($is_front_controller) {
                            $tmp['info_box_html'] = $this->fshelper->smartyFetch('front/info_box_17.tpl', true);
                        } else {
                            $tmp['info_box_html'] = $this->fshelper->smartyFetch('front/info_box.tpl');
                        }

                        $tmp['full_address'] = $tmp['address1'];
                        if ($tmp['address2']) {
                            $tmp['full_address'] .= ' '.$tmp['address2'];
                        }
                        $tmp['full_address'] .= ' '.$tmp['city'];
                        if ($tmp['state_iso_code']) {
                            $tmp['full_address'] .= ', '.$tmp['state_iso_code'];
                        }
                        $tmp['full_address'] .= ' '.$tmp['postcode'];

                        self::$stores[$store->id] = $tmp;
                    }
                }
            }
        }

        return self::$stores;
    }

    public function getStores()
    {
        $collection_store = new PrestaShopCollection('Store');
        if (Configuration::get('FSPASC_STORE_ORDER_NAME')) {
            if ($this->fshelper->isPsMin173()) {
                $stores = $collection_store->orderBy('l.name')->getResults();
            } else {
                $stores = $collection_store->orderBy('name')->getResults();
            }
        } else {
            $stores = $collection_store->getResults();
        }

        $result = array();
        if ($stores) {
            foreach ($stores as $store) {
                $tmp = array();
                $tmp['id_store'] = $store->id;
                $tmp['val'] = $store->id;
                $tmp['name'] = $this->fshelper->getLangValue($store->name);
                $tmp['state_iso_code'] = '';
                $tmp['state_name'] = '';

                if ($store->id_state) {
                    $state = new State($store->id_state);
                    $tmp['state_iso_code'] = $state->iso_code;
                    $tmp['state_name'] = $state->name;
                }

                $tmp['full_address'] = $this->fshelper->getLangValue($store->address1);
                if ($this->fshelper->getLangValue($store->address2)) {
                    $tmp['full_address'] .= ' '.$this->fshelper->getLangValue($store->address2);
                }
                $tmp['full_address'] .= ' '.$store->city;
                if ($tmp['state_iso_code']) {
                    $tmp['full_address'] .= ', '.$tmp['state_iso_code'];
                }
                $tmp['full_address'] .= ' '.$store->postcode;

                $tmp['latitude'] = $store->latitude;
                $tmp['longitude'] = $store->longitude;

                $result[$store->id] = $tmp;
            }
        }

        return $result;
    }

    public function getSavedStoreIdByIdCart($id_cart)
    {
        $sql = 'SELECT id_store FROM `'._DB_PREFIX_.'fspickupatstorecarrier_cart` WHERE `id_cart` = '.(int)$id_cart;
        return Db::getInstance()->getValue($sql);
    }

    public function getSavedPickupDateTimeByIdCart($id_cart)
    {
        $sql = 'SELECT date_pickup FROM `'._DB_PREFIX_.'fspickupatstorecarrier_cart` WHERE `id_cart` = '.(int)$id_cart;
        return Db::getInstance()->getValue($sql);
    }

    public function getSelectedStoreId()
    {
        $context = Context::getContext();
        $stores_enable = json_decode(Configuration::get('FSPASC_STORES_ENABLE'), true);
        $sql = 'SELECT id_store FROM `'
            ._DB_PREFIX_.'fspickupatstorecarrier_cart` WHERE `id_cart` = '.(int)$context->cart->id;
        $fspasc_id_store = Db::getInstance()->getValue($sql);
        if (is_numeric($fspasc_id_store) && $fspasc_id_store > 0) {
            if (in_array($fspasc_id_store, $stores_enable)) {
                return $fspasc_id_store;
            }
        }

        $default_id_store = Configuration::get('FSPASC_STORE_DEFAULT');
        if (in_array($default_id_store, $stores_enable)) {
            if (!Configuration::get('FSPASC_FORCE_SELECT_STORE')) {
                $this->setSelectedStoreId($default_id_store);
            }
            return $default_id_store;
        }

        $stores = $this->getStoresFront();
        $default_id_store = min(array_keys($stores));
        if (!Configuration::get('FSPASC_FORCE_SELECT_STORE')) {
            $this->setSelectedStoreId($default_id_store);
        }
        return $default_id_store;
    }

    public function setSelectedStoreId($id_store)
    {
        $context = Context::getContext();
        $sql = 'SELECT id_store FROM `'
            ._DB_PREFIX_.'fspickupatstorecarrier_cart` WHERE `id_cart` = '.(int)$context->cart->id;
        $fspasc_id_store = Db::getInstance()->getValue($sql);
        if ($fspasc_id_store) {
            $sql = 'UPDATE `'._DB_PREFIX_.'fspickupatstorecarrier_cart` SET `id_store` = '
                .(int)$id_store.' WHERE `id_cart` = '.(int)$context->cart->id;
            Db::getInstance()->Execute($sql);
        } else {
            $sql = 'INSERT IGNORE INTO `'._DB_PREFIX_.'fspickupatstorecarrier_cart` (`id_cart`, `id_store`)
                VALUES ('.(int)$context->cart->id.', '.(int)$id_store.')';
            Db::getInstance()->Execute($sql);
        }

        return true;
    }

    public function getSelectedPickupDateTime()
    {
        $context = Context::getContext();
        $calendar_data = $this->getCalendarData($this->getSelectedStoreId());
        $next_available_date_time = $calendar_data['selected_date'].' '.$calendar_data['selected_time'];
        $sql = 'SELECT date_pickup FROM `'
            ._DB_PREFIX_.'fspickupatstorecarrier_cart` WHERE `id_cart` = '.(int)$context->cart->id;
        $fspasc_pickup_date_time = Db::getInstance()->getValue($sql);
        if (Validate::isDate($fspasc_pickup_date_time) && $next_available_date_time <= $fspasc_pickup_date_time) {
            return $fspasc_pickup_date_time;
        }

        return $calendar_data['selected_date'].' '.$calendar_data['selected_time'];
    }

    public function setSelectedPickupDateTime($date_pickup)
    {
        $context = Context::getContext();
        $sql = 'UPDATE `'._DB_PREFIX_.'fspickupatstorecarrier_cart` SET `date_pickup` = \''
            .pSQL($date_pickup).'\' WHERE `id_cart` = '.(int)$context->cart->id;
        Db::getInstance()->Execute($sql);

        return true;
    }

    public function getCarrierHistory($id_carrier)
    {
        $carrier = new Carrier($id_carrier);
        $sql = 'SELECT id_carrier FROM `'
            ._DB_PREFIX_.'carrier` WHERE `id_reference` = \''.pSQL($carrier->id_reference).'\'';
        $carrier_histories = Db::getInstance()->executeS($sql);
        $carrier_history_ids = array();
        if ($carrier_histories) {
            foreach ($carrier_histories as $carrier_history) {
                $carrier_history_ids[] = $carrier_history['id_carrier'];
            }
        }
        return $carrier_history_ids;
    }

    public function getIdAddressByIdStore($id_store)
    {
        $store = new Store($id_store);
        if (!$store->id) {
            return false;
        }
        $id_hash = sha1(json_encode($store));
        $sql_cache = 'SELECT * FROM `'._DB_PREFIX_
            .'fspickupatstorecarrier_cache` WHERE `id_hash` = \''.pSQL($id_hash).'\'';
        $cache = Db::getInstance()->getRow($sql_cache);
        if ($cache) {
            return $cache['id_address'];
        }

        $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));
        $address = new FsPickupAtStoreCarrierAddress();
        $address->id_country = $store->id_country;
        $address->id_customer = 0;
        $address->id_manufacturer = 0;
        $address->id_supplier = 0;
        $address->id_warehouse = 0;
        $address->alias = $carrier->name;
        if (Tools::strlen($address->alias) > 32) {
            $address->alias = Tools::substr($address->alias, 0, 27).'...';
        }
        $address->company = $this->fshelper->getLangValue($store->name);
        $address->lastname = $this->name;
        $address->firstname = $this->name;
        $address->address1 = $this->fshelper->getLangValue($store->address1);
        $address->address2 = $this->fshelper->getLangValue($store->address2);
        $address->postcode = $store->postcode;
        $address->city = $store->city;
        $address->id_state = $store->id_state;
        $address->phone = $store->phone;
        $address->phone_mobile = '.';
        $address->vat_number = null;
        $address->dni = null;
        $address->active = 1;
        $address->save();
        $id_address = $address->id;

        $sql_update = 'UPDATE `'._DB_PREFIX_.'address` SET `lastname` = \'\', `firstname` = \'\',
        `phone_mobile` = \'\' WHERE `id_address` = \''.pSQL($id_address).'\'';
        Db::getInstance()->execute($sql_update);

        $sql_insert = 'INSERT INTO `'._DB_PREFIX_.'fspickupatstorecarrier_cache` (`id_hash`, `id_address`) ';
        $sql_insert .= 'VALUES (\''.pSQL($id_hash).'\', \''.pSQL($id_address).'\')';
        Db::getInstance()->execute($sql_insert);

        return $id_address;
    }

    public function getIdCarrier()
    {
        $carrier = Carrier::getCarrierByReference(Configuration::get('FSPASC_ID_CARRIER'));
        if (Validate::isLoadedObject($carrier)) {
            return $carrier->id;
        }
        return 0;
    }

    public function savePickupInfoAsMessage($fspasc, $order)
    {
        $id_address_delivery = $this->getIdAddressByIdStore($fspasc->id_store);
        if ($id_address_delivery) {
            $delivery = new Address((int)$id_address_delivery);
            $delivery_text = $this->l('Selected Store:').' ';
            $delivery_text .= $this->getFormattedAddress(
                $delivery,
                ', '
            );

            if (Configuration::get('FSPASC_DATE_ENABLE')) {
                $delivery_text .= "\n\n";
                $delivery_text .= $this->l('Selected Date:').' ';
                $delivery_text .= strftime('%Y-%m-%d', strtotime($fspasc->date_pickup));

                if (Configuration::get('FSPASC_TIME_ENABLE')) {
                    $delivery_text .= "\n\n";
                    $delivery_text .= $this->l('Selected Time:').' ';
                    $delivery_text .= strftime('%H:%M', strtotime($fspasc->date_pickup));
                }
            }

            $message = new Message();
            $message->message = $delivery_text;
            $message->id_order = $order->id;
            $message->private = true;
            $message->save();
        }
    }

    public function fixCustomizationAddressForOrder($order)
    {
        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'customization` SET `id_address_delivery` = '.pSQL($order->id_address_delivery)
            .' WHERE `id_cart` = '.pSQL($order->id_cart)
        );
    }

    public function hasGoogleMapsApiKey()
    {
        return (bool)Configuration::get('FSPASC_MAP_API_KEY');
    }

    public function getImageSizesByType($type)
    {
        $image_types = ImageType::getImagesTypes($type);
        $image_size_selector = array();
        if ($image_types) {
            foreach ($image_types as $image_type) {
                $image_size_selector[] = array(
                    'id' => $image_type['name'],
                    'name' => $image_type['name'].' - ('.$image_type['width'].' x '.$image_type['width'].' pixel)'
                );
            }
        }

        return $image_size_selector;
    }

    #################### MAILING FUNCTIONS ####################

    public function useMailAlerts($params)
    {
        $mailalerts_enabled = Module::isEnabled('ps_emailalerts');

        if ($mailalerts_enabled) {
            $mailalerts = Module::getInstanceByName('ps_emailalerts');
            $mailalerts->hookActionValidateOrder($params);
        }
    }

    public function sendOrderEmailToStore($params, $fspasc)
    {
        $store = new Store($fspasc->id_store);
        if (!trim($store->email)) {
            return;
        }

        $id_lang = (int)$this->context->language->id;
        $id_shop = (int)$this->context->shop->id;
        $currency = $params['currency'];
        $order = $params['order'];
        $customer = $params['customer'];
        $configuration = Configuration::getMultiple(
            array(
                'PS_SHOP_EMAIL',
                'PS_MAIL_METHOD',
                'PS_MAIL_SERVER',
                'PS_MAIL_USER',
                'PS_MAIL_PASSWD',
                'PS_SHOP_NAME',
                'PS_MAIL_COLOR'
            ),
            $id_lang,
            null,
            $id_shop
        );
        $delivery = new Address((int)$order->id_address_delivery);
        $invoice = new Address((int)$order->id_address_invoice);
        $order_date_text = Tools::displayDate($order->date_add);
        $carrier = new Carrier((int)$order->id_carrier);
        $message = $this->getAllOrderMessages($order->id);

        if (!$message || empty($message)) {
            $message = $this->l('No message');
        }

        $items_table = '';

        $products = $order->getProducts();
        $customized_data = Product::getAllCustomizedDatas((int)$params['cart']->id);
        Product::addCustomizationPrice($products, $customized_data);
        foreach ($products as $key => $product) {
            $id_product = $product['product_id'];
            $id_product_attribute = $product['product_attribute_id'];

            $unit_price = $product['product_price_wt'];
            if (Product::getTaxCalculationMethod($customer->id) == PS_TAX_EXC) {
                $unit_price = $product['product_price'];
            }

            $customization_text = '';
            if (isset($customized_data[$id_product][$id_product_attribute])) {
                $customizations = array_pop($customized_data[$id_product][$id_product_attribute]);
                foreach ($customizations as $customization) {
                    if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                        foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                            $customization_text .= $text['name'].': '.$text['value'].'<br />';
                        }
                    }

                    if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                        $customization_text .= count($customization['datas'][Product::CUSTOMIZE_FILE]).
                            ' '.$this->l('image(s)').'<br />';
                    }

                    $customization_text .= '---<br />';
                }
                if (method_exists('Tools', 'rtrimString')) {
                    $customization_text = Tools::rtrimString($customization_text, '---<br />');
                } else {
                    $customization_text = preg_replace('/---<br \/>$/', '', $customization_text);
                }
            }

            $url = $this->context->link->getProductLink($id_product);

            $attributes_small = '';
            if (isset($product['attributes_small'])) {
                $attributes_small = $product['attributes_small'];
            }

            $this->fshelper->smartyAssign(array(
                'fspasc_mail_color' => ($key % 2 ? '#DDE2E6' : '#EBECEE'),
                'fspasc_mail_product_reference' => $product['product_reference'],
                'fspasc_mail_url' => $url,
                'fspasc_mail_product_name' => $product['product_name'],
                'fspasc_mail_attributes_small' => $attributes_small,
                'fspasc_mail_customization_text' => $customization_text,
                'fspasc_mail_unit_price' => Tools::displayPrice($unit_price, $currency, false),
                'fspasc_mail_quantity' => (int)$product['product_quantity'],
                'fspasc_mail_total_price' => Tools::displayPrice(
                    ($unit_price * $product['product_quantity']),
                    $currency,
                    false
                )
            ));

            $items_table .= $this->fshelper->smartyFetch('hook/email_product_17.tpl', true);
        }

        foreach ($order->getCartRules() as $discount) {
            $this->fshelper->smartyAssign(array(
                'fspasc_mail_discount_name' => $discount['name'],
                'fspasc_mail_discount_price' => Tools::displayPrice($discount['value'], $currency, false)
            ));

            $items_table .= $this->fshelper->smartyFetch('hook/email_voucher_17.tpl', true);
        }


        $delivery_state = $invoice_state = new State();
        if ($delivery->id_state) {
            $delivery_state = new State((int)$delivery->id_state);
        }
        if ($invoice->id_state) {
            $invoice_state = new State((int)$invoice->id_state);
        }

        if (Product::getTaxCalculationMethod($customer->id) == PS_TAX_EXC) {
            $total_products = $order->getTotalProductsWithoutTaxes();
        } else {
            $total_products = $order->getTotalProductsWithTaxes();
        }

        $order_state = $params['orderStatus'];

        $selected_date_time = array(
            'fspasc_mail_date_selected' => false,
            'fspasc_mail_time_selected' => false
        );

        if (Configuration::get('FSPASC_DATE_ENABLE')) {
            $selected_date_time['fspasc_mail_date_selected'] = strftime('%Y-%m-%d', strtotime($fspasc->date_pickup));

            if (Configuration::get('FSPASC_TIME_ENABLE')) {
                $selected_date_time['fspasc_mail_time_selected'] = strftime('%H:%M', strtotime($fspasc->date_pickup));
            }
        }

        $this->fshelper->smartyAssign($selected_date_time);
        $selected_date_time_block_html = $this->fshelper->smartyFetch('hook/email_date_time_17.tpl', true);

        // Filling-in vars for email
        $template_vars = array(
            '{selected_date_time_block_html}' => $selected_date_time_block_html,
            '{firstname}' => $customer->firstname,
            '{lastname}' => $customer->lastname,
            '{email}' => $customer->email,
            '{delivery_block_txt}' => $this->getFormattedAddress($delivery, "\n"),
            '{invoice_block_txt}' => $this->getFormattedAddress($invoice, "\n"),
            '{delivery_block_html}' => $this->getFormattedAddress(
                $delivery,
                ', ',
                array(
                    'company' => '<span style="font-weight:bold;">%s</span>',
                    'address1' => '<br />%s',
                    'city' => '%s<br />'
                )
            ),
            '{invoice_block_html}' => $this->getFormattedAddress(
                $invoice,
                '<br />',
                array(
                    'firstname' => '<span style="font-weight:bold;">%s</span>',
                    'lastname' => '<span style="font-weight:bold;">%s</span>'
                )
            ),
            '{delivery_company}' => $delivery->company,
            '{delivery_firstname}' => $delivery->firstname,
            '{delivery_lastname}' => $delivery->lastname,
            '{delivery_address1}' => $delivery->address1,
            '{delivery_address2}' => $delivery->address2,
            '{delivery_city}' => $delivery->city,
            '{delivery_postal_code}' => $delivery->postcode,
            '{delivery_country}' => $delivery->country,
            '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
            '{delivery_phone}' => $delivery->phone ? $delivery->phone : $delivery->phone_mobile,
            '{delivery_other}' => $delivery->other,
            '{invoice_company}' => $invoice->company,
            '{invoice_firstname}' => $invoice->firstname,
            '{invoice_lastname}' => $invoice->lastname,
            '{invoice_address2}' => $invoice->address2,
            '{invoice_address1}' => $invoice->address1,
            '{invoice_city}' => $invoice->city,
            '{invoice_postal_code}' => $invoice->postcode,
            '{invoice_country}' => $invoice->country,
            '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
            '{invoice_phone}' => $invoice->phone ? $invoice->phone : $invoice->phone_mobile,
            '{invoice_other}' => $invoice->other,
            '{order_name}' => $order->reference,
            '{order_status}' => $order_state->name,
            '{shop_name}' => $configuration['PS_SHOP_NAME'],
            '{date}' => $order_date_text,
            '{carrier}' => (($carrier->name == '0') ? $configuration['PS_SHOP_NAME'] : $carrier->name),
            '{payment}' => Tools::substr($order->payment, 0, 32),
            '{items}' => $items_table,
            '{total_paid}' => Tools::displayPrice($order->total_paid, $currency),
            '{total_products}' => Tools::displayPrice($total_products, $currency),
            '{total_discounts}' => Tools::displayPrice($order->total_discounts, $currency),
            '{total_shipping}' => Tools::displayPrice($order->total_shipping, $currency),
            '{total_tax_paid}' => Tools::displayPrice(
                ($order->total_products_wt - $order->total_products) +
                ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl),
                $currency,
                false
            ),
            '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $currency),
            '{currency}' => $currency->sign,
            '{gift}' => (bool)$order->gift,
            '{gift_message}' => $order->gift_message,
            '{message}' => $message
        );

        // Shop iso
        $iso = Language::getIsoById((int)Configuration::get('PS_LANG_DEFAULT'));

        $merchant_mails = explode(',', $store->email);
        foreach ($merchant_mails as $merchant_mail) {
            // Default language
            $mail_id_lang = $id_lang;
            $mail_iso = $iso;

            // Use the merchant lang if he exists as an employee
            $results = Db::getInstance()->getRow('
				SELECT `id_lang` FROM `'._DB_PREFIX_.'employee`
				WHERE `email` = \''.pSQL($merchant_mail).'\'
			');
            if ($results) {
                $user_iso = Language::getIsoById((int)$results['id_lang']);
                if ($user_iso) {
                    $mail_id_lang = (int)$results['id_lang'];
                    $mail_iso = $user_iso;
                }
            }

            $dir_mail = false;
            if (file_exists(dirname(__FILE__).'/mails/'.$mail_iso.'/new_order.txt') &&
                file_exists(dirname(__FILE__).'/mails/'.$mail_iso.'/new_order.html')) {
                $dir_mail = dirname(__FILE__).'/mails/';
            }

            if (file_exists(_PS_MAIL_DIR_.$mail_iso.'/new_order.txt') &&
                file_exists(_PS_MAIL_DIR_.$mail_iso.'/new_order.html')) {
                $dir_mail = _PS_MAIL_DIR_;
            }

            if ($dir_mail) {
                Mail::Send(
                    $mail_id_lang,
                    'new_order',
                    sprintf(Mail::l('New order : #%d - %s', $mail_id_lang), $order->id, $order->reference),
                    $template_vars,
                    $merchant_mail,
                    null,
                    $configuration['PS_SHOP_EMAIL'],
                    $configuration['PS_SHOP_NAME'],
                    null,
                    null,
                    $dir_mail,
                    null,
                    $id_shop
                );
            }
        }
    }

    public function getAllOrderMessages($id)
    {
        $messages = Db::getInstance()->executeS(
            'SELECT `message` FROM `'._DB_PREFIX_.'message` WHERE `id_order` = '.(int)$id.' ORDER BY `id_message` ASC'
        );
        $result = array();
        foreach ($messages as $message) {
            $result[] = $message['message'];
        }

        return implode('<br/>', $result);
    }

    public function getFormattedAddress(Address $address, $line_sep, $fields_style = array())
    {
        return AddressFormat::generateAddress($address, array('avoid' => array()), $line_sep, ' ', $fields_style);
    }

    #################### CALENDAR FUNCTIONS ####################

    public function getSoreOpenHours($id_store)
    {
        $store = new Store($id_store);
        $open_hours_prepare = json_decode($this->fshelper->getLangValue($store->hours), true);
        foreach ($open_hours_prepare as $key => $open_hour_prepare) {
            $open_hours_prepare[$key] = implode(' | ', $open_hour_prepare);
        }
        $open_hours_prepare = array_combine(range(1, count($open_hours_prepare)), array_values($open_hours_prepare));
        if (isset($open_hours_prepare[7])) {
            $open_hours_prepare[0] = $open_hours_prepare[7];
        }
        ksort($open_hours_prepare);

        $open_hours = array();
        for ($day_of_week = 0; $day_of_week <= 7; $day_of_week++) {
            $open_hours[$day_of_week] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

            if (isset($open_hours_prepare[$day_of_week])) {
                $open_hour = $open_hours_prepare[$day_of_week];
                $open_hour = str_replace('-', ' - ', $open_hour);
                $open_hour_array = explode(' ', $open_hour);
                if (count($open_hour_array) > 1) {
                    $open_hour_array_tmp = array();
                    foreach ($open_hour_array as $open_hour_part) {
                        if (is_numeric(preg_replace('/[^0-9]/', '', $open_hour_part))) {
                            if (stripos($open_hour_part, 'pm')) {
                                $open_hour_part = (int)preg_replace('/[^0-9]/', '', $open_hour_part)+1200;
                            } else {
                                $open_hour_part = (int)preg_replace('/[^0-9]/', '', $open_hour_part);
                            }
                            $open_hour_part = (int)($open_hour_part/100);
                            if ($open_hour_part > 23) {
                                $open_hour_part = 23;
                            }
                            $open_hour_array_tmp[] = $open_hour_part;
                        }
                    }
                    asort($open_hour_array_tmp);
                    $open_hour_intervals = array_values($open_hour_array_tmp);

                    if (count($open_hour_intervals) > 1) {
                        foreach ($open_hours[$day_of_week] as $hour => &$is_open) {
                            foreach (array_keys($open_hour_intervals) as $key) {
                                if ($key % 2 == 0) {
                                    $from = $open_hour_intervals[$key];
                                    $to = 0;
                                    if (isset($open_hour_intervals[$key+1])) {
                                        $to = $open_hour_intervals[$key+1];
                                    }

                                    if ($from <= $hour && $hour < $to) {
                                        $is_open = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $open_hours;
    }

    public function getWeeklyOpens($id_store)
    {
        $store_weekly_opens = array(
            0 => false,
            1 => false,
            2 => false,
            3 => false,
            4 => false,
            5 => false,
            6 => false,
            7 => false,
        );

        $open_hours = $this->getSoreOpenHours($id_store);
        foreach ($open_hours as $day_of_week => $open_hour) {
            foreach ($open_hour as $is_open) {
                if ($is_open) {
                    $store_weekly_opens[$day_of_week] = true;
                    break;
                }
            }
        }

        return $store_weekly_opens;
    }

    public function getMinWaitingHours()
    {
        $min_hours = (int)Configuration::get('FSPASC_PICKUP_MIN_HOURS', 0);
        if (!is_numeric($min_hours)) {
            $min_hours = 0;
        }

        if (is_numeric($min_hours) && $min_hours > 23) {
            $min_hours = 23;
        }

        if (!Configuration::get('FSPASC_TIME_ENABLE', 0)) {
            $min_hours = 0;
        }

        return $min_hours+1;
    }

    public function generateWeeklyOpenHours($open_hours)
    {
        $weekly_open_hours = array();
        for ($day_of_week = 0; $day_of_week <= 7; $day_of_week++) {
            $weekly_open_hours[$day_of_week]['id'] = 'day'.$day_of_week;
            $weekly_open_hours[$day_of_week]['class'] = '';

            $found_enabled = false;
            for ($hour = 0; $hour <= 23; $hour++) {
                $disabled = false;
                $current = false;

                if (!isset($open_hours[$day_of_week][$hour])) {
                    $disabled = true;
                } elseif (!$open_hours[$day_of_week][$hour]) {
                    $disabled = true;
                }

                if (!$found_enabled) {
                    if (!$disabled) {
                        $found_enabled = true;
                        $current = true;
                    }
                }

                $weekly_open_hours[$day_of_week]['hours'][$hour] = array(
                    'id' => 'd'.$day_of_week.'h'.$hour,
                    'text' => $hour.':00',
                    'full_time' => str_pad($hour, 2, '0', STR_PAD_LEFT).':00:00',
                    'disabled' => $disabled,
                    'current' => $current,
                    'hour' => $hour
                );
            }
        }

        return $weekly_open_hours;
    }

    public function getNextAvailableDateFromWeeklyOpens($store_weekly_opens)
    {
        $current_hour = (int)strftime('%H');
        $waiting_days = Configuration::get('FSPASC_PICKUP_MIN_DAYS', 0);
        $cut_off_time = Configuration::get('FSPASC_PICKUP_CUT_OFF_TIME', 0);
        if ($cut_off_time) {
            if ($current_hour >= $cut_off_time) {
                $waiting_days++;
            }
        }

        if ($waiting_days) {
            $current_day_of_week = (int)strftime('%w');
            $delay_days = 0;
            while ($waiting_days) {
                $current_day_of_week++;
                if ($current_day_of_week > 6) {
                    $current_day_of_week = 0;
                }

                if ($store_weekly_opens[$current_day_of_week]) {
                    $waiting_days--;
                }

                //Infinite loop safety
                $delay_days++;
                if ($delay_days > 100) {
                    $waiting_days = 0;
                }
            }
            return strftime('%Y-%m-%d', strtotime('+'.$delay_days.'days'));
        } else {
            $current_day_of_week = (int)strftime('%w');
            $day_founded = false;
            $delay_days = 0;
            while (!$day_founded) {
                if ($store_weekly_opens[$current_day_of_week]) {
                    $day_founded = true;
                } else {
                    $delay_days++;
                }

                $current_day_of_week++;
                if ($current_day_of_week > 6) {
                    $current_day_of_week = 0;
                }

                //Infinite loop safety
                if ($delay_days > 100) {
                    $day_founded = true;
                }
            }
            return strftime('%Y-%m-%d', strtotime('+'.$delay_days.'days'));
        }
    }

    public function getCalendarData($id_store)
    {
        $response = array();
        $current_hour = (int)strftime('%H');
        $open_hours = $this->getSoreOpenHours($id_store);
        $weekly_open_hours = $this->generateWeeklyOpenHours($open_hours);
        $store_weekly_opens = $this->getWeeklyOpens($id_store);
        $min_hours = $this->getMinWaitingHours();
        $next_available_date = $this->getNextAvailableDateFromWeeklyOpens($store_weekly_opens);

        $calendar = array();
        $next_available_date_time = strtotime($next_available_date);
        $min_month = (int)strftime('%m', $next_available_date_time);
        $min_day = (int)strftime('%d', $next_available_date_time);

        $max_days = Configuration::get('FSPASC_PICKUP_MAX_DAYS');
        if (!is_numeric($max_days) || $max_days == 0) {
            $max_days = 100;
        }

        $valid_days_count = 0;
        $need_more_month = true;
        $month_diff = 0;
        while ($need_more_month) {
            $year = (int)strftime('%Y', strtotime($next_available_date));
            $month = (int)strftime('%m', strtotime($next_available_date))+$month_diff;
            if ($month > 12) {
                $year += 1;
                $month -= 12;
            }
            $month_first_day_time = strtotime($year.'-'.$month.'-01');
            $week = (int)strftime('%W', $month_first_day_time);

            $month_first_day_of_week = (int)strftime('%w', $month_first_day_time);
            if ($month_first_day_of_week === 0) {
                $month_first_day_of_week = 7;
            }

            if ($month_first_day_of_week > 1) {
                $start_date = strftime(
                    '%Y-%m-%d',
                    strtotime('-'.($month_first_day_of_week-1).'days', $month_first_day_time)
                );
            } else {
                $start_date = strftime('%Y-%m-01', $month_first_day_time);
            }

            $calendar[$month]['class'] = ($min_month == $month)?'active':'';
            $calendar[$month]['year'] = $year;
            $calendar[$month]['num'] = $month;
            $calendar[$month]['panel_id'] = $year.'-'.$month;
            $calendar[$month]['name'] = $this->getTranslatedMonth($month);

            $start_date_time = strtotime($start_date);

            $day_diff = 0;
            for ($week_diff = 0; $week_diff <= 5; $week_diff++) {
                $week_has_active = false;
                $week_month = $month;
                for ($day_of_week = 1; $day_of_week <= 7; $day_of_week++) {
                    $m = (int)strftime('%m', strtotime('+'.$day_diff.'days', $start_date_time));
                    $d = (int)strftime('%d', strtotime('+'.$day_diff.'days', $start_date_time));
                    $disabled = !$store_weekly_opens[$day_of_week];
                    if ($valid_days_count >= $max_days) {
                        $disabled = true;
                        $need_more_month = false;
                    }

                    if ($m == $min_month && $d < $min_day) {
                        $disabled = true;
                    }

                    if ($m > $month || $m < $month) {
                        $disabled = true;
                    }

                    if (!$disabled) {
                        //TODO manual exceptions
                    }

                    if (!$disabled) {
                        $valid_days_count++;
                    }

                    $current = false;
                    if ($next_available_date == strftime(
                        '%Y-%m-%d',
                        strtotime('+'.$day_diff.'days', $start_date_time)
                    )) {
                        if ($disabled && !$valid_days_count) {
                            $next_available_date = strftime(
                                '%Y-%m-%d',
                                strtotime('+1 day', strtotime($next_available_date))
                            );
                        } else {
                            $current = true;
                        }
                    }

                    $calendar[$month]['weeks'][$week+$week_diff][$day_of_week] = array(
                        'date' => strftime('%Y-%m-%d', strtotime('+'.$day_diff.'days', $start_date_time)),
                        'text' => (int)strftime('%d', strtotime('+'.$day_diff.'days', $start_date_time)),
                        'disabled' => $disabled,
                        'current' => $current
                    );

                    $day_diff++;

                    $week_month = (int)strftime('%m', strtotime('+'.$day_diff.'days', $start_date_time));
                    if (!$disabled) {
                        $week_has_active = true;
                    }
                }

                if (!$week_has_active && ($month < $week_month)) {
                    unset($calendar[$month]['weeks'][$week+$week_diff]);
                    break;
                }
            }

            $month_diff++;

            if ($month_diff > 10) {
                $need_more_month = false;
            }
        }

        $month_panel_ids = array();
        foreach ($calendar as $month) {
            $month_panel_ids[] = $month['panel_id'];
        }

        foreach ($calendar as $key => $month) {
            $year_num = (int)$month['year'];
            $month_num = (int)$month['num'];
            $calendar[$key]['prev'] = 0;
            $calendar[$key]['next'] = 0;

            $prev_year = $year_num;
            $prev_month = $month_num-1;
            if ($prev_month < 1) {
                $prev_month = 12;
                $prev_year--;
            }
            if (in_array($prev_year.'-'.$prev_month, $month_panel_ids)) {
                $calendar[$key]['prev'] = $prev_year.'-'.$prev_month;
            }

            $next_year = $year_num;
            $next_month = $month_num+1;
            if ($next_month > 12) {
                $next_month = 1;
                $next_year++;
            }
            if (in_array($next_year.'-'.$next_month, $month_panel_ids)) {
                $calendar[$key]['next'] = $next_year.'-'.$next_month;
            }
        }

        $threshold_hour = $current_hour+$min_hours;
        if ($next_available_date == strftime('%Y-%m-%d')) {
            $current_day_of_week = (int)strftime('%w', strtotime($next_available_date));
            $maybe_today = $weekly_open_hours[$current_day_of_week];
            $has_available_day = false;
            foreach ($maybe_today['hours'] as &$hour) {
                if ($threshold_hour >= $hour['hour']) {
                    $hour['disabled'] = true;
                }

                if (!$hour['disabled']) {
                    $has_available_day = true;
                }
            }
            if (!$has_available_day) {
                $old_available_date = $next_available_date;
                $next_available_date = strftime('%Y-%m-%d', strtotime('+1day', strtotime($next_available_date)));
                foreach ($calendar as $month_num => $month) {
                    foreach ($month['weeks'] as $week_num => $week) {
                        foreach ($week as $day_of_week => $day) {
                            if ($day['date'] == $next_available_date) {
                                if (!$calendar[$month_num]['weeks'][$week_num][$day_of_week]['disabled']) {
                                    $calendar[$month_num]['weeks'][$week_num][$day_of_week]['current'] = true;
                                    $has_available_day = true;
                                } else {
                                    if (!$has_available_day) {
                                        $next_available_date = strftime(
                                            '%Y-%m-%d',
                                            strtotime('+1day', strtotime($next_available_date))
                                        );
                                    }
                                }
                            }
                            if ($day['date'] == $old_available_date) {
                                $calendar[$month_num]['weeks'][$week_num][$day_of_week]['disabled'] = true;
                                $calendar[$month_num]['weeks'][$week_num][$day_of_week]['current'] = false;
                            }
                        }
                    }
                }
            }
        }

        $response['selected_date'] = strftime('%Y-%m-%d', strtotime($next_available_date));
        if ($threshold_hour > 23) {
            $threshold_hour -= 24;
        }

        $current_day_of_week = (int)strftime('%w', strtotime($next_available_date));
        $today = $weekly_open_hours[$current_day_of_week];
        $today['id'] = 'today';
        $today['class'] = 'active';
        $found_enabled = false;
        foreach ($today['hours'] as &$hour) {
            if ($next_available_date == strftime('%Y-%m-%d')) {
                if ($threshold_hour > $hour['hour']) {
                    $hour['disabled'] = true;
                    $hour['current'] = false;
                }
            }

            $hour['id'] = 't'. $hour['id'];

            if ($next_available_date == strftime('%Y-%m-%d')) {
                if ($current_hour >= $hour['hour']) {
                    $hour['disabled'] = true;
                }
            }

            if (!$found_enabled) {
                if (!$hour['disabled']) {
                    $found_enabled = true;
                    $hour['current'] = true;
                    $response['selected_time'] = $hour['full_time'];
                }
            }
        }

        if (!Configuration::get('FSPASC_TIME_ENABLE', 0)) {
            $response['selected_time'] = '00:00:00';
        }

        if (!isset($response['selected_time'])) {
            $response['selected_time'] = '00:00:00';
        }

        $weekly_open_hours['today'] = $today;
        $response['times'] = $weekly_open_hours;
        $response['dates'] = $calendar;
        return $response;
    }

    public function getTranslatedMonths()
    {
        $months = array(
            1 => $this->l('January'),
            2 => $this->l('February'),
            3 => $this->l('March'),
            4 => $this->l('April'),
            5 => $this->l('May'),
            6 => $this->l('June'),
            7 => $this->l('July'),
            8 => $this->l('August'),
            9 => $this->l('September'),
            10 => $this->l('October'),
            11 => $this->l('November'),
            12 => $this->l('December'),
        );

        return $months;
    }

    public function getTranslatedMonth($month)
    {
        $months = $this->getTranslatedMonths();

        if (isset($months[$month])) {
            return $months[$month];
        }
        return '';
    }

    public function formatDate($date, $format = '')
    {
        if (!$format) {
            $format = Configuration::get(
                'FSPASC_DATE_TIME_FORMAT',
                $this->context->language->id
            );
        }

        $year = (int)strftime('%Y', strtotime($date));
        $month = $this->getTranslatedMonth((int)strftime('%m', strtotime($date)));
        $day = strftime('%d', strtotime($date));
        $time_12 = strftime('%l:%M %p', strtotime($date));
        $time_24 = strftime('%k:%M', strtotime($date));

        return str_replace(
            array('%Y', '%M', '%D', '%T12', '%T24'),
            array($year, $month, $day, $time_12, $time_24),
            $format
        );
    }

    #################### OVERRIDE ####################

    public function orderInvoiceGetByDeliveryDateInterval($items)
    {
        $items_filtered = array();
        $fspasc_id_carrier = Tools::getValue('fspasc_id_carrier', 0);
        $fspasc_id_store = Tools::getValue('fspasc_id_store', 0);

        foreach ($items as $item) {
            if ($fspasc_id_carrier) {
                $carrier_history = $this->getCarrierHistory($fspasc_id_carrier);
                $order = new Order($item->id_order);
                if (in_array($order->id_carrier, $carrier_history)) {
                    if ($fspasc_id_store) {
                        $fspickupatstorecarrier = FsPickupAtStoreCarrierModel::getByIdOrder($item->id_order);
                        if ($fspasc_id_store == $fspickupatstorecarrier->id_store) {
                            $items_filtered[] = $item;
                        }
                    } else {
                        $items_filtered[] = $item;
                    }
                }
            } else {
                $items_filtered[] = $item;
            }
        }

        return $items_filtered;
    }
}
