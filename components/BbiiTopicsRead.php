<?php

namespace frontend\modules\bbii\components;

use Yii;
use yii\db\ActiveRecord;

class BbiiTopicsRead {
    private $data;
    private $topics;
    private $follow;
    
    public function __construct() {
        $this->topics = array();
        $this->follow = array();
    }
    
    public function setRead($topicId, $postId) {
        $this->topics[$topicId] = $postId;
    }
    
    public function topicLastRead($topicId) {
        if (isset($this->topics[$topicId])) {
            return $this->topics[$topicId];
        } else {
            return 0;
        }
    }
    
    public function setFollow($topicId, $postId) {
        $this->follow[$topicId] = $postId;
    }
    
    public function unsetFollow($topicId) {
        unset($this->follow[$topicId]);
    }
    
    public function follows($topicId) {
        if (isset($this->follow[$topicId])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getFollow() {
        return $this->follow;
    }
    
    public function serialize() {
        $this->data = array();
        $this->data['topics'] = $this->topics;
        $this->data['follow'] = $this->follow;
        $serialized = serialize($this->data);
        return $serialized;
    }
    
    public function unserialize($data) {
        $this->data = unserialize($data);
        if (isset($this->data['topics'])) {
            $this->topics = $this->data['topics'];
        } else {
            $this->topics = array();
        }
        if (isset($this->data['follow'])) {
            $this->follow = $this->data['follow'];
        } else {
            $this->follow = array();
        }
    }
}