<div class="oauthTokens form">
<?php 
echo $this->Form->create('OauthToken');
if (isset($hideUser)) {
    echo $this->Form->input('user_id', array('div' => 'hide'));
} else {
    echo $this->Form->input('user_id');
}
echo $this->Form->input('key');
echo $this->Form->input('secret');
echo $this->Form->input('expires');
echo $this->Form->input('comment');
echo $this->Form->end(__('Submit', true));
?>
</div>
