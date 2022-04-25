<?php

if ( empty( $posts ) ) {
	echo 'No records!';
	return;
}

?>

<style>
	.all_records {
		display: flex;
		flex-wrap: wrap;
	}
	.record_item {
		width: 30%;
		margin-right: 3%;
		margin-bottom: 35px;
		box-shadow: 0 0 22px -3px rgb(0 0 0 / 40%);
		border-radius: 4px;
		padding: 25px;
	}
	
	@media all and ( max-width: 992px ) {
		.record_item {
			width: 100%;
			margin-right: 0;
		}
	}

</style>

<?php
echo '<div class="all_records">';
foreach ( $posts as $record ) {
	
	$title = get_post_meta($record->ID, 'adv_title', true);
	$about = get_post_meta($record->ID, 'adv_about',  true);
	$organizer = get_post_meta($record->ID, 'adv_organizer', true);
	$timestamp = get_post_meta($record->ID, 'adv_timestamp', true);
	$email = get_post_meta($record->ID, 'adv_email', true);
	$address = get_post_meta($record->ID, 'adv_address', true);
	$latitude = get_post_meta($record->ID, 'adv_latitude', true);
	$longitude = get_post_meta($record->ID, 'adv_longitude', true);
	
	
	$now = time();
	$adv_date = strtotime($timestamp);
	
	$date_diff = $now - $adv_date;
	$date_diff = round($date_diff / (3600));
	
	$datetime1 = new DateTime($timestamp);
	
	$datetime2 = new DateTime(date('Y:m:d H:i:s'));
	
	$difference = $datetime1->diff($datetime2);
	
	$ended = ($difference->invert === 0) ? 'Ended before: ':'In ';
	$month = ($difference->m > 1) ? $difference->m .' months ': $difference->m .' month ';
	if ( $difference->m == 0 ) $month = ''; // don't show if it's 0
	$days = ($difference->d > 1) ? $difference->d .' days ': $difference->d .' day ';
	$hours = ($difference->h > 1) ? $difference->h.' hours ': $difference->h.' hour ';
	$min = ($difference->i > 1) ? $difference->i.' minutes ': $difference->i.' minute ';
	
	?>
	<div class="record_item">
		<h3><?php echo $title; ?></h3>
		<p><b>Organizer:</b> <?php echo $organizer; ?></p>
		<p><b>Email:</b> <?php echo $email; ?></p>
		<p><b>Address:</b> <?php echo $address; ?></p>
		<p><b>Time:</b> <?php echo $timestamp; ?></p>
		<p><b>Time:</b> <?php echo $ended . $month . $days . $hours . $min; ?></p>
	</div>
	
	<?php
}
echo '</div>';



$ttr = "";
if ( get_query_var( 'paged' ) ) {
	$ttr = 'paged';
} elseif ( get_query_var( 'page' ) ) {
	$ttr ='page';
}

$big = 999999999;
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var( $ttr ) ),
	'total' => $the_query->max_num_pages,
	'prev_text' => ' < ',
	'mid_size' => 5,
	'next_text' => ' > '
) );

?>