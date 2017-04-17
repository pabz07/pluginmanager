<h1>Plugin Manager</h1>
<?php
	$groups = get_transient("plugin_manage_groups");
	$allPlugins = getAllPlugins();
	if(empty($groups)) {
		$groups = [
			"Others" => [
				"slug" => "others",
				"name" => "Others",
				"plugins" => $allPlugins
			]
		];
		set_transient("plugin_manage_groups", $groups);
	}

	if(isset($_POST["group_name"])) {
		if(!in_array($_POST["group_name"], $groups)) {
			$groupName = $_POST["group_name"];
			$groups[$groupName] = [
				"slug" => strtolower(str_replace(" ", "_", $groupName)),
				"name" => $groupName,
				"plugins" => []
			];
			set_transient("plugin_manage_groups", $groups);
		}
	}
?>
<?php if(isset($message)) : ?>
	<div class="alert alert-<?php echo $message["type"]; ?>" role="alert"><?php echo $message["content"]; ?></div>
<?php endif; ?>
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#groups-tab" aria-controls="groups-tab" role="tab" data-toggle="tab">Groups</a></li>
	<li role="presentation"><a href="#plugins-tab" aria-controls="plugins-tab" role="tab" data-toggle="tab">Plugins</a></li>
</ul>
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="groups-tab">
		<div class="panel-group" id="group-accordion" role="tablist" aria-multiselectable="true">
			<div class="row">
				<div class="col-lg-8">
					<?php foreach($groups as $index => $group) : ?>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="group-<?php echo $group["slug"]; ?>-heading">
								<h4 class="panel-title">
							        <a role="button" class="accordion-toggle" data-toggle="collapse" data-parent="#group-accordion" href="#group-<?php echo $group["slug"]; ?>" aria-expanded="true" aria-controls="collapseOne">
							        	<?php echo $group["name"]; ?>
							        </a>
							    </h4>
							</div>
							<div id="group-<?php echo $group["slug"]; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="group-<?php echo $group["slug"]; ?>-heading">
				      			<div class="panel-body">
				      				<table class="table table-hover">
				      					<tr>
				      						<th><input class="checkall" type="checkbox" name="checkall" id="checkall-<?php echo $index; ?>"></th>
				      						<th><p>Plugin</p></th>
				      						<th><p>Description</p></th>
				      						<th><p><select id="move_to_<?php echo $group["slug"]; ?>">
				      								<option>-Move to-</option>
													<?php foreach($groups as $g) : ?>
														<?php if($g["name"] != $group["name"]) : ?>
															<option value="<?php echo $g["name"]; ?>"><?php echo $g["name"]; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select></p></th>
				      					</tr>
									<?php foreach($group["plugins"] as $key => $plugin) : ?>
										<tr class="plugin-row">
											<td><input type="checkbox" name="<?php echo $key; ?>" id="plugin-<?php echo $group["slug"] . "-" . $key; ?>"></td>
											<td>
												<p><b><?php echo $plugin["Name"]; ?></b></p>
												<a href="admin.php?page=plugin_manager_options&plugin=<?php echo urldecode($key); ?>&type=<?php echo $plugin["is_active"] ? "deactivate" : "activate"; ?>"><?php echo $plugin["is_active"] ? "Deactivate" : "Activate"; ?></a>
											</td>
											<td class="description">
												<p><?php echo $plugin["Description"]; ?></p>
												<p>By: <a href="<?php echo $plugin["AuthorURI"]; ?>"><?php echo $plugin["Author"]; ?></a></p>
											</td>
											<td>
												<input type="hidden" class="plugin_name" value="<?php echo $key; ?>" />
												<input type="hidden" class="current_group" value="<?php echo $group["name"]; ?>" />
												<select class="move_to_select"> id="move_to_<?php echo $group["slug"]; ?>">
													<option>-Move to-</option>
													<?php foreach($groups as $g) : ?>
														<?php if($g["name"] != $group["name"]) : ?>
															<option value="<?php echo $g["name"]; ?>"><?php echo $g["name"]; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
											</td>
										</tr>
									<?php endforeach; ?>
									</table>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading">Create Group</div>
						<div class="panel-body">
							<form action="admin.php?page=plugin_manager_options" class="form-horizontal" method="POST">
								<input type="hidden" name="action" value="plugin_manager_options" />
								<div class="form-group">
								    <label for="group-name-input" class="col-sm-3 control-label">Group Name</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" id="group-name-input" placeholder="Group Name" name="group_name">
									</div>
								</div>
								<div class="form-group">
									<label for="group-name-input" class="col-sm-3 control-label"></label>
									<div class="col-sm-3">
										<input type="submit" class="form-control btn btn-primary" id="group-submit" placeholder="Group Name">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane active" id="plugins-tab">
	</div>
</div>