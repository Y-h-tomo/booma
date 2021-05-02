<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TBook[]|\Cake\Collection\CollectionInterface $tBooks
 */

$this->assign('title', 'Book List : 書籍リスト');

$session = $this->getRequest()->getSession();
if ($session->check('User.name')) {
    $LoginName = $session->read('User.name');
    $role = $session->read('User.role');
    $user_id = $session->read('User.id');
} else {
    $LoginName = 'No login';
    $role = '1';
    $user_id = '';
}

?>
<div class="main-body">
  <div class="page-wrapper">
    <div class="page-body">
      <div class="mUsers index content">
        <div class="card">
          <div class="card-header">
            <div class="paginator">
              <ul class="pagination">
                <span class="m-l-5"><?= $this->Paginator->first('<< ' . __('最初へ')) ?></span>
                <span class="m-l-5"><?= $this->Paginator->prev('< ' . __('前')) ?></span>
                <span class="m-l-5"><?= $this->Paginator->numbers() ?></span>
                <span class="m-l-5"><?= $this->Paginator->next(__('次') . ' >') ?></span>
                <span class="m-l-5"><?= $this->Paginator->last(__('最後へ') . ' >>') ?></span>
              </ul>
              <p><?= $this->Paginator->counter(__('{{current}}  / {{count}} 書籍 ,  ( {{page}} / {{pages}} )')) ?></p>
            </div>
            <div class="card-header-right">
              <ul class="list-unstyled card-option">
                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                <li><i class="fa fa-window-maximize full-card"></i></li>
                <li><i class="fa fa-minus minimize-card"></i></li>
                <li><i class="fa fa-refresh reload-card"></i></li>
                <li><i class="fa fa-trash close-card"></i></li>
              </ul>
            </div>
          </div>
          <div class="card-block table-border-style">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('id', 'ID') ?></span></th>
                    <th><span><?= '書籍イメージ' ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('name', '書籍名') ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('book_no', '書籍No') ?></span></th>
                    <th><span><?= 'ジャンル' ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('deadline', '最大レンタル時間') ?></span>
                    </th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('remain', '残り冊数') ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('quantity', '登録冊数') ?></span></th>
                    <th class="actions"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($tBooks as $tBook) : ?>
                  <tr>
                    <td><?= $this->Number->format($tBook->id) ?></td>
                    <td><?= $this->Html->image("$tBook->image", ['width' => '80', 'alt' => "$tBook->name の画像"]); ?></td>
                    <td><?= h($tBook->name) ?></td>
                    <td><?= h($tBook->book_no) ?></td>
                    <td style="text-align:right;">
                      <?php foreach ($tBook->m_genres as $genre) : ?>
                      <span class="badge badge-pill badge-primary ml-1">
                        <?= h($genre['genre']) ?>
                      </span>
                      <?php endforeach; ?>
                    </td>
                    <td style="text-align:right;"><?= $this->Number->format($tBook->deadline) ?><span> 時間</span></td>
                    <td style="text-align:right;"><?= $this->Number->format($tBook->remain) ?><span> 冊</span></td>
                    <td style="text-align:right;"><?= $this->Number->format($tBook->quantity) ?><span> 冊</span></td>
                    <td class="actions">
                      <?php if ($user_id) : ?>
                      <?php if (($tBook->remain) !== 0) : ?>
                      <a href="#modal_b<?= $tBook->id ?>"><button type="button"
                          class="btn btn-warning btn-sm mt-1">レンタル</button></a>
                      <!-- /* --------------------------------- レンタルモーダルエリア -------------------------------- */ -->
                      <div class="remodal col" data-remodal-id="modal_b<?= $tBook->id ?>">
                        <p>※<?= $tBook->name . 'をレンタルしますか？'  ?> </p>
                        <?= $this->Form->postButton(__('レンタル'), ['controller' => 'tHistories', 'action' => 'add'], ['data' => ['t_books_id' => $tBook->id, 'm_users_id' => $user_id], 'class' => 'btn btn-warning m-b-5']) ?>
                        <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                      </div>
                      <!-- /* -------------------------------------------------------------------------- */ -->
                      <?php endif; ?>
                      <?php if (($tBook->remain) < ($tBook->quantity)) : ?>
                      <a href="#modal_r<?= $tBook->id ?>"><button type="button"
                          class="btn btn-dark btn-sm mt-1">返却</button></a>
                      <!-- /* --------------------------------- 返却モーダルエリア -------------------------------- */ -->
                      <div class="remodal col" data-remodal-id="modal_r<?= $tBook->id ?>">
                        <p>※<?= $tBook->name . 'を返却しますか？'  ?> </p>
                        <?= $this->Form->postButton(__('返却'), ['controller' => 'tHistories', 'action' => 'delete'], ['data' => ['t_books_id' => $tBook->id, 'm_users_id' => $user_id], 'class' => 'btn btn-dark m-b-5']) ?>
                        <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                      </div>
                      <!-- /* -------------------------------------------------------------------------- */ -->
                      <?php endif; ?>
                      <?php else : ?>
                      <span class="text-danger">レンタル・返却機能の利用にはログインが必要です</span>
                      <?php endif; ?>
                      <?= $this->Html->link(__('詳細'), ['action' => 'view', $tBook->id], ['class' => 'btn btn-info btn-sm mt-1']) ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>