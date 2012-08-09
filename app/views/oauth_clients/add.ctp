<div class="oauthClients form">
<?php 
echo $this->Form->create('OauthClient');
echo $this->Form->input('user_id');
echo $this->Form->input('key');
echo $this->Form->input('secret');
echo $this->Form->input('comment');
echo $this->Form->end(__('Submit', true));
?>
</div>
