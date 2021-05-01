<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TBook $tBook
 */

use function PHPSTORM_META\type;

$this->assign('title', 'Add Book : 書籍登録');

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
            <?= $this->Form->control('name', ['class' => 'form-control', 'placeholder' => '書籍名']); ?>
          </div>
        </div>
        <div class="form-group row" id="js-book-no">
          <label class="col-sm-2 col-form-label">BookNo：<br>書籍No</label>
          <div class="col-sm-4">
            <?= $this->Form->control('book_no', ['class' => 'form-control', 'v-model' => ' book_no', 'placeholder' => '書籍No', '@change' => "validate_no"]); ?>
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
            <input type="hidden" name="genres" :value="genres">
          </div>
        </div>
        <div class="form-group row" id="js-quantity">
          <label class="col-sm-2 col-form-label">Quantity：<br>冊数</label>
          <div class="col-sm-2">
            <span v-for="q in quantity" class="h4">{{ q }}</span>
            <span>冊</span>
          </div>
          <div class="col-sm-8">
            <div class="btn-group" role="group">
              <span v-for="number in numbers"><button @click.prevent="num(number)" v-bind:disabled="isDisabled"
                  type="button" class="btn btn-light btn-sm">{{ number }}</button>
              </span>
            </div>
            <button class="btn btn-info btn-sm" @click.prevent='clear'>再選択</button>
          </div>
          <input type="hidden" name="quantity" :value="quantity" class="form-control">
        </div>
        <div class="form-group row" id="js-price">
          <label class="col-sm-2 col-form-label">Price：<br>価格</label>
          <div class="col-sm-2">
            <span>¥</span>
            <span v-show="price" class="h4">{{ Number(price).toLocaleString() }}</span>
          </div>
          <div class="col-sm-4">
            <?= $this->Form->control('price', ['class' => 'form-control', 'maxLength' => '10', 'v-model' => 'price', 'placeholder' => '価格', '@change' => "validate_price"]); ?>
          </div>
        </div>
        <div class="form-group row" id="js-deadline">
          <label class="col-sm-2 col-form-label">Deadline：<br>最長レンタル時間</label>
          <div class="col-sm-2">
            <div>
              <span v-for="d in deadline" class="h4">{{ d }}</span>
              <span>時間</span>
            </div>
            <span v-show="deadline">約:{{ day }}日</span>
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
        </div>
        <div class="form-group row" id="js-image">
          <label class="col-sm-2 col-form-label control-label" for="fileInput">Image：<br>書籍画像</label>
          <div class="col-sm-10 controls">
            <?= $this->Form->file('image_file', array('id' => 'fileInput', 'class' => 'input-file uniform_on form-control', '@change' => "validate_uploads")); ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="fileInput">サムネイル画像</label>
          <div class="controls">
            <p id="preview">指定なし</p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Outline : <br>概要</label>
          <div class="col-sm-10">
            <?= $this->Form->textarea('outline', ['class' => 'form-control', 'placeholder' => '概要']); ?>
          </div>
        </div>
        <input type="hidden" name="del_flg" value="0">
        <?= $this->Form->button('Confirm - 確認', ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>