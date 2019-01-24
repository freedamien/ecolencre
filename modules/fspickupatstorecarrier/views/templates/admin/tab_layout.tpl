{**
 *  2017 ModuleFactory.co
 *
 *  @author    ModuleFactory.co <info@modulefactory.co>
 *  @copyright 2017 ModuleFactory.co
 *  @license   ModuleFactory.co Commercial License
 *}

<div class="row">
    <div id="fspasc_tabs" class="col-lg-2 col-md-3">
        <div class="list-group">
            {foreach from=$fspasc_tab_layout item=fspasc_tab}
                <a class="list-group-item{if $fspasc_active_tab == $fspasc_tab.id} active{/if}"
                   href="#{$fspasc_tab.id|escape:'htmlall':'UTF-8'}"
                   aria-controls="{$fspasc_tab.id|escape:'htmlall':'UTF-8'}" role="tab" data-toggle="tab">
                    {$fspasc_tab.title|escape:'htmlall':'UTF-8'}
                </a>
            {/foreach}
        </div>
    </div>
    <div class="col-lg-10 col-md-9">
        <div class="tab-content">
            {foreach from=$fspasc_tab_layout item=fspasc_tab}
                <div role="tabpanel" class="tab-pane{if $fspasc_active_tab == $fspasc_tab.id} active{/if}" id="{$fspasc_tab.id|escape:'htmlall':'UTF-8'}">
                    {$fspasc_tab.content|escape:'html':'UTF-8'|fspascCorrectTheMess}
                </div>
            {/foreach}
        </div>
    </div>
    <div class="clearfix"></div>
</div>