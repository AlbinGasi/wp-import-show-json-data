<?php
wp_enqueue_media();
?>
<?php wp_enqueue_media(); ?>
<?php wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');?>
<table class="form-table">
	<tr>
		<td><?php _e('Multiple slider?','sportska'); ?></td>
		<td>
			<?php
			?>
			<input type="checkbox" name="cultural_multislider" value="yes" <?php echo (get_post_meta($post_c->ID, 'cultural_multislider', TRUE) == 'yes')?'checked':''; ?>> Yes<br>
			<input type="checkbox" name="cultural_multislider" value="no" <?php echo (get_post_meta($post_c->ID, 'cultural_multislider', TRUE) == 'no')?'checked':''; ?>> No
		</td>
	</tr>
	<tr>
		<td><?php _e('Galerija', 'sportska');?>
		</td>
		<td>
			<?php
			$post = get_post( $post_c->ID, OBJECT, 'edit' );
			$content = get_post_meta($post_c->ID, 'cultural_gallery', TRUE);
			$editor_id = 'cultural_gallery';
			wp_editor( $content, $editor_id );
			?>
		</td>
	</tr>

</table>

<div class="multi-slider-options-info">
	<h4 class="info-multiple-slider">Multiple slider options</h4>
	<em>This option will be used only if Multiple slider is enabled.</em>
</div>
<div id="multiSliderOptions">
	<?php
	$npName = get_post_meta($post_c->ID, 'cultural_multislider_name', TRUE);
	$npDescription = get_post_meta($post_c->ID, 'cultural_multislider_description', TRUE);
	$npImage =  get_post_meta($post_c->ID, 'cultural_multislider_image', TRUE);
	$npAuthor = get_post_meta($post_c->ID, 'cultural_multislider_image_author', TRUE);
	
	$allEmpty = true;
	if ( !empty($npName) /*&& !empty($npDescription) && !empty($npImage)*/ ) {
		for ($i = 0; $i < count($npName); $i++ ) {
			if ( !empty($npName[$i]) || !empty($npDescription[$i]) || !empty($npImage[$i]) ) {
				$allEmpty = false;
				?>
				<table class="form-table">
					<tbody>
					<tr>
						<td><span class="delete-table">x</span></td>
					</tr>
					<tr class="tr-row-er">
						<td><?php _e( 'Naziv', 'sportska' ); ?></td>
						<td>
							<input class="ff-tab-s" type="text" name="cultural_multislider_name[]" value="<?php echo $npName[ $i ]; ?>">
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Description', 'sportska' ); ?></td>
						<td>
							<textarea type="text" name="cultural_multislider_description[]"><?php echo $npDescription[$i]; ?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Image', 'sportska' ); ?></td>
						<td>
							<input class="ff-tab-s image_upload_attraction" type="text" name="cultural_multislider_image[]" value="<?php echo $npImage[ $i ]; ?>">
							<button class="set_custom_images button">Select file</button>
							<span class="attraction-prev-img-metabox"><img src="<?php echo $npImage[ $i ]; ?>"></span>
						</td>
					</tr>
					<tr>
						<td><?php _e('Photo author', 'sportska'); ?></td>
						<td><textarea class="ff-tab-s image_upload_attraction" type="text" name="cultural_multislider_image_author[]"><?php echo $npAuthor[ $i ]; ?></textarea></td>
					</tr>
					</tbody>
				</table>
				<?php
			} else {
				//$allEmpty = true;
			}
		}
	}
	if ( $allEmpty !== false ) {
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<td><span class="delete-table">x</span></td>
			</tr>
			<tr class="tr-row-er">
				<td><?php _e( 'Naziv', 'sportska' ); ?></td>
				<td>
					<input class="ff-tab-s" type="text" name="cultural_multislider_name[]">
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Description', 'sportska' ); ?></td>
				<td>
					<textarea type="text" name="cultural_multislider_description[]"></textarea>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Image', 'sportska' ); ?></td>
				<td>
					<input class="ff-tab-s image_upload_attraction" type="text" name="cultural_multislider_image[]">
					<button class="set_custom_images button">Select file</button>
					<span class="attraction-prev-img-metabox"></span>
				</td>
			</tr>
			<tr>
				<td><?php _e('Photo author', 'sportska'); ?></td>
				<td><textarea class="ff-tab-s image_upload_attraction" type="text" name="cultural_multislider_image_author[]"><?php echo $npAuthor[ $i ]; ?></textarea></td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	?>
</div>

<div class="slider_button_more">
	<button type="button" id="add_more_slider"><?php  _e('Add more','sportska'); ?></button>
</div>

<div id="getMultiSliderAppend" style="display: none;">
	<table class="form-table">
		<tbody>
		<tr>
			<td><span class="delete-table">x</span></td>
		</tr>
		<tr class="tr-row-er">
			<td><?php _e( 'Naziv', 'sportska' ); ?></td>
			<td>
				<input class="ff-tab-s" type="text" name="cultural_multislider_name[]">
			</td>
		</tr>
		<tr>
			<td><?php _e( 'Description', 'sportska' ); ?></td>
			<td>
				<textarea type="text" name="cultural_multislider_description[]"></textarea>
			</td>
		</tr>
		<tr>
			<td><?php _e( 'Image', 'sportska' ); ?></td>
			<td>
				<input class="ff-tab-s image_upload_attraction" type="text" name="cultural_multislider_image[]">
				<button class="set_custom_images button">Select file</button>
				<span class="attraction-prev-img-metabox"></span>
			</td>
		</tr>
		<tr>
			<td><?php _e('Photo author', 'sportska'); ?></td>
			<td><textarea class="ff-tab-s image_upload_attraction" type="text" name="cultural_multislider_image_author[]"><?php echo $npAuthor[ $i ]; ?></textarea></td>
		</tr>
		</tbody>
	</table>
</div>


<script>
  jQuery(document).ready(function( $ ) {
    // make it sortable
    $('#multiSliderOptions').sortable();

    // wp media
    if ($('.set_custom_images').length > 0) {
      if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
        $(document).on('click', '.set_custom_images', function(e) {
          e.preventDefault();
          var button = $(this);
          var input = button.prev();
          var imagePreview = button.next();
          wp.media.editor.send.attachment = function(props, attachment) {
            input.val(attachment.url);
            imagePreview.html('<img src="'+attachment.url+'">');
          };
          wp.media.editor.open(button);
          return false;
        });
      }
    }

    // add more slider
    $('#add_more_slider').click(function (e) {
      var content = $('#getMultiSliderAppend').html();
      $('#multiSliderOptions').append(content);
    });

    // delete table
    $(document).on('click', '.delete-table', function (e) {
      var deleteTable = confirm('Are you sure you want to delete this entry?');

      if ( deleteTable ) {
        $(this).parent().parent().parent().parent().fadeOut(300, function(){
          $(this).remove();
        });
      }
    });

    // Only one checkbox
    $("input:checkbox").on('click', function () {
      // in the handler, 'this' refers to the box clicked on
      var $box = $(this);
      if ($box.is(":checked")) {
        // the name of the box is retrieved using the .attr() method
        // as it is assumed and expected to be immutable
        var group = "input:checkbox[name='" + $box.attr("name") + "']";
        // the checked state of the group/box on the other hand will change
        // and the current value is retrieved using .prop() method
        $(group).prop("checked", false);
        $box.prop("checked", true);
      } else {
        $box.prop("checked", false);
      }
    });
  });
</script>