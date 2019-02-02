<?php

class Ec_BO_PageRevendeur extends Module {
 
    public function __construct() {
 
        $this->name = 'ec_bo_pagerevendeur';
        $this->tab = 'others';
        $this->author = 'Damien Lievre';
        $this->version = '0.1.0';
        $this->need_instance = 0;
        $this->bootstrap = true;
 
        parent::__construct();
 
        $this->displayName = $this->l('ec_bo_pagerevendeur');
        $this->description = $this->l('add new fields to store');
        $this->ps_versions_compliancy = array('min' => '1.7.1', 'max' => _PS_VERSION_);
    }
 
    /**
     * Installation du module
     * @return bool
     */
    public function install()
    {
        if ( ! parent::install()
            || !$this->registerHook('actionAdminStoresControllerSaveAfter')
            || !$this->registerHook('actionAdminStoresFormModifier')
            ) {
                return false;
            }
            
            return true;
    }
    
    public function uninstall()
    {
        return parent::uninstall();
    }
    
    public function hookActionAdminStoresFormModifier($params)
    {
        
        //Ajout d'un champ au fieldset par défaut
        $params['fields'][0]['form']['input'][] =  array(
            'type' => 'text',
            'label' => $this->l('Custom field 1'),
            'name' => $this->name.'_newfield1',
        );
        
        //Modification des propriétés d'un champ déjà existant
        foreach ( $params['fields'][0]['form']['input'] as &$field ){
            
            if ( $field['name'] == 'meta_description'){
                $field['maxlength'] = '255';
                $field['maxchar'] = '255';
                $field['hint'] = 'Modified by a module';
            }
        }
        
        //Création d'un nouveau fieldset
        $params['fields'][$this->name] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Sample Category Fieldset'),
                    'icon' => 'icon-tags',
                ),
                'description' => $this->l('New sample fieldset'),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Custom field New Fieldset 1'),
                        'name' => $this->name.'_newfieldset1',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Custom field New Fieldset 2'),
                        'name' => $this->name.'_newfieldset2',
                    ),
                )
            )
        );
        
        //Pour remonter les valeurs des champs
        $params['fields_value'][$this->name.'_newfield1'] = 'Custom value 1';
        $params['fields_value'][$this->name.'_newfieldset1'] = 'Custom value fieldset 1';
        $params['fields_value'][$this->name.'_newfieldset2'] = 'Custom value fieldset 2';
    }
    
}