<style>
#thumb_batch_size, #thumb_batch_size * {
  margin-left: 0 !important;
  text-align: left !important;
}

</style>

<div class="titrePage">
<h2>Thumb Batch Size</h2>
</div>

<form action="" method="post">

<fieldset id="thumb_batch_size">
<legend>{'Configuration'|@translate}</legend>

<h3>{'Gestion_miniatures'|@translate}</h3>
<table>


  <tr>
    <td align="left">{'Largeur des miniatures'|@translate} : &nbsp;&nbsp;</td>
    <td><input type="text" size="2" maxlength="3" name="largeur" value="{$LARGEUR}">&nbsp;px</td></td>
  </tr>

  <tr>
    <td align="left">{'Hauteur des miniatures'|@translate} : &nbsp;&nbsp;</td>
    <td><input type="text" size="2" maxlength="3" name="hauteur" value="{$HAUTEUR}">&nbsp;px</td>
  </tr>

  <tr>
    <td align="left">{'Qualité image'|@translate} : &nbsp;&nbsp;</td>
	<td>
	<select id="qualite" name="qualite">
  {foreach from=$qualite_op item=opt}
    <option value="{$opt.value}" {if $QUALITE == $opt.value}selected{/if}>{$opt.label}</option>
  {/foreach}
</select>
    </td>
  </tr>

  <tr>
    <td align="left">{'Aspect des miniatures'|@translate} : &nbsp;&nbsp;</td>
    <td><label><input type="radio" name="dimcrop" value='contain' {if $DIMCROP == 'contain'}checked="checked"{/if}> {'Redimensionnée'|@translate}</label> &nbsp;
        <label><input type="radio" name="dimcrop" value='cover' {if $DIMCROP == 'cover'}checked="checked"{/if}> {'Retaillée'|@translate}</label>
    </td>
  </tr>
  
</table>

<h3>{'Dimensions_Album'|@translate}</h3>
<table>
  <tr> 
    <td align="left">{'Largeur'|@translate} : &nbsp;&nbsp;</td>
    <td><input type="text" size="2" maxlength="3" name="larg" value="{$ALBUM_LA}">&nbsp;px</td>
  </tr>
  <tr>   
    <td align="left">{'Hauteur'|@translate} : &nbsp;&nbsp;</td>
    <td><input type="text" size="2" maxlength="3" name="haut" value="{$ALBUM_HA}">&nbsp;px</td>
  </tr>
</table>

</fieldset>

<p style="text-align: left; margin-left: 0;">

  <input type="submit" style="width: 180px !important;" name="submit" value="{'Valider'|@translate}">
</br> </br>  
  <input type="submit" style="width: 180px !important;" name="defaut" value="{'Valeurs_ok'|@translate}">
</br> </br>
  <input type="submit" style="width: 180px !important;" name="reinit" value="{'Valeurs_piwigo'|@translate}">
</p>
</form>






