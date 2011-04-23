<?php

if (empty($deployment_posts))
{
	
	echo '<p class="notice">' . lang('dh:log_is_empty') . '</p>';
	
} else {
	
	$this->table->set_template($cp_table_template);
	
	$this->table->set_heading(
		array(
			'data' => lang('dh:time'),
			'style' => 'width:150px'
		),
		array(
			'data' => lang('ip_address'),
			'style' => 'width:100px'
		),
		lang('dh:log')
	);
	
	foreach ($deployment_posts as $post)
	{
		$this->table->add_row(
			$this->localize->set_human_time($post->deploy_timestamp,TRUE,TRUE),
			$post->deploy_ip,
			ol(unserialize($post->deploy_data))
		);
	}
	
	echo $this->table->generate();
	
	echo $pagination;
	
}

?>