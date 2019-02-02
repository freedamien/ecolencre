<div id="_desktop_user_info" class="js-dropdown hidden-sm-down">
  <div class="account-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">{l s='My Account' d='Shop.Theme.Actions'}<i class="material-icons expand-more">&#xE5C5;</i></div>
  <div class="user-info">
    {if $logged}
	  <a
        class="account"
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <span class="">{$customerName}</span>
      </a>
      <a
        class="logout"
        href="{$logout_url}"
        rel="nofollow"
      >
        {l s='Sign out' d='Shop.Theme.Actions'}
      </a>
     
    {else}
	 <a  class="creat-account"  href="{$urls.pages.register}" data-link-action="display-register-form">
          {l s='Create account' d='Shop.Theme.Actions'}
        </a>	
		
      <a class="login"
        href="{$my_account_url}"
        title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
      >
        <span class="signin">{l s='Sign in' d='Shop.Theme.Actions'}</span>
      </a>
    {/if}
  </div>
</div>