/**
 * Javascript functios for Groups
 *
 */

 $(document).ready(function() {
 	$(".checkall").change(function(evt) {
 		if($(this).is(":checked")) {
 			$(this).parent().parent().siblings().find("input[type='checkbox']").attr("checked", "checked");
 		} else {
 			$(this).parent().parent().siblings().find("input[type='checkbox']").removeAttr("checked");
 		}
 	});

 	$(".move_to_select").on("change", function(evt){
 		$.ajax({
 			url: ajaxurl,
 			data: {
 				action: "move_plugin_to_group",
 				plugin: $(this).siblings(".plugin_name").val(),
 				from: $(this).siblings(".current_group").val(),
 				to: $(this).val()
 			},
 			type: "POST",
 			dataType: "JSON",
 			sucess: function(res) {
 				console.log(res);
 			},
 			error: function(res) {

 			}
 		})
 	});
 });