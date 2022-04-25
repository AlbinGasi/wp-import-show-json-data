<style>
	.input-wrapp {
		width: 350px;
	}
</style>
<div class="wrap">
	<h1>Loop options</h1>
	
	<form  method="post" action="options.php">
		<?php settings_fields( 'isjd-dev-test' ); ?>
		<?php do_settings_sections( 'isjd-dev-test' ); ?>
		<div class="wrapper_vtt" style="margin-bottom: 10px;padding-bottom: 5px;border-bottom: 2px solid #ddd;">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Enagle Plugin?
						<br>
						<span style="color:#616161;font-style:italic;font-size:12px;">(just for fun for now)</span>
					</th>
					<td><input class="input-wrapp" type="checkbox" name="isjd_enable" value="yes" <?php echo get_option('isjd_enable') == 'yes' ? 'checked' : ''; ?>>
					</td>
				</tr>

			</table>
		</div>
		<?php submit_button('Save'); ?>
	</form>
</div>
