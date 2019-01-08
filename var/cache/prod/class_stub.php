<?php

class Profile extends ProfileCore {};
class CheckoutProcess extends CheckoutProcessCore {};
class CheckoutPaymentStep extends CheckoutPaymentStepCore {};
class CheckoutSession extends CheckoutSessionCore {};
class CheckoutDeliveryStep extends CheckoutDeliveryStepCore {};
class DeliveryOptionsFinder extends DeliveryOptionsFinderCore {};
class CheckoutPersonalInformationStep extends CheckoutPersonalInformationStepCore {};
class PaymentOptionsFinder extends PaymentOptionsFinderCore {};
class CartChecksum extends CartChecksumCore {};
class AddressValidator extends AddressValidatorCore {};
class ConditionsToApproveFinder extends ConditionsToApproveFinderCore {};
class CheckoutAddressesStep extends CheckoutAddressesStepCore {};
abstract class AbstractCheckoutStep extends AbstractCheckoutStepCore {};
class Country extends CountryCore {};
class Alias extends AliasCore {};
class Zone extends ZoneCore {};
class Windows extends WindowsCore {};
class Image extends ImageCore {};
class Connection extends ConnectionCore {};
class Dispatcher extends DispatcherCore {};
class Product extends ProductCore {};
class AddressFormat extends AddressFormatCore {};
class PDFGenerator extends PDFGeneratorCore {};
class HTMLTemplateSupplyOrderForm extends HTMLTemplateSupplyOrderFormCore {};
class HTMLTemplateOrderReturn extends HTMLTemplateOrderReturnCore {};
class PDF extends PDFCore {};
class HTMLTemplateInvoice extends HTMLTemplateInvoiceCore {};
class HTMLTemplateDeliverySlip extends HTMLTemplateDeliverySlipCore {};
abstract class HTMLTemplate extends HTMLTemplateCore {};
class HTMLTemplateOrderSlip extends HTMLTemplateOrderSlipCore {};
class Uploader extends UploaderCore {};
class Combination extends CombinationCore {};
class ProductSale extends ProductSaleCore {};
class ImageManager extends ImageManagerCore {};
class RequestSql extends RequestSqlCore {};
class ProductPresenterFactory extends ProductPresenterFactoryCore {};
class WarehouseAddress extends WarehouseAddressCore {};
class Group extends GroupCore {};
class LinkProxy extends LinkProxyCore {};
class HelperCalendar extends HelperCalendarCore {};
class HelperImageUploader extends HelperImageUploaderCore {};
class HelperKpiRow extends HelperKpiRowCore {};
class HelperOptions extends HelperOptionsCore {};
class HelperView extends HelperViewCore {};
class HelperShop extends HelperShopCore {};
class HelperTreeShops extends HelperTreeShopsCore {};
class HelperList extends HelperListCore {};
class HelperKpi extends HelperKpiCore {};
class HelperForm extends HelperFormCore {};
class Helper extends HelperCore {};
class HelperTreeCategories extends HelperTreeCategoriesCore {};
class HelperUploader extends HelperUploaderCore {};
class Store extends StoreCore {};
class CssMinifier extends CssMinifierCore {};
class StylesheetManager extends StylesheetManagerCore {};
class CccReducer extends CccReducerCore {};
class JavascriptManager extends JavascriptManagerCore {};
abstract class AbstractAssetManager extends AbstractAssetManagerCore {};
class JsMinifier extends JsMinifierCore {};
class WebserviceException extends WebserviceExceptionCore {};
class WebserviceKey extends WebserviceKeyCore {};
class WebserviceOutputJSON extends WebserviceOutputJSONCore {};
class WebserviceOutputXML extends WebserviceOutputXMLCore {};
class WebserviceSpecificManagementSearch extends WebserviceSpecificManagementSearchCore {};
class WebserviceSpecificManagementImages extends WebserviceSpecificManagementImagesCore {};
class WebserviceRequest extends WebserviceRequestCore {};
class WebserviceOutputBuilder extends WebserviceOutputBuilderCore {};
class CSV extends CSVCore {};
class Carrier extends CarrierCore {};
class LocalizationPack extends LocalizationPackCore {};
class Shop extends ShopCore {};
class ShopGroup extends ShopGroupCore {};
class ShopUrl extends ShopUrlCore {};
class QqUploadedFileXhr extends QqUploadedFileXhrCore {};
class FileUploader extends FileUploaderCore {};
abstract class Cache extends CacheCore {};
class CacheXcache extends CacheXcacheCore {};
class CacheMemcache extends CacheMemcacheCore {};
class CacheApc extends CacheApcCore {};
class CacheMemcached extends CacheMemcachedCore {};
class Feature extends FeatureCore {};
class Search extends SearchCore {};
class CustomerThread extends CustomerThreadCore {};
class ConnectionsSource extends ConnectionsSourceCore {};
class PrestaShopBackup extends PrestaShopBackupCore {};
class Link extends LinkCore {};
class PhpEncryptionEngine extends PhpEncryptionEngineCore {};
class Referrer extends ReferrerCore {};
class DateRange extends DateRangeCore {};
class Hook extends HookCore {};
class ManufacturerAddress extends ManufacturerAddressCore {};
class AddressChecksum extends AddressChecksumCore {};
class QuickAccess extends QuickAccessCore {};
class Chart extends ChartCore {};
class SupplierAddress extends SupplierAddressCore {};
class State extends StateCore {};
class RangeWeight extends RangeWeightCore {};
class RangePrice extends RangePriceCore {};
class Manufacturer extends ManufacturerCore {};
class ThemeLang extends ThemeLangCore {};
class ConfigurationLang extends ConfigurationLangCore {};
class OrderReturnStateLang extends OrderReturnStateLangCore {};
class GroupLang extends GroupLangCore {};
class GenderLang extends GenderLangCore {};
class DataLang extends DataLangCore {};
class OrderStateLang extends OrderStateLangCore {};
class FeatureLang extends FeatureLangCore {};
class FeatureValueLang extends FeatureValueLangCore {};
class ContactLang extends ContactLangCore {};
class TabLang extends TabLangCore {};
class AttributeGroupLang extends AttributeGroupLangCore {};
class ProfileLang extends ProfileLangCore {};
class AttributeLang extends AttributeLangCore {};
class CategoryLang extends CategoryLangCore {};
class StockMvtReasonLang extends StockMvtReasonLangCore {};
class QuickAccessLang extends QuickAccessLangCore {};
class SupplyOrderStateLang extends SupplyOrderStateLangCore {};
class CmsCategoryLang extends CmsCategoryLangCore {};
class OrderMessageLang extends OrderMessageLangCore {};
class CarrierLang extends CarrierLangCore {};
class RiskLang extends RiskLangCore {};
class MetaLang extends MetaLangCore {};
class CartRule extends CartRuleCore {};
abstract class ObjectModel extends ObjectModelCore {};
class Mail extends MailCore {};
class OrderReturn extends OrderReturnCore {};
class OrderState extends OrderStateCore {};
class OrderMessage extends OrderMessageCore {};
class OrderReturnState extends OrderReturnStateCore {};
class OrderSlip extends OrderSlipCore {};
class OrderDiscount extends OrderDiscountCore {};
class OrderInvoice extends OrderInvoiceCore {};
class OrderCartRule extends OrderCartRuleCore {};
class OrderCarrier extends OrderCarrierCore {};
class OrderPayment extends OrderPaymentCore {};
class OrderDetail extends OrderDetailCore {};
class OrderHistory extends OrderHistoryCore {};
class Order extends OrderCore {};
class GroupReduction extends GroupReductionCore {};
abstract class AbstractLogger extends AbstractLoggerCore {};
class FileLogger extends FileLoggerCore {};
class TaxRulesGroup extends TaxRulesGroupCore {};
abstract class TaxManagerModule extends TaxManagerModuleCore {};
class TaxRulesTaxManager extends TaxRulesTaxManagerCore {};
class TaxCalculator extends TaxCalculatorCore {};
class TaxRule extends TaxRuleCore {};
class TaxConfiguration extends TaxConfigurationCore {};
class TaxManagerFactory extends TaxManagerFactoryCore {};
class Tax extends TaxCore {};
abstract class ProductListingFrontController extends ProductListingFrontControllerCore {};
class AdminController extends AdminControllerCore {};
abstract class Controller extends ControllerCore {};
class ModuleFrontController extends ModuleFrontControllerCore {};
class FrontController extends FrontControllerCore {};
abstract class ModuleAdminController extends ModuleAdminControllerCore {};
abstract class ProductPresentingFrontController extends ProductPresentingFrontControllerCore {};
class PrestaShopObjectNotFoundException extends PrestaShopObjectNotFoundExceptionCore {};
class PrestaShopModuleException extends PrestaShopModuleExceptionCore {};
class PrestaShopException extends PrestaShopExceptionCore {};
class PrestaShopDatabaseException extends PrestaShopDatabaseExceptionCore {};
class PrestaShopPaymentException extends PrestaShopPaymentExceptionCore {};
class Contact extends ContactCore {};
class Language extends LanguageCore {};
class CustomizationField extends CustomizationFieldCore {};
class QqUploadedFileForm extends QqUploadedFileFormCore {};
class Tools extends ToolsCore {};
class DbQuery extends DbQueryCore {};
abstract class Db extends DbCore {};
class DbPDO extends DbPDOCore {};
class DbMySQLi extends DbMySQLiCore {};
class Currency extends CurrencyCore {};
class ImageType extends ImageTypeCore {};
class Attachment extends AttachmentCore {};
class Upgrader extends UpgraderCore {};
class PrestaShopCollection extends PrestaShopCollectionCore {};
class CustomerPersister extends CustomerPersisterCore {};
class CustomerAddressForm extends CustomerAddressFormCore {};
class CustomerForm extends CustomerFormCore {};
class CustomerAddressFormatter extends CustomerAddressFormatterCore {};
class CustomerFormatter extends CustomerFormatterCore {};
class CustomerLoginForm extends CustomerLoginFormCore {};
class CustomerLoginFormatter extends CustomerLoginFormatterCore {};
class CustomerAddressPersister extends CustomerAddressPersisterCore {};
class FormField extends FormFieldCore {};
abstract class AbstractForm extends AbstractFormCore {};
class CMS extends CMSCore {};
class SmartyResourceModule extends SmartyResourceModuleCore {};
class SmartyResourceParent extends SmartyResourceParentCore {};
class SmartyDevTemplate extends SmartyDevTemplateCore {};
class SmartyCustomTemplate extends SmartyCustomTemplateCore {};
class TemplateFinder extends TemplateFinderCore {};
class SmartyCustom extends SmartyCustomCore {};
class CustomerMessage extends CustomerMessageCore {};
class Category extends CategoryCore {};
class Configuration extends ConfigurationCore {};
class Customer extends CustomerCore {};
class CustomerAddress extends CustomerAddressCore {};
class Media extends MediaCore {};
class CMSCategory extends CMSCategoryCore {};
class PhpEncryption extends PhpEncryptionCore {};
class Supplier extends SupplierCore {};
abstract class ModuleGraphEngine extends ModuleGraphEngineCore {};
abstract class Module extends ModuleCore {};
abstract class ModuleGrid extends ModuleGridCore {};
abstract class CarrierModule extends CarrierModuleCore {};
abstract class ModuleGraph extends ModuleGraphCore {};
abstract class ModuleGridEngine extends ModuleGridEngineCore {};
class Translate extends TranslateCore {};
class TranslatedConfiguration extends TranslatedConfigurationCore {};
class Curve extends CurveCore {};
class PrestaShopLogger extends PrestaShopLoggerCore {};
class Context extends ContextCore {};
class CMSRole extends CMSRoleCore {};
class Employee extends EmployeeCore {};
class Notification extends NotificationCore {};
class SpecificPrice extends SpecificPriceCore {};
class Page extends PageCore {};
class Access extends AccessCore {};
class Tab extends TabCore {};
class SpecificPriceRule extends SpecificPriceRuleCore {};
class ConfigurationTest extends ConfigurationTestCore {};
abstract class PaymentModule extends PaymentModuleCore {};
class ConfigurationKPI extends ConfigurationKPICore {};
class Delivery extends DeliveryCore {};
class WarehouseProductLocation extends WarehouseProductLocationCore {};
class Warehouse extends WarehouseCore {};
class StockAvailable extends StockAvailableCore {};
class SupplyOrderDetail extends SupplyOrderDetailCore {};
class SupplyOrderReceiptHistory extends SupplyOrderReceiptHistoryCore {};
abstract class StockManagerModule extends StockManagerModuleCore {};
class StockMvtWS extends StockMvtWSCore {};
class SupplyOrderHistory extends SupplyOrderHistoryCore {};
class SupplyOrderState extends SupplyOrderStateCore {};
class StockMvtReason extends StockMvtReasonCore {};
class StockManagerFactory extends StockManagerFactoryCore {};
class Stock extends StockCore {};
class StockMvt extends StockMvtCore {};
class SupplyOrder extends SupplyOrderCore {};
class StockManager extends StockManagerCore {};
class Tag extends TagCore {};
class ProductDownload extends ProductDownloadCore {};
class ValidateConstraintTranslator extends ValidateConstraintTranslatorCore {};
class Risk extends RiskCore {};
class Customization extends CustomizationCore {};
class Guest extends GuestCore {};
class AttributeGroup extends AttributeGroupCore {};
class Meta extends MetaCore {};
class Gender extends GenderCore {};
class TreeToolbarLink extends TreeToolbarLinkCore {};
abstract class TreeToolbarButton extends TreeToolbarButtonCore {};
class TreeToolbarSearchCategories extends TreeToolbarSearchCategoriesCore {};
class TreeToolbarSearch extends TreeToolbarSearchCore {};
class TreeToolbar extends TreeToolbarCore {};
class Tree extends TreeCore {};
class ProductAssembler extends ProductAssemblerCore {};
class ProductSupplier extends ProductSupplierCore {};
class PhpEncryptionLegacyEngine extends PhpEncryptionLegacyEngineCore {};
class FeatureValue extends FeatureValueCore {};
class Validate extends ValidateCore {};
class Message extends MessageCore {};
class Address extends AddressCore {};
class SearchEngine extends SearchEngineCore {};
class Pack extends PackCore {};
class Cart extends CartCore {};
class Attribute extends AttributeCore {};
class Cookie extends CookieCore {};