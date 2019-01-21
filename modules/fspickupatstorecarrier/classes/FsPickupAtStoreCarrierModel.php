<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierModel extends ObjectModel
{
    /** @var integer fspickupatstorecarrier id*/
    public $id;

    /** @var integer fspickupatstorecarrier id order*/
    public $id_order;

    /** @var string fspickupatstorecarrier id store */
    public $id_store;

    /** @var string fspickupatstorecarrier creation date */
    public $date_pickup;

    /** @var string fspickupatstorecarrier creation date */
    public $date_add;

    /** @var string fspickupatstorecarrier last modification date */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'fspickupatstorecarrier',
        'primary' => 'id_fspickupatstorecarrier',
        'multilang' => false,
        'fields' => array(
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_store' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'date_pickup' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
        )
    );

    public static function getIdByIdOrder($id_order)
    {
        $sql = 'SELECT id_fspickupatstorecarrier FROM `'
            ._DB_PREFIX_.'fspickupatstorecarrier` WHERE `id_order` = \''.pSQL($id_order).'\'';
        return Db::getInstance()->getValue($sql);
    }

    public static function getByIdOrder($id_order)
    {
        $id_fspickupatstorecarrier = self::getIdByIdOrder($id_order);
        if ($id_fspickupatstorecarrier) {
            return new self($id_fspickupatstorecarrier);
        } else {
            return new self();
        }
    }
}
