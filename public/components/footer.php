  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" 
          integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" 
          crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- drag drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

  

  <script src="../js/utils.js"></script>

  
  <?php if (!empty($script)): ?>
    <?php if (is_array($script)): ?>
      <?php foreach ($script as $s): ?>
        <script type="<?= $type ?? 'module' ?>" src="../js/<?= $s ?>.js"></script>
      <?php endforeach; ?>
    <?php else: ?>
      <script type="<?= $type ?? 'module' ?>" src="../js/<?= $script ?>.js"></script>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (!empty($checkAuth) && $checkAuth === true): ?>
    <script src="/../js/auth.js"></script>
  <?php else: ?>
    <script>
      window.checkAuth = false;
    </script>
  <?php endif; ?>
</body>
</html>
