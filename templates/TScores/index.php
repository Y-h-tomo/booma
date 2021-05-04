<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TScore[]|\Cake\Collection\CollectionInterface $tScores
 */
?>
<div class="tScores index content">
    <?= $this->Html->link(__('New T Score'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('T Scores') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('m_users_id') ?></th>
                    <th><?= $this->Paginator->sort('t_books_id') ?></th>
                    <th><?= $this->Paginator->sort('score') ?></th>
                    <th><?= $this->Paginator->sort('del_flg') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tScores as $tScore): ?>
                <tr>
                    <td><?= $this->Number->format($tScore->id) ?></td>
                    <td><?= $tScore->has('m_user') ? $this->Html->link($tScore->m_user->name, ['controller' => 'MUsers', 'action' => 'view', $tScore->m_user->id]) : '' ?></td>
                    <td><?= $tScore->has('t_book') ? $this->Html->link($tScore->t_book->name, ['controller' => 'TBooks', 'action' => 'view', $tScore->t_book->id]) : '' ?></td>
                    <td><?= h($tScore->score) ?></td>
                    <td><?= h($tScore->del_flg) ?></td>
                    <td><?= h($tScore->created) ?></td>
                    <td><?= h($tScore->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $tScore->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $tScore->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tScore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tScore->id)]) ?>
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
