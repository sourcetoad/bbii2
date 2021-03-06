<?php
/* @var $post_id integer */
$models = BbiiUpvoted::find()->findAllByAttributes(array('post_id' => $post_id));
$count = count($models);

if ($count) {
    echo '<div class = "post-upvote-footer">' . PHP_EOL;
    echo Yii::t('BbiiModule.bbii', 'Post appreciated by: '); 
    $users = array();
    foreach($models as $model) {
        $member = BbiiMember::find($model->member_id);
        if ($member !== null) {
            $users[] = Html::a(Html::encode($member->member_name), array("member/view", "id" => $member->id));
        }
    }
    echo implode(', ', $users);
    echo '</div>' . PHP_EOL;
}
?>