<?php

// Typically view are more HTML and less PHP. In this case I was building 
// tables with the CI table helper most of the time so it just made more 
// sense to use PHP over HTML. Don't fret. It's not breaking any rules

if (count($notices) > 0)
{
	echo heading(lang('dh:notice'),3);
	echo '<ul class="notice">';
	foreach ($notices as $notice) {
		echo '<li>'.$notice.'</li>';
	}
	echo '</ul><br/>';
}

if ($actions_registered)
{
	
	$this->table->set_template($cp_table_template);
	$this->table->set_heading(
		lang('dh:hook_location'),
		lang('dh:hook_url')
	);
	
	
	$this->table->add_row(
		array(
			'data' => heading(lang('dh:hook_instructions'),4),
			'colspan' => '2',
			'class' => 'box'
		)
	);
	
	
	$this->table->add_row(
		lang('dh:pre_deployment'),
		form_input('',$pre_action_url)
	);
	$this->table->add_row(
		lang('dh:post_deployment'),
		form_input('',$post_action_url)
	);
	
	echo $this->table->generate();
	
} else {

	echo "<p class='notice'>" . lang('dh:problem_installing_hooks') . "</p>";

}

echo br();

$this->table->clear();
$this->table->set_template($cp_table_template);

if ($pre_is_used)
{
	$this->table->set_heading(
		array(
			'data' => lang('version'),
			'style' => 'width:70px'
		),
		lang('dh:pre_deployment') .' '. lang('extensions')
	);
	
	foreach ($pre_extensions as $ext)
	{
		$this->table->add_row(
			$ext->version,
			$ext->name
		);
	}
} else {
	$this->table->set_heading(
		lang('dh:pre_deployment') .' '. lang('extension')
	);
	$this->table->add_row(
		lang('dh:hook_not_used')
	);
}

echo $this->table->generate();

$this->table->clear();
echo br();

if ($post_is_used)
{	
	$this->table->set_heading(
		array('data' => lang('version'), 'style' => 'width:70px' ),
		lang('dh:post_deployment') .' '. lang('extensions')
	);
	
	foreach ($post_extensions as $ext)
	{
		$this->table->add_row(
			$ext->version,
			$ext->name
		);
	}
} else {
	$this->table->set_heading(
		lang('dh:post_deployment') .' '. lang('extension')
	);
	$this->table->add_row(
		lang('dh:hook_not_used')
	);
}

echo $this->table->generate();

echo br();

echo "<p><a href=\""
      . BASE
      . AMP
      . "C=addons_extensions\">"
      . lang('dh:view_your_extensions')
      . "</a>"
      . lang('dh:to_make_changes')
      . "</p>";

/* End of file cp_index.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/views/cp_index.php */