DONE:
class BbiiForum extends BbiiAR {
	public function scopes() {
		return array(
			'categories' => array(
				'condition' => 'type = 0',
				'order' => 'sort',
			),
			'category' => array(
				'condition' => 'type = 0',
			),
			'forum' => array(
				'condition' => 'type = 1',
			),
			'public' => array(
				'condition' => 'public = 1',
			),
			'sorted' => array(
				'order' => 'sort',
			),
		);
	}
}

DONE:
class BbiiMember extends BbiiAR {
	public function scopes() {
		$recent = date('Y-m-d H:i:s', time() - 900);
		return array(
			'present' => array(
				'order' => 'last_visit DESC',
				'condition' => "last_visit > '$recent'",
			),
			'show' => array(
				'condition' => 'show_online = 1',
			),
			'hidden' => array(
				'condition' => 'show_online = 0',
			),
			'newest' => array(
				'order' => 'first_visit DESC',
				'limit' => 1,
			),
			'moderator' => array(
				'condition' => 'moderator = 1',
			),
		);
	}
}

DONE:
class BbiiMembergroup extends BbiiAR {
	public function scopes() {
		return array(
			'specific' => array(
				'condition' => 'id > 0',
			),
		);
	}
}

DONE:
class BbiiMessage extends BbiiAR {
	public function scopes() {
		return array(
			'inbox' => array(
				'condition' => 'inbox = 1',
			),
			'outbox' => array(
				'condition' => 'outbox = 1',
			),
			'unread' => array(
				'condition' => 'read_indicator = 0',
			),
			'report' => array(
				'condition' => 'sendto = 0',
			),
		);
	}
}

DONE:
class BbiiPost extends BbiiAR {
	public function scopes() {
		return array(
			'approved' => array(
				'condition' => 'approved = 1',
			),
			'unapproved' => array(
				'condition' => 'approved = 0',
			),
		);
	}
}

DONE:
class BbiiSession extends BbiiAR {
	public function scopes() {
		$recent = date('Y-m-d H:i:s', time() - 900);
		return array(
			'present' => array(
				'condition' => "last_visit > '$recent'",
			),
		);
	}
}

DONE:
class BbiiSpider extends BbiiAR {
	public function scopes() {
		$recent = date('Y-m-d H:i:s', time() - 900);
		return array(
			'present' => array(
				'order' => 'last_visit DESC',
				'condition' => "last_visit > '$recent'",
			),
		);
	}
}