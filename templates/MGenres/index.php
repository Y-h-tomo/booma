<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MGenre[]|\Cake\Collection\CollectionInterface $mGenres
 */
$this->assign('title', 'Genre List : ジャンルリスト');

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
              <p>
              <p><?= $this->Paginator->counter(__('{{current}}  / {{count}} ジャンル ,  ( {{page}} / {{pages}} )')) ?></p>
              </p>
            </div>
            <div>
              <?php if ($role == '3') : ?>
              <?= $this->Html->link(__('Add Genre - ジャンル登録'), ['action' => 'add'], ['class' => 'btn float-right btn-outline-primary']) ?>
              <?php endif; ?>
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
                    <th><?= $this->Paginator->sort('id', '登録ID') ?></th>
                    <th><?= $this->Paginator->sort('genre', 'ジャンル名') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($mGenres as $mGenre) : ?>
                  <?php if($mGenre->del_flg): ?>
                  <tr>
                    <th scope="row"><?= $this->Number->format($mGenre->id) ?></th>
                    <td class="text-white bg-secondary"><?= h($mGenre->genre) ?><br><span>OFFのため、登録/編集に使用できません。</span>
                    </td>
                    <td>
                      <?php if ($role == '3') : ?>
                      <a href="#modal_b"><button type="button" class="btn btn-outline-success btn-sm">ON</button></a>

                      <div class="remodal col" data-remodal-id="modal_b">
                        <p>書籍管理に影響が出る可能性があります。本当にジャンル：<?= $mGenre->genre ?> をONしますか?</p>
                        <?= $this->Form->postButton(__('ON'), ['action' => 'edit', $mGenre->id],
                      ['class' => 'btn btn-outline-success mb-5']) ?>
                        <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                      </div>

                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php else: ?>
                  <tr>
                    <th scope="row"><?= $this->Number->format($mGenre->id) ?></th>
                    <td><?= h($mGenre->genre) ?></td>
                    <td>
                      <?php if ($role == '3') : ?>
                      <a href="#modal_b"><button type="button" class="btn btn-outline-danger btn-sm">OFF</button></a>

                      <div class="remodal col" data-remodal-id="modal_b">
                        <p>書籍管理に影響が出る可能性があります。本当にジャンル：<?= $mGenre->genre ?> をOFFしますか?</p>
                        <?= $this->Form->postButton(__('OFF'), ['action' => 'delete', $mGenre->id],
                      ['class' => 'btn btn-outline-danger mb-5']) ?>
                        <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                      </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endif; ?>
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