<?php
/* @var $this ForumController */
/* @var $data BbiiChoice */
$percentage = ($this->poll->votes)?(($data->votes/$this->poll->votes)*100):0;
$percentage = round($percentage);
?>

<div class="poll">
<?php echo $data->choice . ' (' . $data->votes . ' ' . Yii::t('BbiiModule.bbii','votes') . ' ' . $percentage . '%)'; ?>
<div class="progress"><div class="progressbar" style="width:<?php echo ($this->poll->votes)?(($data->votes/$this->poll->votes)*99.5):'0'; ?>%"> </div></div>
</div>