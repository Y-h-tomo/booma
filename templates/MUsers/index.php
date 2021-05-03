<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MUser[]|\Cake\Collection\CollectionInterface $mUsers
 */


$this->assign('title', 'User List : ユーザーリスト');

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
              <p>
              <p><?= $this->Paginator->counter(__('{{current}}  / {{count}} ユーザー ,  ( {{page}} / {{pages}} )')) ?></p>
              </p>
            </div>
            <div>
              <?= $this->Html->link(__('Add User - 新規登録'), ['action' => 'add'], ['class' => 'btn float-right btn-outline-warning']) ?>
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
              <table class="table">
                <thead>
                  <tr>
                    <th><?= $this->Paginator->sort('user_no', 'ユーザーNo') ?></th>
                    <th><?= $this->Paginator->sort('name', 'ユーザー名') ?></th>
                    <th><?= $this->Paginator->sort('login_no', 'ログインNo') ?></th>
                    <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                    <th><?= $this->Paginator->sort('role', '権限') ?></th>
                    <th><?= $this->Paginator->sort('arrears', '累積延滞時間') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($mUsers as $mUser) : ?>
                  <tr>
                    <th scope="row"><?= $this->Number->format($mUser->user_no) ?></th>
                    <td><?= h($mUser->name) ?></td>
                    <td><?= h($mUser->login_no) ?></td>
                    <td><?= h($mUser->email) ?></td>
                    <td><?= $this->Number->format($mUser->role) ?></td>
                    <td style="text-align:right;"><?= $this->Number->format($mUser->arrears) ?><span> 時間</span></td>
                    <td class="actions">
                      <!-- <#?= $this->Html->link(__('View'), ['action' => 'view', $mUser->id], ['class' => 'btn btn-view']) ?> -->
                      <?= $this->Html->link(__('編集'), ['action' => 'edit', $mUser->id], ['class' => 'btn btn-success btn-sm']) ?>
                      <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $mUser->id], ['class' => 'btn btn-danger btn-sm', 'confirm' => __('本当にユーザー名：{0} さんを削除しますか?', $mUser->name)]) ?>
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