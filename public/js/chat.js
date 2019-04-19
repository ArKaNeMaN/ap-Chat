$(document).ready(function(){
	$('.chatSendBtn').click(sendMsg);
	
	$.ap.sendRequest(
		'chat', 'checkMsgs',
		{lastMsg: window.chatLastMsgId},
		(res) => {if(res === true) getNewMsgs();},
		function(){}, false,
		{timeout: 1000}
	);
	
	$('.chatInput').keypress(function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13') sendMsg();
	});
	
	$('.chatEmojiPicker').lsxEmojiPicker({
		width: 300,
		twemoji: true,
		onSelect: function(emoji){
			$('.chatInput').val($('.chatInput').val()+'[emoji='+emoji.name+']');
		}
	});
});

function sendMsg(){
	var isReq = $.ap.sendRequest(
		'chat', 'sendMsg',
		{msg: $('.chatInput').val()},
		(res) => {
			$('.chatSendBtn').html(btnHtml);
			if(res instanceof Object){
				if(res.status){
					$('.chatInput').val('');
					appendMsg(res.data.id, res.data.msg, res.data.sendTimeF, res.data.userInfo.name, res.data.userInfo.avatar, res.data.userInfo.id);
					chatScrollDown();
				}
				else iziToast.error({title: res.msg});
			}
			else{
				console.log(res);
				iziToast.error({title: 'Возникла непредвиденная ошибка!'});
			}
		},
		() => {$('.chatSendBtn').html(btnHtml);},
		true
	);
	if(!isReq) return;
	var btnHtml = $('.chatSendBtn').html();
	$('.chatSendBtn').html('<i class="fa fa-spinner fa-spin"></i>');
}

function getNewMsgs(){
	$.ap.sendRequest(
		'chat', 'getNewMsgs',
		{lastMsg: window.chatLastMsgId},
		(res) => {
			if(res instanceof Array){
				for(var i = 0; i < res.length; i++){
					appendMsg(res[i].id, res[i].msg, res[i].sendTimeF, res[i].userInfo.name, res[i].userInfo.avatar, res[i].userInfo.id);
				}
				chatScrollDown();
			}
		},
		function(){}, false,
		{timeout: 2000}
	);
}

function appendMsg(id, msg, sendTime, userName, userAvatar, userId){
	if($('#chatMsg-'+id).length || Number(id) <= window.chatLastMsgId) return false;
	$('.chatList').append('\
		<li id="chatMsg-'+id+'">\
			<img onclick="$.ap.redirect(\'profile\', {id: '+userId+'});" class="chatAvatar" src="'+window.panelHome+userAvatar+'" />\
			<div class="chatMsgCont">\
				<span class="chatSendTime">'+sendTime+'</span>\
				<b class="chatNick">'+userName+'<sup class="chatUserId">'+userId+'</sup></b>\
				<span class="chatMsg">'+msg+'</span>\
			</div>\
		</li>\
	');
	window.chatLastMsgId = Number(id);
}

function chatScrollDown(){
	$('.chatBlockBody').animate({scrollTop: $('.chatList').height()}, 50);
}

$('.chatBlockBody').ready(chatScrollDown);