<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MGenre[]|\Cake\Collection\CollectionInterface $mGenres
 */
?>
<div class="mGenres index content">
    <?= $this->Html->link(__('New M Genre'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('M Genres') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('genre') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('del_flg') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mGenres as $mGenre): ?>
                <tr>
                    <td><?= $this->Number->format($mGenre->id) ?></td>
                    <td><?= h($mGenre->genre) ?></td>
                    <td><?= h($mGenre->created) ?></td>
                    <td><?= h($mGenre->modified) ?></td>
                    <td><?= h($mGenre->del_flg) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $mGenre->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mGenre->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mGenre->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mGenre->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
