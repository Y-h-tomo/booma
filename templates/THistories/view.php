<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\THistory $tHistory
 */

use Cake\I18n\FrozenTime;


$time = FrozenTime::now()->toUnixString();

$this->assign('title', 'My Page : レンタル中書籍');

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
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('id', '履歴ID') ?></span></th>
                    <th><span><?= '書籍イメージ' ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('TBooks.name', '書籍名') ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('TBooks.book_no', '書籍No') ?></span>
                    </th>
                    <th><span><?= 'ジャンル' ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('return_time', '返却期限') ?></span></th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('TBooks.remain', '残り冊数') ?></span>
                    </th>
                    <th><span class="badge badge-light"><?= $this->Paginator->sort('TBooks.quantity', '登録冊数') ?></span>
                    </th>
                    <th class="rentals"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (($tHistories->toArray()) as $h) : ?>
                  <tr>
                    <td><?= $this->Number->format($h['id']) ?></td>
                    <td>
                      <?= $this->Html->image("{$h['t_book']['image']}", ['width' => '80', 'alt' => "{$h['t_book']['name']} の画像"]); ?>
                    </td>
                    <td><?= h($h['t_book']['name']) ?></td>
                    <td><?= h($h['t_book']['book_no']) ?></td>
                    <td style="text-align:right;">
                      <?php foreach ($h['t_book']['m_genres'] as $genre) : ?>
                      <span class="badge badge-pill badge-primary ml-1">
                        <?= h($genre['genre']) ?>
                      </span>
                      <?php endforeach; ?>
                    </td>
                    <td style="text-align:right;"><?= $h['return_time']->i18nFormat('yyyy-MM-dd HH:mm:ss'); ?><br>
                      <?php if($h['return_time']->toUnixString() > $time) :?>
                      <span class="text-danger">
                        残り<?= ($h['return_time']->timeAgoInWords(
                                                            ['format' => 'MMM d, YYY', 'end' => '+1 year']
                                                        )); ?>
                      </span>
                      <?php else: ?>
                      <span class="bg-danger"> 既に返却期限を過ぎています</span>
                      <?php endif; ?>
                    </td>
                    <td style="text-align:right;"><?= $this->Number->format($h['t_book']['remain']) ?><span> 冊</span>
                    </td>
                    <td style="text-align:right;"><?= $this->Number->format($h['t_book']['quantity']) ?><span> 冊</span>
                    </td>
                    <td class="rentals">
                      <a href="#modal_r<?= $h['t_book']['id'] ?>"><button type="button"
                          class="btn btn-dark btn-sm">返却</button></a>
                      <!-- /* --------------------------------- 返却モーダルエリア -------------------------------- */ -->
                      <div class="remodal col" data-remodal-id="modal_r<?= $h['t_book']['id'] ?>">
                        <p>※<?= $h['t_book']['name'] . 'を返却しますか？'  ?> </p>
                        <?= $this->Form->postButton(__('返却'), ['controller' => 'tHistories', 'action' => 'delete'], ['data' => ['t_books_id' => $h['t_book']['id'], 'm_users_id' => $user_id], 'class' => 'btn btn-dark']) ?>
                        <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                      </div>
                      <!-- /* -------------------------------------------------------------------------- */ -->
                      <?= $this->Html->link(__('詳細'), ['controller' => 'TBooks', 'action' => 'view', $h['t_book']['id']], ['class' => 'btn btn-info btn-sm mt-1']) ?>
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