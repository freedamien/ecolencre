<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierHelper
{
    /**
     * @var string
     */
    const MODULE_NAME = 'fspickupatstorecarrier';

    /**
     * @var string
     */
    const DEFAULT_TAB_SECTION = 'fspasc_general_tab';

    /**
     * @var FsPickupAtStoreCarrierHelper
     */
    protected static $instance;

    /**
     * @var FsPickupAtStoreCarrier
     */
    protected static $module;

    /**
     * @var bool
     */
    private static $smarty_registered = false;

    /**
     * @var bool
     */
    private static $compatibility_warning_done = array();

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var array
     */
    protected $register_hooks = array();

    /**
     * @var array
     */
    protected $sql_tables = array();

    /**
     * @var array
     */
    protected $default_config = array();

    /**
     * @var array
     */
    protected $front_config;

    /**
     * @var string
     */
    public $tab_section;

    /**
     * @var array
     */
    private $messages = array();

    /**
     * @var bool
     */
    private $save_messages = false;

    public function __construct()
    {
        $this->context = Context::getContext();

        if (!self::$smarty_registered) {
            smartyRegisterFunction(
                $this->context->smarty,
                'modifier',
                'fspascCorrectTheMess',
                array('FsPickupAtStoreCarrierHelper', 'unescapeSmarty'),
                false
            );
            smartyRegisterFunction(
                $this->context->smarty,
                'modifier',
                'fspascJsonEncode',
                'json_encode',
                false
            );
            smartyRegisterFunction(
                $this->context->smarty,
                'block',
                'fspascMinifyCss',
                array('FsPickupAtStoreCarrierHelper', 'minifyCss'),
                false
            );
            self::$smarty_registered = true;
        }
    }

    public function __destruct()
    {
        $this->saveMessagesToFile();
    }

    /**
     * Get a singleton instance of FsPickupAtStoreCarrierHelper object
     *
     * @return FsPickupAtStoreCarrierHelper
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get a singleton instance of Fspayinstore object
     *
     * @return FsPickupAtStoreCarrier
     */
    public function getModule()
    {
        if (!isset(self::$module)) {
            self::$module = Module::getInstanceByName(self::MODULE_NAME);
        }

        return self::$module;
    }

    #################### INSTALL ####################

    /**
     * Called from the module install function
     *
     * 1. Register hooks
     * 2. Set default configuration
     * 3. Create database tables if exist
     *
     * @return bool
     */
    public function install()
    {
        $return = true;
        if (count($this->register_hooks) > 0) {
            foreach ($this->register_hooks as $register_hook) {
                $return = $return && $this->getModule()->registerHook($register_hook);
            }
        }

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if ($this->getDefaultConfig()) {
            foreach ($this->getDefaultConfig() as $key => $value) {
                $return = $return && Configuration::updateValue($key, $value, true);
            }
        }

        $return = $return && $this->installDB();

        return $return;
    }

    private function installDB()
    {
        $return = true;
        if (count($this->sql_tables) > 0) {
            foreach ($this->sql_tables as $table) {
                $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.pSQL($table['name']).'` (';
                $columns = array();
                $primary_keys = array();
                $keys = array();

                foreach ($table['columns'] as $column) {
                    $columns[] = '`'.$column['name'].'` '.$column['params'];

                    if (isset($column['is_primary_key']) && $column['is_primary_key']) {
                        $primary_keys[] = $column['name'];
                    }

                    if (isset($column['is_key']) && $column['is_key']) {
                        $keys[] = 'KEY `'.$column['name'].'` (`'.$column['name'].'`)';
                    }
                }

                $keys = array_merge(array('PRIMARY KEY (`'.implode('`, `', $primary_keys).'`)'), $keys);

                $sql .= implode(', ', array_merge($columns, $keys)). ') ENGINE='.
                    _MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

                $return = $return && Db::getInstance()->execute($sql);
            }
        }

        return $return;
    }

    #################### UNINSTALL ####################

    /**
     * Called from the module uninstall function
     *
     * 1. Remove configuration
     * 2. Drop database tables if exist
     *
     * @return bool
     */
    public function uninstall()
    {
        $return = true;
        if ($this->getDefaultConfig()) {
            foreach ($this->getConfigKeys() as $key) {
                $return = $return && Configuration::deleteByName($key);
            }
        }

        $return = $return && $this->uninstallDB();

        return $return;
    }

    private function uninstallDB()
    {
        $return = true;
        if (count($this->sql_tables) > 0) {
            foreach ($this->sql_tables as $table) {
                $return = $return && $this->dropSqlTable($table['name']);
            }
        }

        return $return;
    }

    #################### HOOK SECTION ####################

    public function addHooks($hooks)
    {
        $this->register_hooks = array_merge($this->register_hooks, $hooks);
    }

    #################### TAB SECTION ####################

    public function getTabSection()
    {
        if ($this->tab_section) {
            return $this->tab_section;
        }
        return self::DEFAULT_TAB_SECTION;
    }

    public function setTabSection($tab_section)
    {
        $this->tab_section = $tab_section;
        return $this;
    }

    #################### SQL HELPERS ####################

    public function addSqlTable($table)
    {
        $this->sql_tables[] = $table;
    }

    public function dropSqlTable($table_name)
    {
        return (bool)Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.pSQL($table_name).'`');
    }

    #################### CONFIG ####################

    public function addDefaultConfig($config)
    {
        $this->default_config = array_merge($this->default_config, $config);
    }

    public function deleteDefaultConfig($config_name)
    {
        if (isset($this->default_config[$config_name])) {
            unset($this->default_config[$config_name]);
        }
    }

    public function getDefaultConfig()
    {
        return $this->default_config;
    }

    public function getConfigKeys()
    {
        return array_keys($this->getDefaultConfig());
    }

    public function getConfigKeysMultilang()
    {
        $multilangual_option_keys = array();
        foreach ($this->getDefaultConfig() as $key => $value) {
            if (is_array($value)) {
                $multilangual_option_keys[] = $key;
            }
        }
        return $multilangual_option_keys;
    }

    public function getConfig($key, $id_lang = null, $id_shop_group = null, $id_shop = null, $default = false)
    {
        return Configuration::get($key, $id_lang, $id_shop_group, $id_shop, $default);
    }

    public function getConfigMultilang($key, $id_shop_group = null, $id_shop = null, $default = '')
    {
        $languages = Language::getLanguages(false);
        $results_array = array();
        foreach ($languages as $language) {
            $results_array[$language['id_lang']] = Configuration::get(
                $key,
                $language['id_lang'],
                $id_shop_group,
                $id_shop,
                $default
            );
        }
        return $results_array;
    }

    public function getAdminConfig()
    {
        $forms_fields_value = Configuration::getMultiple($this->getConfigKeys());
        if ($this->getConfigKeysMultilang()) {
            foreach ($this->getConfigKeysMultilang() as $multilang_config_key) {
                $forms_fields_value[$multilang_config_key] =
                    $this->getConfigMultilang($multilang_config_key);
            }
        }
        return $forms_fields_value;
    }

    public function getFrontConfig()
    {
        if (is_null($this->front_config)) {
            $id_shop = $this->context->shop->id;
            $id_shop_group = $this->context->shop->id_shop_group;
            $id_language = $this->context->language->id;
            $this->front_config = Configuration::getMultiple(
                $this->getConfigKeys(),
                null,
                $id_shop_group,
                $id_shop
            );
            if ($this->getConfigKeysMultilang()) {
                foreach ($this->getConfigKeysMultilang() as $multilang_option_key) {
                    $this->front_config[$multilang_option_key] = Configuration::get(
                        $multilang_option_key,
                        $id_language,
                        $id_shop_group,
                        $id_shop
                    );
                }
            }
        }
        return $this->front_config;
    }

    #################### DATA TRANSFER ####################

    public function getTransferData()
    {
        $file_name = 'data-transfer-'.$this->context->cookie->getName().'.json';
        $file_path = _PS_MODULE_DIR_.self::MODULE_NAME.'/'.$file_name;
        if (file_exists($file_path)) {
            $data = json_decode(Tools::file_get_contents($file_path), true);
            unlink($file_path);
            return $data;
        }

        return false;
    }

    public function setTransferData($data)
    {
        $file_name = 'data-transfer-'.$this->context->cookie->getName().'.json';
        $file = fopen(_PS_MODULE_DIR_.self::MODULE_NAME.'/'.$file_name, 'w');
        fwrite($file, json_encode($data));
        fclose($file);
    }

    #################### MESSENGER ####################

    private function addMessage($type, $message)
    {
        $this->messages[] = array('type' => $type, 'message' => $message);
        $this->save_messages = true;
    }

    public function addSuccessMessage($message)
    {
        $this->addMessage('success', $message);
        return $this;
    }

    public function getSuccessMessages($html = false)
    {
        $return_messages = array();

        $this->getMessagesFromFile();

        if ($this->messages) {
            foreach ($this->messages as $message) {
                if ($message['type'] == 'success') {
                    $return_messages[] = $message['message'];
                }
            }
        }

        if ($html) {
            if ($return_messages) {
                return $this->getModule()->displayConfirmation(implode('<br />', $return_messages));
            }
            return '';
        }

        return $return_messages;
    }

    public function addErrorMessage($message)
    {
        $this->addMessage('error', $message);
        return $this;
    }

    public function getErrorMessages($html = false)
    {
        $return_messages = array();

        $this->getMessagesFromFile();

        if ($this->messages) {
            foreach ($this->messages as $message) {
                if ($message['type'] == 'error') {
                    $return_messages[] = $message['message'];
                }
            }
        }

        if ($html) {
            if ($return_messages) {
                if (count($return_messages) < 2) {
                    $return_messages = implode('', $return_messages);
                }
                return $this->getModule()->displayError($return_messages);
            }
            return '';
        }

        return $return_messages;
    }

    private function getMessagesFromFile()
    {
        $file_name = 'messages-'.$this->context->cookie->getName().'.json';
        $file_path = _PS_MODULE_DIR_.self::MODULE_NAME.'/'.$file_name;
        if (file_exists($file_path)) {
            $this->messages = json_decode(Tools::file_get_contents($file_path), true);
            unlink($file_path);
        }
    }

    private function saveMessagesToFile()
    {
        if ($this->messages && $this->save_messages) {
            $file_name = 'messages-'.$this->context->cookie->getName().'.json';
            $file = fopen(_PS_MODULE_DIR_.self::MODULE_NAME.'/'.$file_name, 'w');
            fwrite($file, json_encode($this->messages));
            fclose($file);
        }
    }

    public function getMessagesHtml()
    {
        return $this->getErrorMessages(true).$this->getSuccessMessages(true);
    }

    #################### REQUEST PROCESS ####################

    public function isSubmit($submit)
    {
        return Tools::isSubmit($submit);
    }

    public function isSubmitMultilang($submit)
    {
        $return = true;
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $return = $return && (bool)Tools::isSubmit($submit.'_'.$language['id_lang']);
        }

        return $return;
    }

    public function getValue($key, $default_value = false, $from = null)
    {
        if (!is_null($from)) {
            if (isset($from[$key])) {
                return $from[$key];
            }

            return $default_value;
        }

        return Tools::getValue($key, $default_value);
    }

    public function getValueMultilang($key, $default = '')
    {
        $languages = Language::getLanguages(false);
        $results_array = array();
        foreach ($languages as $language) {
            $results_array[$language['id_lang']] = Tools::getValue($key.'_'.$language['id_lang'], $default);
        }
        return $results_array;
    }

    #################### URL ####################

    public function getBaseUrl()
    {
        return $this->context->shop->getBaseURL(true);
    }

    public function getModuleBaseUrl()
    {
        return $this->getBaseURL().'modules/'.self::MODULE_NAME.'/';
    }

    public function getAdminModuleUrl()
    {
        return $this->context->link->getAdminLink('AdminModules').'&configure='.self::MODULE_NAME;
    }

    public function getAdminModuleUrlTab($tab_section = null)
    {
        if (is_null($tab_section)) {
            $tab_section = $this->getTabSection();
        }

        return $this->getAdminModuleUrl().'&tab_section='.$tab_section;
    }

    public function redirect($url, $headers = null)
    {
        if (!Validate::isAbsoluteUrl($url)) {
            if (Tools::strlen($url) > 0 && Tools::substr($url, 0, 1) == '/') {
                $url = Tools::substr($url, 1);
            }
            $url = $this->getBaseURL().$url;
        }
        Tools::redirect($url, __PS_BASE_URI__, null, $headers);
    }

    public function redirectBack($default_back_url = null)
    {
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $this->getBaseURL()) == 0) {
            $url_back = $_SERVER['HTTP_REFERER'];
        } elseif ($default_back_url) {
            $url_back = $default_back_url;
        } else {
            $url_back = $this->getBaseURL();
        }
        $this->redirect($url_back);
    }

    #################### FORM HELPERS ####################

    public function setFilterToCookie($id, $filter_fields, $default_order_by, $tab_section = '')
    {
        $pagination_default = 50;
        $filter = array(
            'page' => Tools::getValue('submitFilter'.$id, 1),
            'limit' => Tools::getValue($id.'_pagination', $pagination_default),
            'order_by' => Tools::getValue($id.'Orderby', $default_order_by),
            'order_way' => Tools::strtoupper(Tools::getValue($id.'Orderway', 'ASC')),
        );

        foreach ($filter_fields as $filter_field) {
            $filter[$filter_field] = Tools::getValue($id.'Filter_'.$filter_field, '');
        }

        if (!$filter['page']) {
            $filter['page'] = 1;
        }

        if (Tools::isSubmit('submitReset'.$id)) {
            foreach ($filter_fields as $filter_field) {
                $filter[$filter_field] = '';
            }
            $filter['page'] = 1;
            $filter['limit'] = Tools::getValue($id.'_pagination', $pagination_default);
        }

        foreach ($filter_fields as $filter_field) {
            $cookie_field_name = $id.'Filter_'.$filter_field;
            $this->context->cookie->$cookie_field_name = $filter[$filter_field];
        }

        $cookie_field_name = $id.'Orderby';
        $this->context->cookie->$cookie_field_name = $filter['order_by'];
        $cookie_field_name = $id.'Orderway';
        $this->context->cookie->$cookie_field_name = $filter['order_way'];

        if (Tools::isSubmit('submitReset'.$id)) {
            $this->redirect($this->getAdminModuleUrl().'&tab_section='.$tab_section);
        }

        if (Tools::isSubmit('submitFilter'.$id)) {
            $this->setTabSection($tab_section);
        }

        return $filter;
    }

    public function generateMultilangField($value = '')
    {
        $multilangual_field = array();
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $multilangual_field[$language['id_lang']] = $value;
        }

        return $multilangual_field;
    }

    public function getLanguagesForForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = array();
        foreach (Language::getLanguages(false) as $lang) {
            $languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }
        return $languages;
    }

    #################### SMARTY ####################

    /**
     * @param $template_path
     * @param $front
     *
     * Relative path to from the module templates path
     * @return string
     */
    public function smartyFetch($template_path, $front = false)
    {
        $this->smartyAssign(array(
            'fspasc_module_base_url' => $this->getModuleBaseUrl(),
        ));

        if ($front) {
            return $this->getModule()->fetch('module:'.self::MODULE_NAME.'/views/templates/'.$template_path);
        }

        return $this->context->smarty->fetch(_PS_MODULE_DIR_.self::MODULE_NAME.'/views/templates/'.$template_path);
    }


    /**
     * @param $var
     */
    public function smartyAssign($var)
    {
        $this->context->smarty->assign($var);
    }

    #################### CSS / JS ####################

    /**
     * @param $css_file
     * @param string $screen
     */
    public function addCSS($css_file, $screen = 'all')
    {
        $this->context->controller->addCSS($this->getModuleBaseUrl().'views/css/'.$css_file, $screen);
    }

    /**
     * @param $js_file
     */
    public function addJS($js_file)
    {
        $this->context->controller->addJS($this->getModuleBaseUrl().'views/js/'.$js_file);
    }

    #################### OTHER ####################

    public function rnd($length = 10)
    {
        $salt = 'abchefghjkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        srand((double)microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= $length) {
            $num = rand() % 59;
            $tmp = Tools::substr($salt, $num, 1);
            $pass = $pass.$tmp;
            $i++;
        }
        return $pass;
    }

    /**
     * @param $module_name
     * @param string $min_version
     * @param bool $show_admin_error
     * @return bool
     */
    public function canUseModule($module_name, $min_version = '0.0.0', $show_admin_error = true)
    {
        if (Module::isEnabled($module_name)) {
            $m = Module::getInstanceByName($module_name);
            if (version_compare($m->version, $min_version, '>=')) {
                return true;
            }

            if (isset($this->context->controller) && $show_admin_error &&
                $this->context->controller instanceof AdminController &&
                !isset(self::$compatibility_warning_done[$module_name])) {
                $this->context->controller->warnings[] = sprintf(
                    $this->l(implode(' ', array(
                        'The "%s" module is installed,',
                        'but need to be updated to at least version "%s"',
                        'to able to use it\'s extending features.'
                    ))),
                    $m->displayName,
                    $min_version
                );
                self::$compatibility_warning_done[$module_name] = true;
            }
        }
        return false;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, Tools::strlen($needle));
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function contains($haystack, $needle)
    {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
        return false;
    }

    public function getLangValue($field_value, $id_lang = null)
    {
        if (is_array($field_value)) {
            if (is_null($id_lang)) {
                $id_lang = $this->context->language->id;
            }

            if (isset($field_value[$id_lang])) {
                return $field_value[$id_lang];
            } else {
                $id_lang = Configuration::get('PS_LANG_DEFAULT');
                if (isset($field_value[$id_lang])) {
                    return $field_value[$id_lang];
                }
            }
        }

        return $field_value;
    }

    public function isPsMin173()
    {
        return self::isPsMin173Static();
    }

    #################### STATIC FUNCTIONS ####################

    public static function isPsMin173Static()
    {
        return version_compare(_PS_VERSION_, '1.7.3.0', '>=');
    }

    public static function unescapeSmarty($escaped)
    {
        return str_replace(
            array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'),
            array('&', '"', '\'', '<', '>'),
            $escaped
        );
    }

    public static function minifyCss($params, $css)
    {
        $mode = 'default';
        if (isset($params['mode'])) {
            $mode = $params['mode'];
        }

        if ($mode == 'default') {
            $css = str_replace(': ', ':', $css);
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        }

        return $css;
    }
}
