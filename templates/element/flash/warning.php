<?php

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
  $message = h($message);
}
?>
<div class="message warning alert alert-warning bg-dark" role="alert" onclick="this.classList.add('hidden');">
  <?= $message ?>
</div>