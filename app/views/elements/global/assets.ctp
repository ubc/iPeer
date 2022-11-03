<?php if (Configure::read('development')): ?>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/main.ts"></script>
<?php else: ?>
    <?php echo $this->Html->css('/css/assets/main.73eaa35e.css', TRUE); ?>
    <script type="module" src="/js/assets/index.58935068.js"></script>
<?php endif; ?>