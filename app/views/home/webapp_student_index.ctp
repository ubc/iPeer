<?php echo $this->Html->script('https://unpkg.com/axios@0.27.2/dist/axios.js', FALSE); ?>

<?php echo $title_for_layout; ?>

<div id="webapp" class="webapp student">vue webapp</div>

<script>
  
  const events = axios.get('http://localhost:8080/home', {
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json;charset=UTF-8',
    },
  })
  console.log(events.json())
  
  const evaluations = axios.get('http://localhost:8080/evaluations/makeEvaluation/2/2', {
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json;charset=UTF-8',
    },
  })
  console.log(evaluations.json())
  
  
</script>