<?=form_open('C=addons_extensions'.AMP.'M=save_extension_settings'.AMP.'file=deployment_hooks');?>
<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th style="width:50%"><?=lang('preference')?></th>
			<th style="width:50%"><?=lang('setting')?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="even">
			<td>
				<label for="get_token">
					<strong><?=lang('dh:get_token')?></strong>
					<br/>
					<small class="notice"><?=lang('dh:get_token_extra')?></small>
				</label>
			</td>
			<td><?=form_input('dh:get_token',$settings['dh:get_token'])?></td>
		</tr>
		<tr class="odd">
			<td>
				<label for="ip_array">
					<strong><?=lang('dh:ip_array')?></strong>
					<br/>
					<small class="notice"><?=lang('dh:ip_array_extra')?></small>
				</label>
			</td>
			<td><?=form_input('dh:ip_array',$settings['dh:ip_array'])?></td>
		</tr>
	</tbody>
</table>

<?=form_submit('submit',lang('dh:save_settings'),'class="submit"')?>
<?=form_close()?>