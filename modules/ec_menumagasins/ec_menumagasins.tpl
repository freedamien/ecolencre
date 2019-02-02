<script>
	function changemagasin(link_rewrite){
		window.location.assign( "/ecolencre/magasins/" + link_rewrite);
	}
</script>

<select name="id_store" id="id_store" onchange="changemagasin(this.options[this.selectedIndex].value);">
	<option value=""> </option>
    {foreach from=$magasins item=magasin}
		<option value="{$magasin.id_cms}-{$magasin.link_rewrite}">{$magasin.name}</option>
    {/foreach}
</select>
