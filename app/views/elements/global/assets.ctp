<?php if (Configure::read('development')): ?>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/main.ts"></script>
<?php else: ?>
    <?php echo $this->Html->css('/css/assets/main.3a0a2aba.css', TRUE); ?>
    <script type="module" src="/js/assets/main.50a5d5e8.js"></script>
<?php endif; ?>