<div class="oauthTokens form">
<?php 
echo $this->Form->create('Oauthtoken');
echo $this->Form->input('OauthToken.id');
echo $this->Form->input('OauthToken.user_id');
echo $this->Form->input('OauthToken.key');
echo $this->Form->input('OauthToken.secret');
echo $this->Form->input('OauthToken.expires');
echo $this->Form->input('OauthToken.enabled', 
    array('options' => array('0' => 'False', '1' => 'True')));
echo $this->Form->input('OauthToken.comment');
echo $this->Form->end(__('Submit', true));
?>
</div>
