<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierStore extends Store
{
    public static $column_definition = array(
        'name' => array(
            'required' => true,
            'validate' => 'isGenericName',
        ),
        'address' => array(
            'required' => true,
            'validate' => 'isAddress',
            'field' => 'address1',
        ),
        'address2' => array(
            'required' => false,
            'validate' => 'isAddress',
        ),
        'city' => array(
            'required' => true,
            'validate' => 'isCityName',
        ),
        'postcode' => array(
            'required' => true,
            'validate' => 'isPostCode',
        ),
        'country' => array(
            'required' => true,
            'validate' => 'isGenericName',
        ),
        'state' => array(
            'required' => false,
            'validate' => 'isGenericName',
        ),
        'latitude' => array(
            'required' => false,
            'validate' => 'isCoordinate',
        ),
        'longitude' => array(
            'required' => false,
            'validate' => 'isCoordinate',
        ),
        'phone' => array(
            'required' => false,
            'validate' => 'isPhoneNumber',
        ),
        'fax' => array(
            'required' => false,
            'validate' => 'isPhoneNumber',
        ),
        'email' => array(
            'required' => false,
            'validate' => 'isEmail',
        ),
        'note' => array(
            'required' => false,
            'validate' => 'isCleanHtml',
        ),
        'active' => array(
            'required' => true,
            'validate' => 'isBool',
        ),
        'imageurl' => array(
            'required' => false,
            'validate' => 'isUrl',
        ),
        'monday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        ),
        'tuesday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        ),
        'wednesday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        ),
        'thursday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        ),
        'friday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        ),
        'saturday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        ),
        'sunday' => array(
            'required' => false,
            'validate' => 'isOpenHour',
        )
    );

    public static function getColumnDefinition()
    {
        $column_definition = self::$column_definition;

        foreach ($column_definition as $column_name => $info) {
            $filed_name = $column_name;
            if (isset($info['field'])) {
                $filed_name = $info['field'];
            }

            if (isset(self::$definition['fields'][$filed_name]['size'])) {
                $column_definition[$column_name]['size'] = self::$definition['fields'][$filed_name]['size'];
            }
        }

        return $column_definition;
    }

    public static function getByName($name)
    {
        $table = 'store';
        if (FsPickupAtStoreCarrierHelper::isPsMin173Static()) {
            $table = 'store_lang';
        }

        $sql = 'SELECT id_store FROM `'._DB_PREFIX_.$table.'` WHERE `name` = \''.pSQL($name).'\'';
        $id_store =  Db::getInstance()->getValue($sql);

        if ($id_store) {
            return new self($id_store);
        } else {
            return new self();
        }
    }

    public function copyFromImportRow($row)
    {
        if (isset($row['id_country'])) {
            $this->id_country = $row['id_country'];
        }

        if (isset($row['id_state'])) {
            $this->id_state = $row['id_state'];
        }

        if (isset($row['name'])) {
            $this->name = $row['name'];
        }

        if (isset($row['address'])) {
            $this->address1 = $row['address'];
        }

        if (isset($row['address2'])) {
            $this->address2 = $row['address2'];
        }

        if (isset($row['postcode'])) {
            $this->postcode = $row['postcode'];
        }

        if (isset($row['city'])) {
            $this->city = $row['city'];
        }

        if (isset($row['latitude'])) {
            $this->latitude = $row['latitude'];
        }

        if (isset($row['longitude'])) {
            $this->longitude = $row['longitude'];
        }

        if (isset($row['phone'])) {
            $this->phone = $row['phone'];
        }

        if (isset($row['fax'])) {
            $this->fax = $row['fax'];
        }

        if (isset($row['note'])) {
            $this->note = $row['note'];
        }

        if (isset($row['email'])) {
            $this->email = $row['email'];
        }

        if (isset($row['active'])) {
            $this->active = $row['active'];
        }

        $monday = '';
        if (isset($row['monday'])) {
            $monday = $row['monday'];
        }

        $tuesday = '';
        if (isset($row['tuesday'])) {
            $tuesday = $row['tuesday'];
        }

        $wednesday = '';
        if (isset($row['wednesday'])) {
            $wednesday = $row['wednesday'];
        }

        $thursday = '';
        if (isset($row['thursday'])) {
            $thursday = $row['thursday'];
        }

        $friday = '';
        if (isset($row['friday'])) {
            $friday = $row['friday'];
        }

        $saturday = '';
        if (isset($row['saturday'])) {
            $saturday = $row['saturday'];
        }

        $sunday = '';
        if (isset($row['sunday'])) {
            $sunday = $row['sunday'];
        }

        $hours = array(
            $monday,
            $tuesday,
            $wednesday,
            $thursday,
            $friday,
            $saturday,
            $sunday
        );

        foreach ($hours as $key => $hour) {
            $hours[$key] = explode(' | ', $hour);
        }

        $this->hours = json_encode($hours);

        if (FsPickupAtStoreCarrierHelper::isPsMin173Static()) {
            $this->name = $this->createMultiLangField($this->name);
            $this->address1 = $this->createMultiLangField($this->address1);
            $this->address2 = $this->createMultiLangField($this->address2);
            $this->hours = $this->createMultiLangField($this->hours);
            $this->note = $this->createMultiLangField($this->note);
        }
    }

    public function createMultiLangField($value)
    {
        $languages = Language::getLanguages(false);
        $ml = array();
        foreach ($languages as $lang) {
            $ml[$lang['id_lang']] = $value;
        }
        return $ml;
    }
}
