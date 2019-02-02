<?php
if (!defined('_PS_VERSION_'))
    exit;

require_once(dirname(__FILE__).'/classes/PageRevendeurClass.php');

class Ec_PageRevendeur extends Module
{
	public function __construct()
	{
		$this->name = 'ec_pagerevendeur';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Damien Lievre';
		$this->bootstrap = true;
		parent::__construct();
		$this->need_instance = 0;
		$this->displayName = 'Informations sur un revendeur';
		$this->description = 'Liste sur la page CMS revendeur les informations sur celui-ci venant de la BDD';
	}

 public function install()
    {
//Si le module ne s'installe pas et ne se hooke pas bien, on demande à Prestashop de nous retourner une erreur. Write the less, do the more
        if (!parent::install() || !$this->registerHook('displaystoreinfo'))
            return false;

        return true;
//Pensez systématiquement à faire une fonction de désinstallation
    }

  public function uninstall()
	{
		if (!parent::uninstall())
			return false;

		return true;
	}

	public function getInfosRevendeur($id_cms)
    {
        $myInfos = PageRevendeurClass::getRevendeurDonnees((int)$id_cms);
       // je déroule et je prends que la première ligne;
       // $myInfosLigne = $myInfos->fetch_row();        
        return $myInfos;
    }

    public function hookdisplaystoreinfo($params)//Fonction essentielle de hook
    {
        //return 'toto';
        //return $params['id_cms'];
        if (!$this->isCached('revendeur.tpl', $this->getCacheId())) {//On vide le cache du tpl
            $this->smarty->assign(//On demande à smarty d'ajouter une nouvelle variable
                'revendeur' , $this->getInfosRevendeur($params['id_cms'])
            );           
        }
        return $this->display(__FILE__, 'revendeur.tpl', $this->getCacheId());//On renvoie le tout vers la vue
 
    }


}
