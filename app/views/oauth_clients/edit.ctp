<div class="oauthClients form">
<?php 
echo $this->Form->create('Oauthclient');
echo $this->Form->input('OauthClient.id');
echo $this->Form->input('OauthClient.user_id');
echo $this->Form->input('OauthClient.key');
echo $this->Form->input('OauthClient.secret');
echo $this->Form->input('OauthClient.enabled', 
    array('options' => array('0' => 'False', '1' => 'True')));
echo $this->Form->input('OauthClient.comment');
echo $this->Form->end(__('Submit', true));
?>
</div>
