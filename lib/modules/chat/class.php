<?php
	class chat{
		
		private $eng;
		private $sql;
		
		public function __construct(&$eng){
			$this->eng = &$eng;
			$this->sql = &$this->eng->sql;
		}
		
		public function sendMsg($userid, $msg, $attachs = null){
			if($userid < 0) return false;
			
			$msg = trim($msg);
			$msg = htmlspecialchars($msg);
			if(empty($msg)) return false;
			
			$msg = preg_replace('/\[emoji=(.*?)\]/i', '<i class="twa twa-2x twa-${1}"></i>', $msg);
			
			$attachments = $attachs;
			
			$curTime = time();
			
			$id = $this->sql->insert('chat', ['userid' => $userid, 'msg' => $msg, 'sendTime' => $curTime, 'attachments' => json_encode($attachments)]);
			return [
				'id' => $id,
				'userInfo' => $this->eng->getUserInfo($userid),
				'msg' => $msg,
				'sendTime' => $curTime,
				'sendTimeF' => $this->eng->timeFormat($curTime, true),
				'attachments' => $attachments,
			];
		}
		
		public function getMsgs($start = 0, $limit = 20, $getDeleted = false){
			$res = $this->sql->select('chat', '*', (bool) $getDeleted ? '' : ['deleted' => 0], 'id', false, (int) $start.', '.(int) $limit);
			if(!$res) return false;
			for($i = 0; $i < count($res); $i++) $this->formatData($res[$i]);
			return array_reverse($res);
		}
		
		public function getNewMsgs($lastMsg){
			$res = $this->sql->select('chat', '*', '`deleted`=0 AND `id`>'.$lastMsg, 'id', false);
			if(!$res) return false;
			for($i = 0; $i < count($res); $i++) $this->formatData($res[$i]);
			return array_reverse($res);
		}
		
		private function formatData(&$msgs){
			$msgs['attachments'] = json_decode($msgs['attachments'], true);
			$msgs['sendTimeF'] = $this->eng->timeFormat($msgs['sendTime'], true);
			$msgs['userInfo'] = $this->eng->getUserInfo($msgs['userid']);
		}
	}