<div class="oauthTokens form">
<?php 
echo $this->Form->create('Oauthtoken');
if (isset($hideUser)) {
    echo $this->Form->input('OauthToken.user_id', array('div' => 'hide'));
} else {
    echo $this->Form->input('OauthToken.user_id');
}
echo $this->Form->input('OauthToken.key');
echo $this->Form->input('OauthToken.secret');
echo $this->Form->input('OauthToken.expires');
echo $this->Form->input('OauthToken.comment');
echo $this->Form->end(__('Submit', true));
?>
</div>
