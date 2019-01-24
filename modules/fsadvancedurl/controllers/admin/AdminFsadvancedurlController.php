<?php
/**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 */

class AdminFsadvancedurlController extends ModuleAdminController
{
    public function ajaxProcessGeneratelinkrewrite()
    {
        $this->json = (bool)Tools::getValue('json');

        $response = array(
            'has_more' => false,
            'progress_bar_percent' => 0,
            'processed_count' => 0,
            'total_count' => 0,
            'progress_bar_message' => '',
            'alert_title' => ''
        );

        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $process_step = 5;
        $error = false;
        $fsau = $this->module;

        if (!$this->hasAccess('edit')) {
            $error = true;
            $this->errors[] = $fsau->l('Access denied');
        }

        if (!$error) {
            $link_rewrite_schema_lang = $this->module->getMultilangualConfiguration('FSAU_LINK_REWRITE_SCHEMA');
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                if (!isset($link_rewrite_schema_lang[$lang['id_lang']]) ||
                    empty($link_rewrite_schema_lang[$lang['id_lang']])) {
                    $error = true;
                    $this->errors[] = $fsau->l(
                        sprintf('Please fill the Friendly URL schema in %s language', $lang['name'])
                    );
                }
            }

            if (!$error) {
                $type = Configuration::get('FSAU_LINK_REWRITE_MODE', 'regenerate_all');

                if ($type == 'regenerate_all') {
                    $offset = Tools::getValue('fsau_offset');
                    $response['processed_count'] = $offset;

                    if (!$error) {
                        $products = Db::getInstance()->executeS(
                            'SELECT `id_product` FROM `'._DB_PREFIX_.'product_shop` WHERE `id_shop` = '.(int)$id_shop
                        );
                        $response['total_count'] = count($products);
                        $products = Db::getInstance()->executeS(
                            'SELECT `id_product` FROM `'._DB_PREFIX_.'product_shop` WHERE `id_shop` = '.(int)$id_shop.
                            ' ORDER BY `id_product` ASC LIMIT '.(int)$offset.', '.(int)$process_step
                        );

                        foreach ($products as $product) {
                            $p = new FsAdvancedUrlProduct((int)$product['id_product']);

                            foreach ($languages as $lang) {
                                $p->link_rewrite[$lang['id_lang']] = $this->generateLinkRewrite(
                                    (int)$product['id_product'],
                                    $lang['id_lang'],
                                    $id_shop,
                                    $link_rewrite_schema_lang
                                );
                            }

                            $p->save();

                            $response['processed_count']++;
                        }

                        $response = $this->generateLoopParams($response);

                        $response = $this->generateProgressBarText(
                            $response,
                            $fsau->l('No item processed'),
                            $fsau->l('product url generated'),
                            $fsau->l('product urls generated')
                        );

                        $response['alert_title'] = $fsau->l('DONE!');

                        $this->confirmations[] = $fsau->l('Products url generation completed.');

                        $this->content = $response;
                    }
                }

                if ($type == 'regenerate_duplicate' || $type == 'append_duplicate') {
                    if (!$context->cookie->fsau_duplicate_count) {
                        $context->cookie->fsau_duplicate_count = $this->getProductDuplicateCount();
                    }
                    $response['total_count'] = (int)$context->cookie->fsau_duplicate_count;

                    $to_process = $this->getNextProductDuplicatePair();
                    if ($to_process) {
                        //$p_base = new FsAdvancedUrlProduct((int)$to_process[0]['id_object']);
                        $p_change = new FsAdvancedUrlProduct((int)$to_process[1]['id_object']);
                        $p_change_id_lang = $to_process[1]['id_lang'];

                        $new_link_rewrite = $this->generateLinkRewrite(
                            (int)$p_change->id,
                            $p_change_id_lang,
                            $id_shop,
                            $link_rewrite_schema_lang
                        );

                        if ($type == 'regenerate_duplicate') {
                            $p_change->link_rewrite[$p_change_id_lang] = $new_link_rewrite;
                        }

                        if ($type == 'append_duplicate') {
                            $p_change->link_rewrite[$p_change_id_lang] .= $new_link_rewrite;
                        }

                        $p_change->save();

                        if ((int)$context->cookie->fsau_processed_count % 10 === 0) {
                            $response['processed_count'] = $response['total_count'] - $this->getProductDuplicateCount();
                        } else {
                            $response['processed_count'] = (int)$context->cookie->fsau_processed_count + 1;
                        }

                        if ($response['processed_count'] == (int)$context->cookie->fsau_processed_count) {
                            $unresolved_count = (int)$context->cookie->fsau_unresolved_count;
                            $unresolved_count++;
                            $context->cookie->fsau_unresolved_count = $unresolved_count;

                            if ($context->cookie->fsau_unresolved_count > 1) {
                                $this->errors[] = $fsau->l('Current Friendly URL Schema can NOT resolve duplication!');
                                $response['total_count'] = 0;
                            }
                        }
                    } else {
                        $response['total_count'] = $response['processed_count'] =
                            $context->cookie->fsau_duplicate_count;
                    }

                    $response = $this->generateLoopParams($response);

                    $response = $this->generateProgressBarText(
                        $response,
                        $fsau->l('No item processed'),
                        $fsau->l('product url generated'),
                        $fsau->l('product urls generated')
                    );

                    $response['alert_title'] = $fsau->l('DONE!');

                    $this->confirmations[] = $fsau->l('Products url generation completed.');

                    $this->content = $response;
                }
            }
        }

