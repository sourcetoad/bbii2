function viewMessage(id, url) {
	var request = {'id':id};
	$('#bbii-message').toggleClass('spinner');
	$('#bbii-message').html('');
	$.post(url, request, function(data) {
		if(data.success == 'yes') {
			$("#bbii-message").html(data.html);
			$('#bbii-message').toggleClass('spinner');
		} else {
			alert(data.message);
			$('#bbii-message').toggleClass('spinner');
		}
	}, 'json');
	$('a').closest('tr').removeClass('selected');
	$("a[href='" + id + "']").closest('tr').addClass('selected');
}

function viewPost(id, url) {
	var request = {'id':id};
	$('#bbii-message').toggleClass('spinner');
	$('#bbii-message').html('');
	$.post(url, request, function(data) {
		if(data.success == 'yes') {
			$("#bbii-message").html(data.html);
			$('#bbii-message').toggleClass('spinner');
		} else {
			alert(data.message);
			$('#bbii-message').toggleClass('spinner');
		}
	}, 'json');
}

function deletePost(url) {
	var request = {'ajax': '1'};
	$.ajax({
		url: url,
		type: "GET",
		dataType: "json",
		data: request
	});
}

function reportPost(id) {
	$('#BbiiMessage_post_id').val(id);
	$('#dlgReportForm').dialog('open');
}

function sendReport() {
	var formdata = $('#report-form').serialize();
	var url = $('#url').val();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			alert(data.message);
			$("#dlgReportForm").dialog('close');
		} else {
			alert(data.message);
			$("#dlgReportForm").dialog('close');
		}
	}, 'json');
}

function banIp(id, url) {
	url = url + /id/ + id;
	$.get(url);
}

function refreshTopics(obj, url) {
	$.post(url, {'id': obj.value}, function(data) {
		if(data.success == 'yes') {
			$('#BbiiTopic_merge').html(data.option);
		} else {
			alert(data.message);
		}
	}, 'json');
	return false;
}

function upvotePost(id, url) {
	$.post(url, {'id': id}, function(data) {
		if(data.success == 'yes') {
			$('#upvote_'+id).replaceWith(data.html);
		}
	}, 'json');
	return false;
}

// Poll functions
function showPoll() {
	togglePoll();
	$('#addPoll').val('yes');
}

function hidePoll() {
	togglePoll();
	$('#addPoll').val('no');
}

function togglePoll() {
	$('#poll-button').toggle();
	$('#poll-form').toggle();
}

function pollChange(obj) {
	var id = obj.id;
	var text = obj.value;
	if(text.length > 0) {
		addChoice();
	} else {
		removeChoice(id);
	}
}

function addChoice() {
	var allfilled = true;
	$('#poll-choices').children('input').each(function(){
		if(this.value.length == 0) {
			allfilled = false;
		}
	});
	if(allfilled) {
		var last_id = $('#poll-choices input:last').attr('id');
		var id = last_id.split('_')[1];
		id++;
		var html = '<input id="choice_'+id+'" type="text" name="choice['+id+']" value="" onchange="pollChange(this)" style="width:99%;" maxlength="80">';
		$('#poll-choices').append(html);
		$('#poll-choices input:last').focus();
	}
}

function removeChoice(id) {
	var n = $('#poll-choices').children('input').size();
	if(n > 2) {
		$('#'+id).remove();
		addChoice();
	}
}

function vote(url) {
	var formdata = $('#bbii-poll-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#poll").html(data.html);
		}
	}, 'json');
}

function changeVote(poll_id, url) {
	var formdata = {'poll_id':poll_id};
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#poll").html(data.html);
		}
	}, 'json');
}

function editPoll(poll_id, url) {
	var formdata = {'poll_id':poll_id};
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#poll").html(data.html);
			$('#expiredate').datepicker($.extend({showMonthAfterYear:false},$.datepicker.regional[language],
				{'altField':'#BbiiPoll_expire_date','altFormat':'yy-mm-dd','showAnim':'fold','defaultDate':7,'minDate':1}
			));
		}
	}, 'json');
}

var BBii = {
	changeTopic: function(url) {
		var formdata = $('#update-topic-form').serialize();
		$.post(url, formdata, function(data) {
			if(data.success == 'yes') {
				$("#dlgTopicForm").dialog('close');
				$.fn.yiiListView.update('bbiiTopic');
			} else {
				settings = $('#update-topic-form').data('settings');
				$.each(settings.attributes, function () {
					$.fn.yiiactiveform.updateInput(this, data.error, $('#update-topic-form'));
				});
			}
		}, 'json');
	},
	toggleForumGroup: function(id, url) {
		if($('#category_'+id).css('display') == 'none') {
			action = 'unset';
		} else {
			action = 'set';
		}
		$('#category_'+id).toggle('fold',{size:1},'fast');
		var data = {id:id,action:action};
		$.post(url, data, function(data) {}, 'json');

	},
	updateTopic: function(id, url) {
		$.post(url, {'id': id}, function(data) {
			if(data.success == 'yes') {
				$('#BbiiTopic_id').val(id);
				$('#BbiiTopic_forum_id').val(data.forum_id);
				$('#BbiiTopic_title').val(data.title);
				$('#BbiiTopic_locked').val(data.locked);
				$('#BbiiTopic_sticky').val(data.sticky);
				$('#BbiiTopic_global').val(data.global);
				$('#BbiiTopic_merge').html(data.option);
				$("#dlgTopicForm").dialog('open');
			} else {
				alert(data.message);
				$("#dlgTopicForm").dialog('close');
			}
		}, 'json');
	},
	watchTopic: function(topicId, postId, url) {
		$.post(url, {'topicId': topicId, 'postId': postId}, function(data) {
			if(data.success == 'yes') {
				$('#watch').toggle();
				$('#unwatch').toggle();
			} else {
				
			}
		}, 'json');
	}
}