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
		<tr class="even">
			<td>
				<label for="dh:http_auth">
					<strong><?=lang('dh:http_auth')?></strong>
					<br/>
					<small class="notice"><?=lang('dh:http_auth_extra')?></small>
				</label>
			</td>
			<td>
				<?=form_label(form_checkbox('dh:http_auth', '1', $settings['dh:http_auth']).NBS.lang('yes'))?>
			</td>
		</tr>
		<tr class="odd">
			<td>
				<label for="dh:http_auth_member_groups">
					<strong><?=lang('dh:http_auth_member_groups')?></strong>
					<br/>
					<small class="notice"><?=lang('dh:http_auth_member_groups_extra')?></small>
				</label>
			</td>
			<td>
				<fieldset id="http_auth_member_groups">
					<?php foreach ($member_groups as $value => $label) : ?>
					<?=form_label(form_checkbox('dh:http_auth_member_groups[]', $value, in_array($value, $settings['dh:http_auth_member_groups'])).NBS.$label, '', array('style' => 'display:block;'))?>
					<?php endforeach; ?>
				</fieldset>
			</td>
		</tr>
	</tbody>
</table>

<?=form_submit('submit',lang('dh:save_settings'),'class="submit"')?>
<?=form_close()?>