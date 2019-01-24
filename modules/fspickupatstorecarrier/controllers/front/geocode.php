<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class FsPickupAtStoreCarrierGeocodeModuleFrontController extends ModuleFrontController
{
    /** @var FsPickupAtStoreCarrier */
    public $module;

    public function initContent()
    {
        $response = array('status' => 'ok');
        $mode = Tools::getValue('mode', 'context');

        switch ($mode) {
            case 'context':
                $response = array_merge($response, $this->geocodeContext());
                break;
            case 'address':
                $response = array_merge($response, $this->geocodeAddress());
                break;
        }

        die(json_encode($response));
    }

    protected function geocodeAddress()
    {
        $address = Tools::getValue('address');
        if ($address) {
            $coords = $this->geocode($address);
            if ($coords) {
                return array(
                    'id_store' => $this->getNearestStore($coords['lat'], $coords['lng']),
                    'address' => $address
                );
            } else {
                return array('error' => 'no-result');
            }
        } else {
            return array('error' => 'no-address');
        }
    }

    protected function geocodeContext()
    {
        if (Validate::isLoadedObject($this->context->customer)) {
            $addresses = $this->context->customer->getAddresses($this->context->language->id);
            if ($addresses) {
                $address = array_pop($addresses);
                $address_text = $address['address1'].', '.$address['city'];
                if ($address['state']) {
                    $address_text .= ', '.$address['state'];
                }
                $address_text .= ', '.$address['country'].', '.$address['postcode'].'';

                $coords = $this->geocode($address_text);
                if ($coords) {
                    return array(
                        'id_store' => $this->getNearestStore($coords['lat'], $coords['lng']),
                        'address' => $address_text
                    );
                } else {
                    return array('error' => 'no-result');
                }
            } else {
                return array('error' => 'no-address');
            }
        } else {
            return array('error' => 'no-customer');
        }
    }

    protected function geocode($address)
    {
        $query = array(
            'key' => Configuration::get('FSPASC_MAP_API_KEY'),
            'address' => $address
        );

        $http_query = http_build_query($query);
        $result = Tools::file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?'.$http_query);
        $result = json_decode($result, true);

        if ($result['status'] == 'OK') {
            return array(
                'lat' => $result['results'][0]['geometry']['location']['lat'],
                'lng' => $result['results'][0]['geometry']['location']['lng']
            );
        } else {
            return false;
        }
    }

    protected function getNearestStore($lat, $lng)
    {
        $closest_id_store = null;
        $closest_distance = null;

        $stores = $this->module->getStores();
        foreach ($stores as $store) {
            $dist = $this->getDistanceBetweenPointsNew(
                $lat,
                $lng,
                $store['latitude'],
                $store['longitude']
            );

            if (!is_null($closest_distance)) {
                if ($dist < $closest_distance) {
                    $closest_id_store = $store['id_store'];
                    $closest_distance = $dist;
                }
            } else {
                $closest_id_store = $store['id_store'];
                $closest_distance = $dist;
            }
        }

        return $closest_id_store;
    }

    public function getDistanceBetweenPointsNew($lat1, $lng1, $lat2, $lng2, $unit = 'km')
    {
        $theta = $lng1-$lng2;
        $distance = (sin(deg2rad($lat1))*sin(deg2rad($lat2))+
            (cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($theta))));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        switch ($unit) {
            case 'km':
                $distance = $distance * 1.609344;
                break;
        }

        return (round($distance, 2));
    }
}
