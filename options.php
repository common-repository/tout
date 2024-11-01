<div class="wrap">
<h2>Tout Wordpress Plugin</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('tout_wordpress_plugin'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Tout Property ID:</th>
<td><input type="text" name="tout_property_id" value="<?php echo get_option('tout_property_id'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Custom code to run when a top-article match exists:</th>
<td><textarea name="tout_top_article_match_code" rows="4" cols="75"><?php echo get_option('tout_top_article_match_code'); ?></textarea></td>
</tr>

<tr valign="top">
<th scope="row">Custom code to run when a mid-article match exists:</th>
<td><textarea name="tout_mid_article_match_code" rows="4" cols="75"><?php echo get_option('tout_mid_article_match_code'); ?></textarea></td>
</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
