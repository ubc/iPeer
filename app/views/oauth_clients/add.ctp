<div class="oauthClients form">
<?php 
echo $this->Form->create('Oauthclient');
if (isset($hideUser)) {
    echo $this->Form->input('OauthClient.user_id', array('div' => 'hide'));
} else {
    echo $this->Form->input('OauthClient.user_id');
}
echo $this->Form->input('OauthClient.key');
echo $this->Form->input('OauthClient.secret');
echo $this->Form->input('OauthClient.comment');
echo $this->Form->end(__('Submit', true));
?>
</div>
