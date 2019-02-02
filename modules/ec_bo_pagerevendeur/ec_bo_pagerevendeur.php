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
     * @return boolean
     */
    public function install() {
        if (!parent::install() 
               // Install Sql du module
                || !$this->_installSql() 
                //Hooks Admin
                || !$this->registerHook('actionAdminStoresControllerSaveAfter') 
                || !$this->registerHook('actionAdminCustomersFormModifier')
                //Hooks Front        
                || !$this->registerHook('additionalCustomerFormFields')
                //Hooks objects 
                || !$this->registerHook('actionObjectCustomerAddAfter') 
                || !$this->registerHook('actionObjectCustomerUpdateAfter')
                //Hook validation des champs
                || !$this->registerHook('validateCustomerFormFields') 
        ) {
            return false;
        }
 
        return true;
    }