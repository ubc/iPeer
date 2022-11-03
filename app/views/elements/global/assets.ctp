<?php if (Configure::read('development')): ?>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/main.ts"></script>
<?php else: ?>
    <?php echo $this->Html->css('vueapp/main.c3ab927f.css', TRUE); ?>
    <script type="module" src="js/vueapp/index.2c04a733.js"></script>
<?php endif; ?>
