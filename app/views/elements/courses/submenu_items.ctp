<ul>
<?php
foreach($items as $item) {
  echo '<li>';
  echo $this->Html->link(
    $item['name'], 
    $item['link'], 
    array('escape' => false)
  );
  echo '</li>';
}
?>
</ul>
