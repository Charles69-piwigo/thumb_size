<div class="titrePage">
<h2>Thumb Batch Size</h2>
</div>

<form action="" method="post">

<fieldset id="thumb_batch_size">
<legend>{'Configuration'|@translate}</legend>


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
  
  <tr>
    <td align="left">{'Dimension popup Album'|@translate} : &nbsp;&nbsp;</td>
</tr>
<tr> 
    <td align="left">{'Largeur'|@translate} : &nbsp;&nbsp;</td>
    <td><input type="text" size="2" maxlength="3" name="larg" value="{$ALBUM_LA}">&nbsp;px</td>
</tr>
<tr>   
    <td align="left">{'Hauteur'|@translate} : &nbsp;&nbsp;</td>
    <td><input type="text" size="2" maxlength="3" name="haut" value="{$ALBUM_HA}">&nbsp;px</td>
    </td>
  </tr>

</table>
</fieldset>

<p>

  <input type="submit" style="width: 180px;" name="submit" value="{'Valider sélections'|@translate}">
</br> </br>  
  <input type="submit" style="width: 180px;" name="defaut" value="{'Valeurs recommandées'|@translate}">
</br> </br>
  <input type="submit" style="width: 180px;" name="reinit" value="{'Valeurs défaut Piwigo'|@translate}">
</p>
</form>






