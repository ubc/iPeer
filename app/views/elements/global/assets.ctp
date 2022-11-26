<?php if (Configure::read('development')): ?>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/main.ts"></script>
<?php else: ?>
    <?php echo $this->Html->css('/css/assets/main.css', TRUE); ?>
    <script defer type="module" src="/js/assets/main.js"></script>
<?php endif; ?>