        $this->status = 'ok';
    }

    private function hasAccess($type)
    {
        $tabAccess = Profile::getProfileAccesses(Context::getContext()->employee->id_profile, 'class_name');

        if (isset($tabAccess['AdminFsadvancedurl'][$type])) {
            if ($tabAccess['AdminFsadvancedurl'][$type] === '1') {
                return true;
            }
        }
        return false;
    }

    private function generateLoopParams($response)
    {
        $context = Context::getContext();

        if (!$response['total_count']) {
            $response['progress_bar_percent'] = 100;
        } else {
            $response['progress_bar_percent'] = round($response['processed_count'] / $response['total_count'] * 100, 0);
        }
        if ($response['processed_count'] < $response['total_count']) {
            $response['has_more'] = true;
        } else {
            $context->cookie->fsau_duplicate_count = 0;
            $context->cookie->fsau_unresolved_count = 0;
        }

        $context->cookie->fsau_processed_count = $response['processed_count'];

        return $response;
    }

    private function generateProgressBarText($response, $no_item, $singular, $plural)
    {
        if ($response['processed_count'] < 1) {
            $response['progress_bar_message'] = $no_item;
        } elseif ($response['processed_count'] > 1) {
            $response['progress_bar_message'] = $response['processed_count'].' '.$plural;
        } else {
            $response['progress_bar_message'] = $response['processed_count'].' '.$singular;
        }
        return $response;
    }

    private function generateLinkRewrite($id_product, $id_lang, $id_shop, $link_rewrite_schema_lang, $params = array())
    {
        $p_lang = new Product((int)$id_product, false, $id_lang, $id_shop);
        $p_link_rewrite = trim($link_rewrite_schema_lang[$id_lang]);

        //Product params
        $params['product_name'] = Tools::str2url($p_lang->name);
        $params['product_meta_title'] = Tools::str2url($p_lang->meta_title);
        $params['product_meta_keywords'] = Tools::str2url($p_lang->meta_keywords);
        $params['product_ean13'] = Tools::str2url($p_lang->ean13);
        $params['product_upc'] = Tools::str2url($p_lang->upc);
        $params['product_reference'] = Tools::str2url($p_lang->reference);
        $params['product_price'] = Tools::str2url(
            Product::getPriceStatic(
                $p_lang->id,
                false,
                null,
                6,
                null,
                false,
                true,
                1,
                false,
                null,
                null,
                null,
                $p_lang->specificPrice
            )
        );
        $params['product_tags'] = Tools::str2url($p_lang->getTags($id_lang));

        //Category params
        $c = new Category($p_lang->id_category_default, $id_lang, $id_shop);
        $params['default_category_name'] = Tools::str2url($c->name);
        $params['default_category_meta_title'] = Tools::str2url($c->meta_title);
        $params['default_category_link_rewrite'] = Tools::str2url($c->link_rewrite);

        //Manufacturer params
        $m = new Manufacturer($p_lang->id_manufacturer, $id_lang);
        $params['manufacturer_name'] = Tools::str2url($m->name);
        $params['manufacturer_meta_title'] = Tools::str2url($m->meta_title);

        //Supplier params
        $s = new Supplier($p_lang->id_supplier, $id_lang);
        $params['supplier_name'] = Tools::str2url($s->name);
        $params['supplier_meta_title'] = Tools::str2url($s->meta_title);

        //Feature params
        $features = Feature::getFeatures($id_lang);
        foreach ($features as $feature) {
            $f = new Feature($feature['id_feature'], $id_lang);
            $params['feature_'.str_replace('-', '_', Tools::str2url($f->name))] = '';
        }

        foreach ($p_lang->getFeatures() as $feature) {
            $f = new Feature($feature['id_feature'], $id_lang);
            $fv = new FeatureValue($feature['id_feature_value'], $id_lang);
            $params['feature_'.str_replace('-', '_', Tools::str2url($f->name))] =
                Tools::str2url($fv->value);
        }

        //Replace the params
        foreach ($params as $keyword => $value) {
            $p_link_rewrite = str_replace('{'.$keyword.'}', $value, $p_link_rewrite);
        }
        $p_link_rewrite = preg_replace('/{[^}]+}/', '', $p_link_rewrite);

        while (preg_match('(--)', $p_link_rewrite)) {
            $p_link_rewrite = str_replace('--', '-', $p_link_rewrite);
        }

        if (Tools::strlen($p_link_rewrite) > 128) {
            $p_link_rewrite = Tools::substr($p_link_rewrite, 0, 128);
        }

        return $p_link_rewrite;
    }

    private function getProductDuplicateCount()
    {
        $sql = 'SELECT pl.`id_product`, pl.`link_rewrite`, pl.`id_shop`, pl.`id_lang`, p.`id_category_default`,';
        $sql .= ' COUNT(pl.`id_product`) as count FROM `'._DB_PREFIX_.'product_lang` pl LEFT JOIN `';
        $sql .= _DB_PREFIX_.'product` p ON pl.`id_product` = p.`id_product`';
        $sql .= ' GROUP BY pl.`id_shop`, pl.`id_lang`, pl.`link_rewrite`';
        if (Configuration::get('FSAU_ENABLE_pr_categories') || Configuration::get('FSAU_ENABLE_pr_category')) {
            $sql .= ', p.`id_category_default`';
        }
        $sql .= ' HAVING count(pl.`link_rewrite`) > 1 ORDER BY pl.`id_shop` ASC';

        $duplicate_count = Db::getInstance()->getValue('SELECT SUM(count) as sum FROM ('.pSQL($sql).') as sum_table');
        return $duplicate_count;
    }

    private function getNextProductDuplicatePair()
    {
        $return = array();
        $limit = ' LIMIT 1';

        $sql = 'SELECT pl.`id_product`, pl.`link_rewrite`, pl.`id_shop`, pl.`id_lang`, p.`id_category_default` FROM';
        $sql .= ' `'._DB_PREFIX_.'product_lang` pl LEFT JOIN `';
        $sql .= _DB_PREFIX_.'product` p ON pl.`id_product` = p.`id_product`';
        $sql .= ' GROUP BY pl.`id_shop`, pl.`id_lang`, pl.`link_rewrite`';
        if (Configuration::get('FSAU_ENABLE_pr_categories') || Configuration::get('FSAU_ENABLE_pr_category')) {
            $sql .= ', p.`id_category_default`';
        }
        $sql .= ' HAVING count(pl.`link_rewrite`) > 1 ORDER BY pl.`id_shop` ASC';
        $sql .= $limit;
        $duplicates = Db::getInstance()->executeS($sql);

        if ($duplicates) {
            foreach ($duplicates as $duplicate) {
                $sql_more = 'SELECT pl.`id_product`, pl.`link_rewrite`, pl.`id_shop`, pl.`id_lang`,';
                $sql_more .= ' p.`id_category_default`, pl.`name` FROM `'._DB_PREFIX_.'product_lang` pl LEFT JOIN `';
                $sql_more .= _DB_PREFIX_.'product` p ON pl.`id_product` = p.`id_product`';
                $sql_more .= ' WHERE pl.`id_shop` = \''.pSQL($duplicate['id_shop']).'\'';
                $sql_more .= ' AND pl.`link_rewrite` = \''.pSQL($duplicate['link_rewrite']).'\'';
                $sql_more .= ' AND pl.`id_lang` = \''.pSQL($duplicate['id_lang']).'\'';
                if (Configuration::get('FSAU_ENABLE_pr_categories') || Configuration::get('FSAU_ENABLE_pr_category')) {
                    $sql_more .= ' AND p.`id_category_default` = \''.pSQL($duplicate['id_category_default']).'\'';
                }
                $sql_more .= ' GROUP BY pl.`id_product` ORDER BY pl.`id_product` ASC LIMIT 2';

                $more_infos = Db::getInstance()->executeS($sql_more);
                foreach ($more_infos as $more_info) {
                    $row = array();
                    $row['id'] = 'product_'.$more_info['id_product'];
                    $row['id_object'] = $more_info['id_product'];
                    $row['id_type'] = 'product';
                    $row['type'] = 'Product';
                    $row['name'] = $more_info['name'];
                    $row['link_rewrite'] = $more_info['link_rewrite'];
                    $row['id_lang'] = $more_info['id_lang'];
                    $row['lang'] = Language::getIsoById($more_info['id_lang']);
                    $row['shop'] = '';
                    $shop = Shop::getShop($more_info['id_shop']);
                    if ($shop) {
                        $row['shop'] = $shop['name'];
                    }

                    $return[] = $row;
                }
            }
        }

        return $return;
    }
}
