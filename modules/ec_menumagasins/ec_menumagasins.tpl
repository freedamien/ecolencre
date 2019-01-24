<script>
	function changemagasin(link_rewrite){
		window.location.assign("/magasins/" + link_rewrite);
	}
</script>

<select name="id_store" id="id_store" onchange="changemagasin(this.options[this.selectedIndex].value);">
	<option value=""></option>
    {foreach from=$magasins item=magasin}
		<option value="{$magasin.link_rewrite}">{$magasin.name}</option>
    {/foreach}
</select>
