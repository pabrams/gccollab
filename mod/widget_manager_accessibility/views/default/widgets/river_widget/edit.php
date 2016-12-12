<?php
/**
 * Edit settings for river widget
 */

// dashboard widget has type parameter
$widget = $vars["entity"];
$widgetId = $widget->getGUID();
if (elgg_in_context('dashboard')) {
	if (!isset($vars['entity']->content_type)) {
		$vars['entity']->content_type = 'friends';
	}
	$params = array(
		'name' => 'params[content_type]',
		'value' => $vars['entity']->content_type,
        'id' => 'activity-'.$widgetId,
		'options_values' => array(
			'friends' => elgg_echo('river:widgets:friends'),
			'all' => elgg_echo('river:widgets:all'),
		),
	);
	$type_dropdown = elgg_view('input/select', $params);
	?>
	<div>
		<?php echo '<label for="activity-'.$widgetId.'">'.elgg_echo('river:widget:type').'</label>'; ?>:
		<?php echo $type_dropdown; ?>
	</div>
	<?php
}

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

$params = array(
	'name' => 'params[num_display]',
    'id' => 'activity2-'.$widgetId,
	'value' => $num_display,
	'options' => array(5, 8, 10, 12, 15, 20),
);
$num_dropdown = elgg_view('input/select', $params);

?>
<div>
	<?php echo '<label for="activity2-'.$widgetId.'">'.elgg_echo('widget:numbertodisplay').'</label>'; ?>:
	<?php echo $num_dropdown; ?>
</div>

<?php
// pass the context so we have the correct output upon save.
if (elgg_in_context('dashboard')) {
	$context = 'dashboard';
} else {
	$context = 'profile';
}

echo elgg_view('input/hidden', array(
	'name' => 'context',
	'value' => $context
));