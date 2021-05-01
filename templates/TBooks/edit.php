<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TBook $tBook
 */

$this->assign('title', 'Edit Book : 書籍編集');

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
      </div>
      <div class="card-block">
        <h4 class="sub-title"></h4>
        <?= $this->Form->create($tBook, ['enctype' => 'multipart/form-data']) ?>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">BookName：<br>書籍名</label>
          <div class="col-sm-10">
            <?= $this->Form->control('name', ['class' => 'form-control']); ?>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">BookNo：<br>書籍No</label>
          <div class="col-sm-4">
            <?= $this->Form->control('book_no', ['class' => 'form-control']); ?>
          </div>
        </div>
        <div class="form-group row" id="js-genre-modal">
          <label class="col-sm-2 col-form-label">BookGenres：<br>書籍ジャンル選択</label>
          <div class="col-sm-3">
            <a href="#modal_b"><button type="button" class="btn btn-outline-primary btn-sm">ジャンル選択</button></a>
            <button type="button" class="btn btn-info btn-sm" @click.prevent='clear'>再選択</button>
          </div>

          <!-- /* --------------------------------- モーダルエリア -------------------------------- */ -->
          <div class="remodal col" data-remodal-id="modal_b">
            <p>※ジャンル選択は3つまで</p>
            <?php foreach ($genres as $g) : ?>
            <input type="checkbox" value="<?= $g['id'] . ':' . $g['genre'] ?>" v-model="genres"
              v-bind:disabled="isDisabled" id="genre-btn-<?= $g['id'] ?>" class="genre-box">
            <label class="genre-btn btn" for="genre-btn-<?= $g['id'] ?>"><?= $g['genre'] ?>
            </label>
            <?php endforeach; ?>
            <br>
            <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
            <button type="button" class="btn btn-info" @click.prevent='clear'>再選択</button>
          </div>

          <!-- /* -------------------------------------------------------------------------- */ -->
          <div class="col-sm-7">
            <div>
              <span class="badge badge-pill badge-primary ml-1" v-for="genre in genres">
                {{ genre }}
              </span>
            </div>
            <div>
              <?php foreach ($selectGenres as $genre) : ?>
              <span class="badge badge-pill badge-secondary ml-1"> <?= h($genre) ?></span>
              <?php endforeach; ?>
              <input type="hidden" name="genres" :value="genres">
              <input type="hidden" name="genres" v-bind:disabled="inputDisabled" value="<?= $beforeGenres ?>">
            </div>
          </div>
        </div>
        <div class="form-group row" id="js-quantity">
          <label class="col-sm-2 col-form-label">Quantity：<br>冊数</label>
          <div class="col-sm-2">
            <span v-show="!quantity" class="h4"><?= h($tBook['quantity']) ?></span>
            <span v-for="q in quantity" class="h4">{{ q }}</span>
            <span>冊</span>
          </div>
          <div class="col-sm-8">
            <div class="btn-group" role="group">
              <span v-for="number in numbers"><button @click.prevent="num(number)" v-bind:disabled="isDisabled"
                  type="button" class="btn btn-light btn-sm">{{ number }}</button>
              </span>
            </div>
            </span><button class="btn btn-info btn-sm" @click.prevent='clear'>再選択</button>
          </div>
          <input type="hidden" name="quantity" :value="quantity" class="form-control">
          <?= $this->Form->control('quantity', ['class' => 'form-control', 'type' => 'hidden', 'v-bind:disabled' => "inputDisabled"]); ?>
        </div>
        <div class="form-group row" id="js-price">
          <label class="col-sm-2 col-form-label">Price：<br>価格</label>
          <div class="col-sm-2">
            <span>¥</span>
            <span v-show="price" class="h4">{{ Number(price).toLocaleString() }}</span>
            <span v-show="!price" class="h4"><?= number_format(h($tBook['price'])) ?></span>
          </div>
          <div class="col-sm-4">
            <input type="text" name="price" v-model="price" class="form-control" maxlength="10"
              @change="validate_price">
            <?= $this->Form->control('price', ['class' => 'form-control', 'maxLength' => '10', 'type' => 'hidden', 'v-bind:disabled' => "inputDisabled", '@change' => "validate_price"]); ?>
          </div>
        </div>
        <div class="form-group row" id="js-deadline">
          <label class="col-sm-2 col-form-label">Deadline：<br>最長レンタル時間</label>
          <div class="col-sm-2">
            <div v-show="deadline">
              <span v-for="d in deadline" class="h4">{{ d }}</span>
              <span>時間</span>
              <div>
                <span v-show="deadline">計＝{{ day }}日</span>
              </div>
            </div>
            <div v-show="!deadline">
              <span class="h4"><?= h($tBook['deadline']) ?></span>
              <span>時間</span>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="btn-group" role="group">
              <span v-for="number in numbers"><button @click.prevent="num(number)" v-bind:disabled="isDisabled"
                  type="button" class="btn btn-light btn-sm">{{ number }}</button>
              </span>
            </div>
            <button type="button" class="btn btn-info btn-sm" @click.prevent='clear'>再選択</button>
            <button type="button" class="btn-dark btn btn-sm" @click.prevent='week'
              v-bind:disabled="inputDisabled">1週間</button>
            <button type="button" class="btn-dark btn btn-sm" @click.prevent='twoWeek'
              v-bind:disabled="inputDisabled">2週間</button>
          </div>
          <input type="hidden" name="deadline" :value="deadline" class="form-control">
          <?= $this->Form->control('deadline', ['class' => 'form-control', 'type' => 'hidden', 'v-bind:disabled' => "inputDisabled"]); ?>
        </div>
        <div class="form-group row" id="js-image">
          <label class="col-sm-2 col-form-label control-label" for="fileInput">Image：<br>書籍画像</label>
          <div class="col-sm-10 controls">
            <?= $this->Form->file('image_file', array('id' => 'fileInput', 'class' => 'input-file uniform_on form-control', '@change' => "validate_uploads", 'required' => false)); ?>
            <?= $this->Form->control('image', ['class' => 'form-control', 'type' => 'hidden']); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="fileInput">変更前画像</label>
          <div class="controls">
            <p>
              <?= $this->Html->image($tBook['image'], ['alt' => $tBook['name'] . 'の画像', 'width' => '100']); ?>
            </p>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="fileInput">変更後画像</label>
          <div class="controls">
            <p id="preview">指定なし</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Outline : <br>概要</label>
          <div class="col-sm-10">
            <?= $this->Form->textarea('outline', ['class' => 'form-control']); ?>
          </div>
        </div>
        <input type="hidden" name="del_flg" value="0">
        <input type="hidden" name="version" value="<?= $tBook['modified']->i18nFormat('yyyy-MM-dd HH:mm:ss') ?>">
        <?= $this->Form->button('Confirm - 確認', ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>