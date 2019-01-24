<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ec_MenuMagasins extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'ec_menumagasins';
        $this->author = 'Damien Lievre';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = 'Menu des magasins revendeurs';
        $this->description = 'Liste les magasins';

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:ec_menumagasins/ec_menumagasins.tpl';
    }

    public function install()
    {
        return (parent::install()
            && $this->registerHook('displayTop'));
    }

    public function hookDisplayTop($params) {
        //return parent::_clearCache($this->templateFile);   
        return $this->renderWidget();
    }
    /**
     * Récupération des variables du widget
     * @param type $hookName
     * @param array $configuration
     * @return array
     */
    public function getWidgetVariables($hookName, array $configuration)
    {
          

        
        $result = Db::getInstance()->executeS("
                    SELECT sl.name , cl.link_rewrite  
                    FROM ps_store s
                    inner join ps_store_lang sl on sl.id_store = s.id_store
                    inner join ps_cms_lang cl on cl.id_cms = s.id_cms
                    WHERE  sl.id_lang=1 
                    AND cl.id_lang=1 
                    AND s.bl_display = 1");
        
        return array(
            'magasins' => $result
        );
    }
    /**
     * Affichage du widget
     * @param type $hookName
     * @param array $configuration : Ensemble des variables du widget
     * @return array
     */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('ec_menumagasins'))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);
            
            if (empty($variables)) {
                return false;
            }
            
            $this->smarty->assign($variables);
        }
        
        return $this->fetch($this->templateFile, $this->getCacheId('ec_menumagasins'));
    }
}
