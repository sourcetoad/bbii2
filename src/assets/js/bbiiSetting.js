/** Sorting function
 * @param obj HtmlElement Object
 * @param url string
*/
function Sort(obj, url) {
	var sort = $('#'+obj.id).sortable('serialize');
	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data: sort,
		success: function(data){
		},
		error: function(error){
			 console.log("Error:");
			 console.log(error);
		}
	});
}

/** Open dlgEditForum for editing Forum
 * @param id integer 
 * @param val string
 */
function editForum(id, val, url) {
	var request = {'id':id};
	$("#dlgEditForum").dialog('option', 'title', val);
	$('#BbiiForum_cat_id').show();
	$('#BbiiForum_public').show();
	$('#BbiiForum_locked').show();
	$('#BbiiForum_moderated').show();
	$('#label_cat_id').show();
	$('#label_public').show();
	$('#label_locked').show();
	$('#label_moderated').show();
	$('#label_poll').show();
	$.getJSON(url, request, function(data){
		$('#BbiiForum_id').val(data.id);
		$('#BbiiForum_name').val(data.name);
		$('#BbiiForum_subtitle').val(data.subtitle);
		$('#BbiiForum_cat_id').val(data.cat_id);
		$('#BbiiForum_public').val(data.public);
		$('#BbiiForum_locked').val(data.locked);
		$('#BbiiForum_moderated').val(data.moderated);
		$('#BbiiForum_type').val(data.type);
		$('#BbiiForum_membergroup_id').val(data.membergroup_id);
		$('#BbiiForum_poll').val(data.poll);
	});
	$("#dlgEditForum").dialog('open');
}

/** Delete Forum or Category */
function deleteForum(url) {
	var val = confirmation[$('#BbiiForum_type').val()];
	var id = $('#BbiiForum_id').val();
	var request = {'id':id};
	if(confirm(val)) {
		$.post(url, request, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditForum").dialog('close');
				window.location = window.location;
			} else {
				alert(data.message);
			}
		}, 'json');
	}
}

/** Save Forum or Category */
function saveForum(url) {
	var formdata = $('#edit-forum-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#dlgEditForum").dialog('close');
			window.location = window.location;
		} else {
			settings = $('#edit-forum-form').data('settings');
			$.each(settings.attributes, function () {
				$.fn.yiiactiveform.updateInput(this, data.error, $('#edit-forum-form'));
			});
		}
	}, 'json');
}

/** Open dlgEditForum for editing Category
 * @param id integer 
 * @param val string
 */
function editCategory(id, val, url) {
	var request = {'id':id};
	$("#dlgEditForum").dialog('option', 'title', val);
	$('#BbiiForum_cat_id').hide();
	$('#BbiiForum_public').hide();
	$('#BbiiForum_locked').hide();
	$('#BbiiForum_moderated').hide();
	$('#label_cat_id').hide();
	$('#label_public').hide();
	$('#label_locked').hide();
	$('#label_moderated').hide();
	$.getJSON(url, request, function(data){
		$('#BbiiForum_id').val(data.id);
		$('#BbiiForum_name').val(data.name);
		$('#BbiiForum_subtitle').val(data.subtitle);
		$('#BbiiForum_type').val(data.type);
	});
	$("#dlgEditForum").dialog('open');
}

/** Open dlgEditMembergroup for creating or editing Membergroup
 * @param id integer 
 * @param url string
 */
function editMembergroup(id, url) {
	var request = {'id':id};
	if(id == undefined) {
			$('#BbiiMembergroup_name').val('');
			$('#BbiiMembergroup_description').val('');
			$('#BbiiMembergroup_min_posts').val('');
			$('#colorpickerField').val('');
			$('#colorpickerColor').css('backgroundColor', '#ffffff');
			$('#BbiiMembergroup_image').val('');
			$("#dlgEditMembergroup").dialog('open');
	} else {
		$.getJSON(url, request, function(data){
			$('#BbiiMembergroup_id').val(data.id);
			$('#BbiiMembergroup_name').val(data.name);
			$('#BbiiMembergroup_description').val(data.description);
			$('#BbiiMembergroup_min_posts').val(data.min_posts);
			$('#colorpickerField').val(data.color);
			$('#colorpickerColor').css('backgroundColor', '#' + data.color);
			$('#BbiiMembergroup_image').val(data.image);
		});
		$("#dlgEditMembergroup").dialog('open');
	}
}

/** Delete Membergroup */
function deleteMembergroup(url) {
	var id = $('#BbiiMembergroup_id').val();
	var request = {'id':id};
	if(confirm(confirmation)) {
		$.post(url, request, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditMembergroup").dialog('close');
				window.location = window.location;
			} else {
				alert(data.message);
			}
		}, 'json');
	}
}

/** Save Membergroup */
function saveMembergroup(url) {
	var formdata = $('#edit-membergroup-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#dlgEditMembergroup").dialog('close');
			window.location = window.location;
		} else {
			settings = $('#edit-membergroup-form').data('settings');
			$.each(settings.attributes, function () {
				$.fn.yiiactiveform.updateInput(this, data.error, $('#edit-membergroup-form'));
			});
		}
	}, 'json');
}

/** */
function changeModeration(obj, id, url) {
	var checked = 0;
	if($('#'+obj.id).attr('checked')) {
		checked = 1;
	}
	var request = {'id':id, 'moderator':checked};
	$.post(url, request, function(data) {}, 'json');
}

var BBiiSetting = {
	ChangeColor: function(obj) {
		var hex = $('#'+obj.id).val();
		var exp = new RegExp(/^[0-9a-f]{6}$/);
		if(exp.test(hex)) {
			$('#colorpickerColor').css('backgroundColor', '#' + hex);
		} else {
			hex = '000000';
			$('#'+obj.id).val(hex);
			$('#colorpickerColor').css('backgroundColor', '#' + hex);
		}
	},
	EditSpider: function(id,url) {
		var request = {'id':id};
		if(id == undefined) {
				$('#BbiiSpider_name').val('');
				$('#BbiiSpider_user_agent').val('');
				$("#dlgEditSpider").dialog('open');
		} else {
			$.getJSON(url, request, function(data){
				$('#BbiiSpider_id').val(data.id);
				$('#BbiiSpider_name').val(data.name);
				$('#BbiiSpider_user_agent').val(data.user_agent);
			});
			$("#dlgEditSpider").dialog('open');
		}
	},
	DeleteSpider: function(url) {
		var id = $('#BbiiSpider_id').val();
		var request = {'id':id};
		if(confirm(confirmation)) {
			$.post(url, request, function(data) {
				if(data.success == 'yes') {
					$("#dlgEditSpider").dialog('close');
					$.fn.yiiGridView.update('spider-grid');
				} else {
					alert(data.message);
				}
			}, 'json');
		}
	},
	SaveSpider: function(url) {
		var formdata = $('#edit-spider-form').serialize();
		$.post(url, formdata, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditSpider").dialog('close');
				$.fn.yiiGridView.update('spider-grid');;
			} else {
				settings = $('#edit-spider-form').data('settings');
				$.each(settings.attributes, function () {
					$.fn.yiiactiveform.updateInput(this, data.error, $('#edit-spider-form'));
				});
			}
		}, 'json');
	}
}