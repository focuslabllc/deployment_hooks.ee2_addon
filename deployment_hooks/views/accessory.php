<?php if (count($deploy_log)): ?>
	<table border="0" cellspacing="0" cellpadding="0" style="margin:10px 0">
		<tr>
			<?php foreach ($deploy_log as $log_item): ?>
				<td valign="top">
					<?=heading($this->localize->set_human_time($log_item->deploy_timestamp,TRUE,TRUE),3)?>
					<?=ol(unserialize($log_item->deploy_data))?>
				</td>
			<?php endforeach ?>
		</tr>
	</table>
<?php else: ?>
	<p><?=lang('dh:log_is_empty')?></p>
<?php endif; ?>


<p>
	<?='<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=deployment_hooks'.AMP.'method=log">'.lang('dh:full_deployment_log').'</a>';?>
	<?='<a href="'.BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=deployment_hooks">'.lang('dh:menu_home').'</a>';?>
</p>