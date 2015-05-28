<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiMembergroup;
use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiPoll;
use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiTopic;
use frontend\modules\bbii\models\BbiiTopicRead;

use frontend\modules\bbii\components\BbiiTopicsRead;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\User;

class ForumController extends BbiiController {
	public $poll;
	public $choiceProvider;
	public $voted;
	
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
				'actions' => array('createTopic', 'quote', 'reply', 'vote', 'displayVote', 'editPoll', 'updatePoll', 'update', 'upvote', 'markAllRead', 'watch', 'unwatch'),
				'users' => array('@'),
			),
			array('allow',
				'actions' => array('error', 'index', 'forum', 'topic','collapsed','setCollapsed'),
				'users' => array('*'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionIndex() {
		if (isset(Yii::$app->user->BbiiTopic_page)) {
			unset(Yii::$app->user->BbiiTopic_page);			
		}

		$model = array();
		$categories = BbiiForum::find()->category()->sorted()->all();

		foreach($categories as $category) {

			if (Yii::$app->user->isGuest) {
				$forums = BbiiForum::find()->where("cat_id = $category->id")->forum()->ispublic()->membergroup()->sorted()->all();
			} elseif ($this->isModerator()) {
				$forums = BbiiForum::find()->where("cat_id = $category->id")->forum()->sorted()->all();
			} else {
				$groupId = BbiiMember::find(Yii::$app->user->id)->group_id;
				$forums = BbiiForum::find()->where("cat_id = $category->id")->forum()->membergroup($groupId)->sorted()->all();
			}
			if (count($forums)) {
				$model[] = $category;
				foreach($forums as $forum) {
					$model[] = $forum;
				}
			}
		}

		$dataProvider = new ArrayDataProvider([
			'allModels'  => $model,
			'id'         => 'forum',
			'key'        => 'id',
			'pagination' => false,
		]);

		return $this->render('index', array(
			'dataProvider' => $dataProvider
		));
	}
	
	public function actionMarkAllRead() {
		if (!Yii::$app->user->isGuest) {
			$object = new BbiiTopicRead;
			$criteria = new CDbCriteria;
			$criteria->limit = 100;
			$criteria->order = 'last_post_id DESC';
			$forums = BbiiForum::find()->forum()->findAll();
			foreach($forums as $forum) {
				$topics = BbiiTopic::find()->findAll($criteria);
				$criteria->condition = 'forum_id = ' . $forum->id;
				foreach($topics as $topic) {
					$object->setRead($topic->id, $topic->last_post_id);
				}
			}
			$model = BbiiTopicRead::find(Yii::$app->user->id);
			if ($model === null) {
				$model = new BbiiTopicRead;
				$model->user_id = Yii::$app->user->id;
			}
			$model->data = $object->serialize();
			$model->save();
			return Yii::$app->response->redirect(array('forum/index'));
		}
	}
	
	/**
	 * Show forum with topics
	 *
	 * @version  2.7.5
	 */
	public function actionForum($id) {
		$id    = is_numeric($id) ?: Yii::$app->request->get('id');
		$forum = BbiiForum::findOne($id);

		if ($forum === null) {
			throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested forum does not exist.'));
		}

		if (Yii::$app->user->isGuest && $forum->public == 0) {
			throw new HttpException(403, Yii::t('BbiiModule.bbii', 'You have no permission to view requested forum.'));
		}

		if ($forum->membergroup_id != 0) {
			if (Yii::$app->user->isGuest) {
				throw new HttpException(403, Yii::t('BbiiModule.bbii', 'You have no permission to view requested forum.'));
			} elseif (!$this->isModerator()) {
				$groupId = BbiiMember::find(Yii::$app->user->id)->group_id;
				if ($forum->membergroup_id != $groupId) {
					throw new HttpException(403, Yii::t('BbiiModule.bbii', 'You have no permission to view requested forum.'));
				}
			}
		}

		// @todo Is this just a complicated way of getting a forum topics? - DJE : 2015-05-26
		/*
		if (isset(Yii::$app->request->get()['BbiiTopic_page']) && isset(Yii::$app->request->get()['ajax'])) {
            $topicPage = (int) Yii::$app->request->get()['BbiiTopic_page'] - 1;
            Yii::$app->user->setState('BbiiTopic_page', $topicPage);
			Yii::$app->user->setState('BbiiForum_id', $id);
            unset(Yii::$app->request->get()['BbiiTopic_page']);
        } elseif (isset(Yii::$app->request->get()['ajax'])) {

            Yii::$app->user->setState('BbiiTopic_page', 0);
		} elseif (Yii::$app->user->hasState('BbiiForum_id') && Yii::$app->user->BbiiForum_id != $id) {
			unset(Yii::$app->user->BbiiForum_id);
			Yii::$app->user->setState('BbiiTopic_page', 0);
		}
		$dataProvider = new ActiveDataProvider('BbiiTopic', array(
			'criteria' => array(
				'condition' => 'approved = 1 and (forum_id = ' . $forum->id . ' or global = 1)',
				'order'     => 'global DESC, sticky DESC, last_post_id DESC',
				'with'      => array('starter'),
			),
			'pagination' => array(
				'currentPage' => Yii::$app->user->getState('BbiiTopic_page', 0),
				'pageSize'    => $this->module->topicsPerPage,
			),
		));
		*/

		$dataProvider = new ActiveDataProvider([
			'query' => BbiiTopic::find()
				->where(['approved' => 1])
				->andWhere('(forum_id = ' . $forum->id . ' or global = 1)')
				->orderBy('id DESC')
	    ]);

		return $this->render('forum', array(
			'dataProvider' => $dataProvider,
			'forum'        => $forum,
		));
	}
	
	/**
	 * Show topic with posts
	 * @param $id integer topic_id
	 * @param $nav string post-id or "last"
	 */
	public function actionTopic($id = null, $nav = null, $postId = null) {
		$id = $id?: Yii::$app->request->get('id');

		$topic = BbiiTopic::findOne($id);
		if ($topic === null) {
			throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested topic does not exist.'));
		}

		$forum = BbiiForum::findOne($topic->forum_id);
		if (Yii::$app->user->isGuest && $forum->public == 0) {
			throw new HttpException(403, Yii::t('BbiiModule.bbii', 'You have no permission to read requested topic.'));
		}

		if ($forum->membergroup_id != 0) {
			if (Yii::$app->user->isGuest) {
				throw new HttpException(403, Yii::t('BbiiModule.bbii', 'You have no permission to read requested topic.'));
			} elseif (!$this->isModerator()) {
				$groupId = BbiiMember::find(Yii::$app->user->id)->group_id;
				if ($forum->membergroup_id != $groupId) {
					throw new HttpException(403, Yii::t('BbiiModule.bbii', 'You have no permission to read requested topic.'));
				}
			}
		}

		/* $dataProvider = new ActiveDataProvider(
			'BbiiPost', array(
			'criteria' => array(
				'condition' => 'approved = 1 and topic_id = ' . $topic->id,
				'order'     => 't.id',
				'with'      => array('poster'),
			),
			'pagination' => array(
				'pageSize' => $this->module->postsPerPage,
			),
		)); */

		$dataProvider = new ActiveDataProvider([
			'query' => BbiiPost::find()
				->where('approved = 1 and topic_id = ' . $topic->id)
				->orderBy('id DESC')
	    ]);

		// @todo Poll not enabled for inital release - DJE : 2015-05-27
		// Determine poll
		/*
		$criteria = new CDbCriteria;
		$criteria->condition = 'post_id = ' . $topic->first_post_id;
		$this->poll = BbiiPoll::find()->find($criteria);
		if ($this->poll !== null) {
			$this->choiceProvider = new ActiveDataProvider('BbiiChoice', array(
				'criteria' => array(
					'condition' => 'poll_id = ' . $this->poll->id,
					'order' => 'sort',
				),
				'pagination' => false,
			));
			// Determine whether user has voted
			if (Yii::$app->user->isGuest) {
				$this->voted = true; // A guest may not vote and sees the result immediately
			} else {
				$criteria->condition = 'poll_id = ' . $this->poll->id . ' and user_id = ' . Yii::$app->user->id;
				$this->voted = BbiiVote::find()->exists($criteria);
			}
			// Determine wheter the poll has expired
			if (!$this->voted && isset($this->poll->expire_date) && $this->poll->expire_date < date('Y-m-d')) {
				$this->voted = true;
			}
		}
		*/

		// Navigate to a post in a topic
		if (isset($nav)) {
			$cPage = $dataProvider->getPagination();
			if (is_numeric($nav)) {
				$criteria->condition = 'topic_id = ' . $topic->id . ' and id < =  ' . $nav . ' and approved = 1';
				$count = BbiiPost::find()->count($criteria);
				$page = ceil($count/$cPage->pageSize);
				$post = $nav;
			} else {
				$page = ceil($dataProvider->totalItemCount/$cPage->pageSize);
				$post = $topic->last_post_id;
			}
			if (Yii::$app->user->hasFlash('moderation')) {
				Yii::$app->user->setFlash('moderation', Yii::$app->user->getFlash('moderation'));
			}
			return Yii::$app->response->redirect(array('forum/topic', 'id' => $id, 'BbiiPost_page' => $page, 'postId' => $post));;
		}

		// Increase topic views
		$topic->updateCounters(array('num_views' => 1));

		// Register the last visit of a topic
		if (!Yii::$app->user->isGuest) {
			$object = new BbiiTopicsRead;
			$model  = BbiiTopicRead::find(Yii::$app->user->id)->one();

			if (empty($model->data)) {
				$model = new BbiiTopicRead;
				$model->user_id = Yii::$app->user->id;
			} else {
				$object->unserialize($model->data);
			}

			$object->setRead($topic->id, $topic->last_post_id);

			if ($object->follows($topic->id)) {
				$object->setFollow($topic->id, $topic->last_post_id);
			}

			$model->setAttribute('data', $object->serialize());

			if (!$model->validate() || !$model->save()){
				echo '<pre>';
				print_r( $model->getErrors() );
				echo '</pre>';
				exit;
			}

		}

		return $this->render('topic', array(
			'dataProvider' => $dataProvider,
			'forum'        => $forum,
			'postId'       => $postId,
			'topic'        => $topic,
		));
	}
	
	/**
	 * Quote the original post in the reply (reply to a post)
	 * @param $id integer post_id
	 */
	public function actionQuote($id) {
		$quoted = BbiiPost::find($id);
		if ($quoted === null) {
			throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
		}
		$forum = BbiiForum::find($quoted->forum_id);
		$topic = BbiiTopic::find($quoted->topic_id);
		if (Yii::$app->request->post('BbiiPost')) {
			$post = new BbiiPost;
			$post->load(Yii::$app->request->post()['BbiiPost']);
			$post->user_id = Yii::$app->user->id;
			if ($forum->moderated) {
				$post->approved = 0;
			} else {
				$post->approved = 1;
			}
			if ($post->save()) {
				if ($post->approved) {
					$forum->saveCounters(array('num_posts' => 1));					// method since Yii 1.1.8
					$topic->saveCounters(array('num_replies' => 1));					// method since Yii 1.1.8
					$topic->saveAttributes(array('last_post_id' => $post->id));
					$forum->saveAttributes(array('last_post_id' => $post->id));
					$post->poster->saveCounters(array('posts' => 1));					// method since Yii 1.1.8
					$this->assignMembergroup(Yii::$app->user->id);
				} else {
					Yii::$app->user->setFlash('moderation',Yii::t('BbiiModule.bbii', 'Your post has been saved. It has been placed in a queue and is now waiting for approval by a moderator before it will appear on the forum. Thank you for your contribution to the forum.'));
				}
				return Yii::$app->response->redirect(array('forum/topic', 'id' => $post->topic_id, 'nav' => 'last'));
			}
		} else {
			$post = new BbiiPost;
			$quote = $quoted->poster->member_name .' '. Yii::t('BbiiModule.bbii', 'wrote') .' '. Yii::t('BbiiModule.bbii', 'on') .' '. DateTimeCalculation::longDate($quoted->create_time);
			$post->content = '<blockquote cite = "'. $quote .'"><p class = "blockquote-header"><strong>'. $quote .'</strong></p>' . $quoted->content . '</blockquote><p></p>';
			$post->subject  = $quoted->subject;
			$post->forum_id = $quoted->forum_id;
			$post->topic_id = $quoted->topic_id;
		}
		return $this->render('reply', array(
			'forum' => $forum,
			'topic' => $topic,
			'post' => $post
		));
	}
	
	/**
	 * Reply to a topic
	 *
	 * @version  2.7.5
	 * @param $id integer topic_id
	 */
	public function actionReply($id) {
		$id    = is_numeric($id) ?: Yii::$app->request->get('id');
		$post  = new BbiiPost;
		$topic = BbiiTopic::find($id);

		if ($topic === null) {
			throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested topic does not exist.'));
		}

		$forum = BbiiForum::find($topic->forum_id);

		if (Yii::$app->request->post('BbiiPost')) {
			$post->load(Yii::$app->request->post('BbiiPost'));
			$post->user_id = Yii::$app->user->id;

			if ($forum->moderated) {
				$post->approved = 0;
			} else {
				$post->approved = 1;
			}

			if ($post->save()) {
				if ($post->approved) {
					// $post->updateCounters(['view_count' => 1]);
					$forum->updateCounters(array('num_posts' => 1));
					$topic->updateCounters(array('num_replies' => 1));
					
					$topic->saveAttributes(array('last_post_id' => $post->id));
					$forum->saveAttributes(array('last_post_id' => $post->id));
					$post->poster->updateCounters(array('posts' => 1));
					$this->assignMembergroup(Yii::$app->user->id);
				} else {
					Yii::$app->user->setFlash('moderation',Yii::t('BbiiModule.bbii', 'Your post has been saved. It has been placed in a queue and is now waiting for approval by a moderator before it will appear on the forum. Thank you for your contribution to the forum.'));
				}
				return Yii::$app->response->redirect(array('forum/topic', 'id' => $post->topic_id, 'nav' => 'last'));
			}
		} else {
			$post->subject = $topic->title;
			$post->forum_id = $forum->id;
			$post->topic_id = $topic->id;
		}

		return $this->render('reply', array(
			'forum' => $forum,
			'post'  => $post,
			'topic' => $topic,
		));
	}
	
	public function actionCreatetopic() {
		$poll = new BbiiPoll;
		$post = new BbiiPost;

		if (Yii::$app->request->post('BbiiForum')) {
			$post->forum_id = Yii::$app->request->post('BbiiForum')['id'];
			$forum = BbiiForum::find($post->forum_id);
		}

		if (isset(Yii::$app->request->post()['choice'])) {
			$choiceArr = Yii::$app->request->post()['choice'];
			while(count($choiceArr) < 3) {
				$choiceArr[] = '';
			}
		} else {

			$choiceArr = array('', '', '');
		}

		if (Yii::$app->request->post('BbiiPost')) {
			$forum = BbiiForum::findOne(Yii::$app->request->post('BbiiPost')['forum_id']);

			$post->setAttributes(Yii::$app->request->post('BbiiPost'));
			$post->approved = ($forum->moderated ? 0 : 1);

			if ($post->validate() && $post->save()) {
				// Topic
				$topic = new BbiiTopic;
				$topic->approved      =  $post->approved;
				$topic->first_post_id =  $post->id;
				$topic->forum_id      =  $forum->id;
				$topic->last_post_id  =  $post->id;
				$topic->title         =  $post->subject;

				if (Yii::$app->request->post('sticky')) { $topic->sticky = 1; }
				if (Yii::$app->request->post('global')) { $topic->global = 1; }
				if (Yii::$app->request->post('locked')) { $topic->locked = 1; }



				// @todo Enable this feature - DJE : 2015-05-26
				/*
				// Poll
				if (isset(Yii::$app->request->post()['BbiiPoll']) && isset(Yii::$app->request->post()['addPoll']) && Yii::$app->request->post()['addPoll'] == 'yes') {
					$poll->load(Yii::$app->request->post()['BbiiPoll']);
					$poll->post_id = $post->id;
					$poll->user_id = Yii::$app->user->id;
					if (empty($poll->expire_date)) {
						unset($poll->expire_date);
					}
					$count = 0;
					$choices = Yii::$app->request->post()['choice'];
					foreach($choices as $choice) {
						if (!empty($choice)) { $count++; }
					}
					if ($poll->validate() && $count > 1) {
						$correct = true;
					} else {
						$correct = false;
						if ($correct < 2) {
							$poll->addError('question', Yii::t('BbiiModule.bbii','A poll should have at least 2 choices.'));
						}
					}
				} else {

					$correct = true;
				}
				*/



				//if ($correct && $topic->save()) {
				if ($topic->validate() && $topic->save()) {
					
					// update post with topic
					$post->topic_id = $topic->id;
					$post->update();



					if (!$forum->moderated) {
						$forum->updateCounters(array('num_posts' => 1,'num_topics' => 1));	// method since Yii 2.0
						//$post->poster->updateCounters(array('posts' => 1));					// method since Yii 2.0
						
						$forum->last_post_id = $post->id;
						$forum->update();

						$this->assignMembergroup(Yii::$app->user->id);
					} else {

						Yii::$app->user->setFlash('moderation',Yii::t('BbiiModule.bbii', 'Your post has been saved. It has been placed in a queue and is now waiting for approval by a moderator before it will appear on the forum. Thank you for your contribution to the forum.'));
					}

					// @todo Enable this feature - DJE : 2015-05-26
					/*
					if (isset(Yii::$app->request->post()['BbiiPoll'])) {
						// @todo Enable this feature - DJE : 2015-05-26
						// $poll->save(); 
						$choices = Yii::$app->request->post()['choice'];
						$i = 1;
						foreach($choices as $choice) {
							if (!empty($choice)) {
								$ch          = new BbiiChoice;
								$ch->choice  = $choice;
								$ch->poll_id = $poll->id;
								$ch->sort    = $i++;

								$ch->save();
							}
						}
					}
					*/

					return Yii::$app->response->redirect(array('forum/topic', 'id' => $topic->id));
				} else {
					Yii::$app->user->setFlash('error', Yii::t('BbiiModule.bbii', 'Error, unable to save post.'));
					$post->delete();
				}
			}
		}

		return $this->render('update/forum', array(
			'choices' => $choiceArr,
			'forum'   => $forum,
			'poll'    => $poll,
			'post'    => $post,
		));
	}
	
	public function actionUpdate($id) {
		$post = BbiiPost::find($id);
		if ($post === null) {
			throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
		}
		if (($post->user_id != Yii::$app->user->id || $post->topic->locked) && !$this->isModerator()) {
			throw new HttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
		}
		$forum = BbiiForum::find($post->forum_id);
		$topic = BbiiTopic::find($post->topic_id);
		if (Yii::$app->request->post('BbiiPost')) {
			$post->attributes = Yii::$app->request->post('BbiiPost');
			$post->change_id = Yii::$app->user->id;
			if ($forum->moderated) {
				$post->approved = 0;
			} else {
				$post->approved = 1;
			}
			if ($post->save()) {
				if (!$post->approved) {
					$forum->saveCounters(array('num_posts' => -1));					// method since Yii 1.1.8
					if ($topic->num_replies > 0) {
						$topic->saveCounters(array('num_replies' => -1));				// method since Yii 1.1.8
					} else {
						$topic->approved = 0;
						$topic->update();
						$forum->saveCounters(array('num_topics' => -1));				// method since Yii 1.1.8
					}
					$post->poster->saveCounters(array('posts' => -1));				// method since Yii 1.1.8
				}
				return Yii::$app->response->redirect(array('forum/topic', 'id' => $post->topic_id));
			}
		}
		return $this->render('update', array(
			'forum' => $forum,
			'topic' => $topic,
			'post' => $post
		));
	}
	
	public function actionUpdatePoll($id) {
		$poll = BbiiPoll::find($id);
		if ($poll === null) {
			throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested poll does not exist.'));
		}
		$post = BbiiPost::find($poll->post_id);
		if ($poll->user_id != Yii::$app->user->id && !$this->isModerator()) {
			throw new HttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
		}
		if (isset(Yii::$app->request->post()['BbiiPoll'])) {
			$poll->load(Yii::$app->request->post()['BbiiPoll']);
			if (empty($poll->expire_date)) {
				unset($poll->expire_date);
			}
			if ($poll->save()) {
				$choices = Yii::$app->request->post()['choice'];
				foreach($choices as $key => $choice) {
					$ch = BbiiChoice::find($key);
					if ($ch !== null) {
						$ch->choice = $choice;
						$ch->save();
					}
				}
			}
		}
		return Yii::$app->response->redirect(array('forum/topic', 'id' => $post->topic_id));
	}
	
	/**
	 * Handle Ajax call for upvote/downvote of post
	 */
	public function actionUpvote() {
		$json = array();
		if (isset(Yii::$app->request->post()['id'])) {
			$criteria = new CDbCriteria;
			$criteria->condition = "member_id = :userid and post_id = :post_id";
			$criteria->params = array(':userid' => Yii::$app->user->id, ':post_id' => Yii::$app->request->post()['id']);
			if (BbiiUpvoted::find()->exists($criteria)) {	// remove upvote
				BbiiUpvoted::find()->deleteAll($criteria);
				$post = BbiiPost::find(Yii::$app->request->post()['id']);
				$topic = BbiiTopic::find($post->topic_id);
				$member = BbiiMember::find($post->user_id);
				$post->saveCounters(array('upvoted' => -1));
				$topic->saveCounters(array('upvoted' => -1));
				$member->saveCounters(array('upvoted' => -1));
			} else {										// add upvote
				$upvote = new BbiiUpvoted;
				$upvote->member_id = Yii::$app->user->id;
				$upvote->post_id = Yii::$app->request->post()['id'];
				$upvote->save();
				$post = BbiiPost::find(Yii::$app->request->post()['id']);
				$topic = BbiiTopic::find($post->topic_id);
				$member = BbiiMember::find($post->user_id);
				$post->saveCounters(array('upvoted' => 1));
				$topic->saveCounters(array('upvoted' => 1));
				$member->saveCounters(array('upvoted' => 1));
			}
			$json['success'] = 'yes';
			$json['html'] = $this->showUpvote(Yii::$app->request->post()['id']);
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Handle Ajax call for voting
	 */
	public function actionVote() {
		$json = array();
		if (isset(Yii::$app->request->post()['poll_id'])) {
			$this->poll = BbiiPoll::find(Yii::$app->request->post()['poll_id']);
			if (isset(Yii::$app->request->post()['choice'])) {
				// In case of a revote: remove previous votes
				$criteria = new CDbCriteria;
				$criteria->condition = 'poll_id = ' . Yii::$app->request->post()['poll_id'] . ' and user_id = ' . Yii::$app->user->id;
				$votes = BbiiVote::find()->findAll($criteria);
				foreach($votes as $vote) {
					$this->poll->saveCounters(array('votes' => -1));
					$model = BbiiChoice::find($vote->choice_id);
					$model->saveCounters(array('votes' => -1));
					$vote->delete();
				}
				foreach(Yii::$app->request->post()['choice'] as $choice) {
					$model = new BbiiVote;
					$model->poll_id = Yii::$app->request->post()['poll_id'];
					$model->choice_id = $choice;
					$model->user_id = Yii::$app->user->id;
					$model->save();
					$model = BbiiChoice::find($choice);
					$model->saveCounters(array('votes' => 1));
					$this->poll->saveCounters(array('votes' => 1));
				}
				$choiceProvider = new ActiveDataProvider('BbiiChoice', array(
					'criteria' => array(
						'condition' => 'poll_id = ' . Yii::$app->request->post()['poll_id'],
						'order' => 'sort',
					),
					'pagination' => false,
				));
				$json['html'] = $this->render('poll', array('choiceProvider' => $choiceProvider), true);
				$json['success'] = 'yes';
			} else {
				$json['success'] = 'no';
			}
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Handle Ajax call for display of vote form
	 */
	public function actionDisplayVote() {
		$json = array();
		if (isset(Yii::$app->request->post()['poll_id'])) {
			$this->poll = BbiiPoll::find(Yii::$app->request->post()['poll_id']);
			$choiceProvider = new ActiveDataProvider('BbiiChoice', array(
				'criteria' => array(
					'condition' => 'poll_id = ' . Yii::$app->request->post()['poll_id'],
					'order' => 'sort',
				),
				'pagination' => false,
			));
			$json['html'] = $this->render('vote', array('choiceProvider' => $choiceProvider), true);
			$json['success'] = 'yes';
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Handle Ajax call for display of poll edit form
	 */
	public function actionEditPoll() {
		$json = array();
		if (isset(Yii::$app->request->post()['poll_id'])) {
			$poll = BbiiPoll::find(Yii::$app->request->post()['poll_id']);
			$choices = array();
			$models = BbiiChoice::find()->findAll('poll_id = '.$poll->id);
			foreach($models as $model) {
				$choices[$model->id] = $model->choice;
			}
			$json['html'] = $this->render('editPoll', array('poll' => $poll, 'choices' => $choices), true);
			$json['success'] = 'yes';
		} else {
			$json['success'] = 'no';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Function to determine whether a forum group should be hidden
	 */
	public function collapsed($id) {
		if (isset(Yii::$app->request->cookies['bbiiCollapsed'])) {
			$catString = Yii::$app->request->cookies['bbiiCollapsed']->value;
			$catArray = explode('_', $catString);
			if (in_array($id, $catArray)) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Handle Ajax call to register in a cookie the setting or unsetting the hidden display of a forum group
	 */
	public function actionSetCollapsed() {
		$json = array('success' => 1);
		if (isset(Yii::$app->request->post()['id']) && isset(Yii::$app->request->post()['action'])) {
			if (Yii::$app->request->post()['action'] == 'set') {
				if (isset(Yii::$app->request->cookies['bbiiCollapsed'])) {
					$json['cookies'] = Yii::$app->request->cookies['bbiiCollapsed'];
					$catArray = explode('_', Yii::$app->request->cookies['bbiiCollapsed']->value);
					$catArray[] = Yii::$app->request->post()['id'];
					$catArray = array_unique($catArray);
					$cookie = new CHttpCookie('bbiiCollapsed', implode('_', $catArray));
					$cookie->expire = time() + (60*60*24*28);
					$cookie->path = Yii::$app->createUrl($this->module->id);
					Yii::$app->request->cookies['bbiiCollapsed'] = $cookie;
				} else {
					$cookie = new CHttpCookie('bbiiCollapsed', Yii::$app->request->post()['id']);
					$cookie->expire = time() + (60*60*24*28);
					$cookie->path = Yii::$app->createUrl($this->module->id);
					Yii::$app->request->cookies['bbiiCollapsed'] = $cookie;
				}
			} else {
				if (isset(Yii::$app->request->cookies['bbiiCollapsed'])) {
					$catArray = explode('_', Yii::$app->request->cookies['bbiiCollapsed']->value);
					$catArray = array_diff($catArray, array(Yii::$app->request->post()['id']));
					$cookie = new CHttpCookie('bbiiCollapsed', implode('_', $catArray));
					$cookie->expire = time() + (60*60*24*28);
					$cookie->path = Yii::$app->createUrl($this->module->id);
					Yii::$app->request->cookies['bbiiCollapsed'] = $cookie;
				}
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Handle Ajax call to register watching a topic by a user
	 */
	public function actionWatch() {
		$json = array('success' => 'yes');
		if (isset(Yii::$app->request->post()['topicId']) && isset(Yii::$app->request->post()['postId'])) {
			$object = new BbiiTopicRead;
			$model = BbiiTopicRead::find(Yii::$app->user->id);
			if ($model === null) {
				$model = new BbiiTopicRead;
				$model->user_id = Yii::$app->user->id;
			} else {
				$object->unserialize($model->data);
			}
			$object->setFollow(Yii::$app->request->post()['topicId'], Yii::$app->request->post()['postId']);
			$model->data = $object->serialize();
			$model->save();
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Handle Ajax call to register unwatching a topic by a user
	 */
	public function actionUnwatch() {
		$json = array('success' => 'yes');
		if (isset(Yii::$app->request->post()['topicId'])) {
			$object = new BbiiTopicRead;
			$model = BbiiTopicRead::find(Yii::$app->user->id);
			if ($model !== null) {
				$object->unserialize($model->data);
				$object->unsetFollow(Yii::$app->request->post()['topicId']);
				$model->data = $object->serialize();
				$model->save();
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::$app->errorHandler->error)
		{
			if (Yii::$app->request->isAjaxRequest)
				echo $error['message'];
			else
				return $this->render('error', $error);
		}
	}
	
	/**
	 * Determine whether a forum is completely read by a user
	 * @param integer forum id
	 * @return boolean
	 */
	public static function forumIsRead($forum_id) {
		$returnData = false;

		if (!Yii::$app->user->isGuest) {
			
			$model = BbiiTopicRead::findOne(Yii::$app->user->id);
			if ($model !== null) {
				$object = new BbiiTopicsRead;
				$object->unserialize($model->data);

				$models = BbiiTopic::find()
					->where('forum_id = '.$forum_id)
					->limit(100)
					->orderBy('last_post_id DESC')
					->all();

				$returnData = true;
				foreach($models as $topic) {
					if ($topic->last_post_id > $object->topicLastRead($topic->id)) {
						$result = false;
						break;
					}
				}
			}
		}

		return $returnData;
	}	
	/**
	 * Determine whether a topic is completely read by a user
	 * @param integer forum id
	 * @return boolean
	 */
	public function topicIsRead($topic_id) {
		if (Yii::$app->user->isGuest) {

			return false;
		} else {
			$model = BbiiTopicRead::find(Yii::$app->user->id)->select('*')->all();

			if (count($model) > 0) {
				return false;
			} else {
				$object = new BbiiTopicsRead;
				$object->unserialize($model->data);
				$lastPost = BbiiTopic::find()->cache(300)->findByPk($topic_id)->last_post_id;
				if ($lastPost > $object->topicLastRead($topic_id)) {
					$result = false;
				} else {
					return true;
				}
			}
		}
	}
	
	/**
	 * Determine the icon for a topic
	 */
	public static function topicIcon($topic) {
		$img = 'topic';

		if (self::topicIsRead($topic->id)) {
			$img .= '2';
		} else {
			$img .= '1';
		}

		if ($topic->global) {
			$img .= 'g';
		}

		if ($topic->sticky) {
			$img .= 's';
		}

		// @todo Poll sdisabled for init release - DJE : 2015-05-27
		/*$criteria = new CDbCriteria;
		$criteria->condition = 'post_id = ' . $topic->first_post_id;
		if (BbiiPoll::find()->exists($criteria)) {
			$img .= 'p';
		}*/

		if ($topic->locked) {
			$img .= 'l';
		}
		return $img;
	}
	
	public function showUpvote($post_id) {
		$url = Yii::$app->urlManager->createAbsoluteUrl('forum/upvote');
		$post = BbiiPost::find($post_id);
		if ($post === null || $post->user_id == Yii::$app->user->id) {
			return '';
		}
		$criteria = new CDbCriteria;
		$criteria->condition = "member_id = :userid and post_id = $post_id";
		$criteria->params = array(':userid' => Yii::$app->user->id);

		// @todo this should be moved to the VW - DJE : 2015-05-14
		$assets = AppAsset::register($this);
		if (BbiiUpvoted::find()->exists($criteria)) {
			$html = Html::img($asset->baseUrl.'/images/down.gif', 'upvote', array('title' => Yii::t('BbiiModule.bbii', 'Remove your vote'), 'id' => 'upvote_'.$post_id, 'style' => 'cursor:pointer;', 'onclick' => 'upvotePost(' . $post_id . ',"' . $url . '")'));
		} else {
			$html = Html::img($asset->baseUrl.'/images/up.gif', 'upvote', array('title' => Yii::t('BbiiModule.bbii', 'Vote this post up'), 'id' => 'upvote_'.$post_id, 'style' => 'cursor:pointer;', 'onclick' => 'upvotePost(' . $post_id . ',"' . $url . '")'));
		}
		return $html;
	}
	
	private function assignMembergroup($id) {
		$member = BbiiMember::findOne($id);
		$group = BbiiMembergroup::find($member->group_id);

		if ($group !== null && (isset($group->min_posts) &&$group->min_posts < 0)) {
			return;
		}

		$newGroup = BbiiMembergroup::find()
			->where("min_posts > 0 and min_posts < =  " . $member->posts)
			->orderBy('min_posts DESC');
		
		if ($newGroup !== null && isset($group->id) && ($group->id != $newGroup->id)) {
			$member->group_id = $newGroup->id;
			$member->save();
		}
	}
	
	public function isWatching($topic_id) {
		$object = new BbiiTopicRead;
		$model = BbiiTopicRead::find(Yii::$app->user->id);
		if ($model === null) {
			return false;
		}
		$object->unserialize($model->data);
		return $object->follows($topic_id);
	}
}