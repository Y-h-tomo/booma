<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MGenre $mGenre
 */

$this->assign('title', 'Add Genre : ジャンル登録');

?>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5><?= $this->fetch('title') ?></h5>
      </div>
      <div class="card-block">
        <?= $this->Form->create($mGenre, ['class' => 'form-material']) ?>
        <div class="form-group form-default">
          <?= $this->Form->control('genre', ['label' => 'ジャンル', 'class' => 'form-control']); ?>
          <span class="form-bar"></span>
        </div>
        <?= $this->Form->button('Register - 登録', ['type' => 'submit', 'class' => 'btn-primary btn']) ?>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>