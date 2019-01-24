{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<div id="fspasc_help" class="panel">
    <div class="panel-heading">
        <span>{l s='Help' mod='fspickupatstorecarrier'}</span>
    </div>
    <div class="form-wrapper clearfix">
        Thank you for using our module. For the best user experience we provide some examples and information.
        If you need more help, please feel free to <a href="{$fspasc_contact_us_url|escape:'html':'UTF-8'|fspascCorrectTheMess}" target="_blank">contact us</a>.
        <br />
        <h2>Getting Started</h2>
        <p>
            When you have installed the module, automatically creates a new carrier added to all of your zones. It also enables
            all your stores for pickup point.
        </p>
        <h2>Configure the module</h2>
        <h4>General Settings</h4>
        <ul>
            <li>Enable/Disable map during the ordering process</li>
            <li>Google Maps API Key</li>
            <li>Map height in pixels</li>
            <li>Map zoom level</li>
            <li>Default store</li>
            <li>Enable/Disable stores for pickup point</li>
            <li>Sort stores alphabetically</li>
            <li>Display phone number</li>
        </ul>
        <br />
        <h4>Email Notification Settings</h4>
        <ul>
            <li>Enable/Disable new order notification to the selected store's email address</li>
            <li>Mail alerts module integration - if you use Mail Alerts by PrestaShop, just enable this for better user experience</li>
        </ul>
        <br />
        <h4>Pickup Date/Time Settings</h4>
        <ul>
            <li>Enable/Disable pickup date selection for customers</li>
            <li>Set the minimum open days what the customer needs to wait before can pickup the package</li>
            <li>Set the maximum open days until the customer can pickup the package. Relative to the next available date</li>
            <li>Set cut off time - after this time the minimum days rolls over to the next available day</li>
            <li>Date/Time format - see filed description for more info</li>
            <li>Enable/Disable pickup time selection in addition for customers</li>
            <li>Set the minimum hours what the customer needs to wait before can pickup the package</li>
        </ul>
        <br />
        <h2>Configure carrier</h2>
        <p>
            Navigate to Shipping -> <a href="{$fspasc_help_carriers_url|escape:'html':'UTF-8'|fspascCorrectTheMess}">Carriers</a> and click edit on the <a href="{$fspasc_help_carrier_url|escape:'html':'UTF-8'|fspascCorrectTheMess}">Pickup at Store carrier</a>.
            Here you can configure as any other carrier in the PrestaShop system (names, location and costs, size, weight, group).
        </p>
        <br />
        <h2>Configure store</h2>
        <p>
            Navigate to Shop Parameters -> Contact -> <a href="{$fspasc_help_stores_url|escape:'html':'UTF-8'|fspascCorrectTheMess}">Stores</a>.
            Here you can add, edit or remove store which is available for pickup point.
            You can change name, address, coordinates, status, picture and opening hours. This information appears in the maps info box. The store's email address is used to send new order admin notification email.
            You can add multiple email address by separating them with "," (comma).
        </p>
        <p>
            To setup opening hours, please use the following formats:
        </p>
        <ul>
            <li>9:00 - 19:00</li>
            <li>09:00 - 19:00</li>
            <li>9:00AM - 7:00PM</li>
            <li>9:00am - 7:00pm</li>
            <li>9:00am - 1:00pm and 3:00pm - 7:00pm</li>
            <li>9:00am - 1:00pm, 3:00pm - 7:00pm</li>
            <li>9:00 - 13:00 | 15:00 - 19:00</li>
            <li>For closed, just use any string or leave it empty</li>
        </ul>
        <p>
            Be sure have at lease one space between each time block as in the examples and no space between time and the "am", "pm" modifier.
        </p>
        <br />
        <h2>Enhanced Delivery Slip Export</h2>
        <p>
            We just improved the Delivery Slip export tool with a store as filter when the selected carrier is the Pickup at Store carrier.
        </p>
        <br />
        <h2>Store Importer</h2>
        <p>
            To start importing you need to prepare an import CSV file, for a start please use our <a href="{$fspasc_help_sample_csv_url|escape:'html':'UTF-8'|fspascCorrectTheMess}" target="_blank">Store Import Sample CSV</a>.
            The store name behaves as a unique identifier, so you can update exist stores with the importer. Please keep the columns' name.<br />
            <br />
            <strong>The import is only successful if contains NO invalid values.</strong><br />
            <br />
            If an error occurs an error message displays the row number, column name and a invalid value as well, it helps you easily find and fix the invalid values.
        </p>
        <br />
        <h4>Required Columns:</h4>
        <strong>Name</strong>
        <ul>
            <li>The name of the store.</li>
            <li>Must be a valid generic name.</li>
            <li>Example: My Cool Store</li>
        </ul>
        <strong>Address</strong>
        <ul>
            <li>The address of the store.</li>
            <li>Must be a valid address.</li>
            <li>Example: Test st. 1</li>
        </ul>
        <strong>City</strong>
        <ul>
            <li>The city of the store.</li>
            <li>Must be a valid city name.</li>
            <li>Example: Miami</li>
        </ul>
        <strong>Postcode</strong>
        <ul>
            <li>The postcode of the store.</li>
            <li>Must be a valid postcode regarding to the country's zip/postal code format.</li>
            <li>Example: 33013</li>
        </ul>
        <strong>Country</strong>
        <ul>
            <li>The country of the store.</li>
            <li>The valid country names listed <a href="{$fspasc_help_countries_url|escape:'html':'UTF-8'|fspascCorrectTheMess}">here</a>.</li>
            <li>Example: United States</li>
        </ul>
        <strong>State</strong>
        <ul>
            <li>The state of the store.</li>
            <li>Only required if the country requires a state, other way leave it blank.</li>
            <li>The valid state names listed <a href="{$fspasc_help_states_url|escape:'html':'UTF-8'|fspascCorrectTheMess}">here</a>.</li>
            <li>Example: Florida</li>
        </ul>
        <strong>Active</strong>
        <ul>
            <li>The status of the store (0/1).</li>
            <li>"0" - Inactive, "1" - Active</li>
        </ul>
        <br />
        <h4>Optional Columns:</h4>
        <strong>Address2</strong>
        <ul>
            <li>The second address line of the store.</li>
            <li>Must be a valid address.</li>
            <li>Example: Test st. 1</li>
        </ul>
        <strong>Latitude</strong>
        <ul>
            <li>The latitude (coordinate) of the store.</li>
            <li>Must be a valid coordinate.</li>
            <li>Example: 23.0243223</li>
        </ul>
        <strong>Longitude</strong>
        <ul>
            <li>The longitude (coordinate) of the store.</li>
            <li>Must be a valid coordinate.</li>
            <li>Example: -83.42123322</li>
        </ul>
        <strong>Phone</strong>
        <ul>
            <li>The phone number of the store.</li>
            <li>Must be a valid phone number.</li>
            <li>Example: +1 (800) 555-3247</li>
        </ul>
        <strong>Fax</strong>
        <ul>
            <li>The fax number of the store.</li>
            <li>Must be a valid phone number.</li>
            <li>Example: +1 (800) 555-3247</li>
        </ul>
        <strong>Email</strong>
        <ul>
            <li>The email address of the store.</li>
            <li>Must be a valid email address.</li>
            <li>This email address is used to send new order admin notification email.</li>
            <li>You can add multiple email address by separating the address with "," (comma).</li>
        </ul>
        <strong>Note</strong>
        <ul>
            <li>The note of the store.</li>
            <li>Must be a valid clean html text.</li>
        </ul>
        <strong>ImageURL</strong>
        <ul>
            <li>The image/logo of the store.</li>
            <li>Must be a valid absolute url to the image, which is accessible through internet.</li>
            <li>If you import images, please regenerate all image thumbnails for Stores <a href="{$fspasc_help_images_url|escape:'html':'UTF-8'|fspascCorrectTheMess}">here</a></li>
        </ul>
        <strong>Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday</strong>
        <ul>
            <li>The opening hours of the store per day.</li>
            <li>Must be a valid opening hour, please see "Configure store" section of the help.</li>
        </ul>
        <br />
        <h2>Extension</h2>
        <h4>If you use along with our Pay in Store When Pickup (Payment Module)</h4>
        <ul>
            <li>Custom Order State: You can create a custom Order Status for this payment, or you can use an exist one.</li>
            <li>Pickup Location Store: Select a store to display additional information during the checkout process (map, address).</li>
            <li>Change Delivery Address: Delivery Address automatically changed to the selected store’s address.</li>
            <li>Carrier Restriction: You can select a carrier where you want to enable this payment.</li>
            <li>In-Store payment options with icons.</li>
            <li>Payment Method with In-Store Payment Option Icons.</li>
            <li>Order Confirmation page with pickup store address and map.</li>
        </ul>
        <a href="https://addons.prestashop.com/en/22620-pay-in-store-when-pickup.html" target="_blank">See Pay in Store When Pickup Module on PrestaShop Addons Marketplace</a>
        <br />
        <br />
        <br />
        <a id="fspasc-developed-by" href="https://addons.prestashop.com/en/116_modulefactory" target="_blank">
            <img src="{$fspasc_module_base_url|escape:'html':'UTF-8'}views/img/help_footer_1280x170.jpg">
        </a>
    </div>
</div>
