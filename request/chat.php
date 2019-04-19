<?php
	require '../lib/engine_class.php';
	$eng = new engine(true);
	
	switch($_GET['action']){
		case 'sendMsg': {
			$eng->checkAccess(0);
			if(is_array($res = $eng->modules['chat']->sendMsg($eng->userid, $_POST['msg']))) $eng->ajaxReturn(['status' => true, 'data' => $res]);
			else $eng->ajaxReturn(['status' => false, 'msg' => 'Ошибка отправки сообщения']);
		}
		case 'checkMsgs': {
			if(!isset($_POST['lastMsg'])) $eng->ajaxReturn(false);
			$eng->ajaxReturn((bool) $eng->sql->select('chat', ['COUNT(*)'], '`id`>'.(int) $_POST['lastMsg'])[0]['COUNT(*)']);
		}
		case 'getNewMsgs': {
			if(!isset($_POST['lastMsg'])) $eng->ajaxReturn(false);
			$eng->ajaxReturn($eng->modules['chat']->getNewMsgs($_POST['lastMsg']));
		}
	}