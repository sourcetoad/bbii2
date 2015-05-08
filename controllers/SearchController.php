<?php

class SearchController extends BbiiController {
	public $search;
	Public $type;
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex() {
		$search = '';
		$choice = 0;
		$type = 0;
		if(isset($_POST['search'])) {
//			$search = trim(Html::encode($_POST['search']));
			$search = trim(filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING));
			Yii::$app->session['search'] = $search;
		} elseif(isset(Yii::$app->session['search'])) {
			$search = Yii::$app->session['search'];
		}
		$this->search = $search;
		if(isset($_POST['choice'])) {
			$choice = $_POST['choice'];
			if($choice != '0' && $choice != '1' && $choice != '2') {
				$choice = 0;
			}
			Yii::$app->session['choice'] = $choice;
		} elseif(isset(Yii::$app->session['choice'])) {
			$choice = Yii::$app->session['choice'];
		}
		if(isset($_POST['type'])) {
			$type = $_POST['type'];
			if($type != '0' && $type != '1' && $type != '2') {
				$type = 0;
			}
			Yii::$app->session['type'] = $type;
		} elseif(isset(Yii::$app->session['type'])) {
			$type = Yii::$app->session['type'];
		}
		$this->type = $type;
		if($type == 0 && strlen($search) < 2) {
			$condition = '1=2';
		} elseif($type == 0) {	// phrase
			if($choice == 1) {
				$condition = "subject LIKE '%$search%' OR title LIKE '%$search%'";
			} elseif($choice == 2) {
				$condition = "content LIKE '%$search%'";
			} else {
				$condition = "subject LIKE '%$search%' OR title LIKE '%$search%' OR content LIKE '%$search%'";
			}
		} elseif($type == 1) {	// any word
			$words = explode(' ', $search);
			$condition = '';
			foreach($words as $word) {
				if(strlen($word) > 1) {
					if($choice == 1) {
						$condition .= "subject LIKE '%$word%' OR title LIKE '%$word%'";
					} elseif($choice == 2) {
						$condition .= "content LIKE '%$word%'";
					} else {
						$condition .= "subject LIKE '%$word%' OR title LIKE '%$word%' OR content LIKE '%$word%'";
					}
					$condition .= ' OR ';
				}
			}
			if(strlen($condition) == 0) {
				$condition = '1=2';
			} else {
				$condition = substr($condition, 0, -4);
			}
		} else {	// all words
			$words = explode(' ', $search);
			$condition = '';
			foreach($words as $word) {
				if(strlen($word) > 1) {
					$condition .= '(';
					if($choice == 1) {
						$condition .= "subject LIKE '%$word%' OR title LIKE '%$word%'";
					} elseif($choice == 2) {
						$condition .= "content LIKE '%$word%'";
					} else {
						$condition .= "subject LIKE '%$word%' OR title LIKE '%$word%' OR content LIKE '%$word%'";
					}
					$condition .= ') AND ';
				}
			}
			if(strlen($condition) == 0) {
				$condition = '1=2';
			} else {
				$condition = substr($condition, 0, -5);
			}
		}
		$dataProvider = new ActiveDataProvider('BbiiPost', array(
			'criteria'=>array(
				'condition'=>$condition,
				'order'=>'create_time DESC',
				'with'=>'topic',
			),
			'pagination'=>array(
				'pageSize'=>10,
			),
		));

		$this->render('index', array(
			'dataProvider'=>$dataProvider,
			'search'=>$search,
			'choice'=>$choice,
			'type'=>$type,
		));
	}
	
	public function getString($string, $pos) {
		$needles = array('<a>','<b>','<br>','<i>','<hr>','<p>','<span>','<strong>','<u>');
		$string = strip_tags($string, '<a><b><br><i><hr><p><span><strong><u>');
		if(strlen($string) > $pos) {
			$min = strlen($string);
			foreach($needles as $needle) {
				$max = stripos($string, $needle, $pos);
				if($max !== false && $max < $min) {
					$min = $max;
				}
			}
			return substr($string, 0, $pos) . ' ...';
		}
		return $string;
	}
	
	public function getSearchedString($string, $pos) {
		$string = strip_tags($string, '<a><b><br><i><hr><p><span><strong><u>');
		$string = str_replace('</p>', '', $string);
		$array = explode('<p>', $string);
		$result = '';
		$search = $this->search;
		$psearch = preg_quote($this->search);
		foreach($array as $value) {
			if($this->type) {
				$words = explode(' ', $search);
			} else {
				$words = array($search);
			}
			$found = false;
			foreach($words as $word) {
				if(strlen($word) > 1 && stripos($value,$word)) {
					$found = true;
					$word = preg_quote($word);
					$value = preg_replace("/(?![^<]*>)($word)/ui", '<span class="highlight">\1</span>', $value);
				}
			}
			if($found) {
				$result .= '<p>... ' . $value . ' ...</p>';
			}
//			if(stripos($value,$search)) {
//				$result .= '<p>... ' . preg_replace("/(?![^<]*>)($psearch)/ui", '<span class="highlight">\1</span>', $value) . ' ...</p>';
//			}
		}
		if(strlen($result) > 0) {
			return $result;
		} else {
			return $this->getString($string, 500);
		}
	}
}