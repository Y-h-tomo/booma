<?php

$this->assign('title', 'Confirm Book : 書籍登録確認');

$options = array(
  'action' => 'done',
  'type' => 'file',
  'inputDefaults' => array('legend' => false, 'label' => false, 'div' => false),
  'class' => 'form-horizontal'
);


?>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5><?= $this->fetch('title') ?></h5>
        <p>こちらの情報で登録します。よろしいですか？</p>
      </div>
      <div class="card-block">
        <p class="sub-title"></p>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">BookName：<br>書籍名</label>
          <h5><?= h($tBook['name']) ?></h5>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">BookNo：<br>書籍No</label>
          <div class="col-sm-10">
            <h5><?= h($tBook['book_no']) ?></h5>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">BookGenres：<br>書籍ジャンル</label>
          <div class="col-sm-10">
            <?php foreach (($tBook['genres_name']) as $genre) : ?>
              <span class="badge badge-pill badge-primary ml-1"> <?= h($genre) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Quantity：<br>冊数</label>
          <div class="col-sm-10">
            <h5><?= h($tBook['quantity']) ?><span>冊</span></h5>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Price：<br>価格</label>
          <div class="col-sm-10">
            <h5><span>¥</span><?= number_format(h($tBook['price'])) ?></h5>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Deadline：<br>最長レンタル期間</label>
          <div class="col-sm-10">
            <h5><?= round((h($tBook['deadline'] / 24)), 2); ?><span>日</span></h5>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="fileInput">サムネイル画像</label>
          <div class="controls">
            <p>
              <?= $this->Html->image($tBook['image'], ['alt' => $tBook['name'] . 'の画像', 'width' => '100']); ?>
            </p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Outline : <br>概要</label>
          <div class="col-sm-10">
            <?= nl2br(h($tBook['outline'])) ?>
          </div>
        </div>
        <?= $this->Form->postButton('Register - 登録する', ['controller' => 'TBooks', 'action' => 'add_exec'], ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link(__('Back - 戻る'), ['action' => 'add'], ['type' => 'button', 'class' => 'button btn btn-outline-dark']) ?>
      </div>
    </div>
  </div>
</div>