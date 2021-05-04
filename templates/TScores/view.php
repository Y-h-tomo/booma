<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TScore $tScore
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit T Score'), ['action' => 'edit', $tScore->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete T Score'), ['action' => 'delete', $tScore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tScore->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List T Scores'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New T Score'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tScores view content">
            <h3><?= h($tScore->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('M User') ?></th>
                    <td><?= $tScore->has('m_user') ? $this->Html->link($tScore->m_user->name, ['controller' => 'MUsers', 'action' => 'view', $tScore->m_user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('T Book') ?></th>
                    <td><?= $tScore->has('t_book') ? $this->Html->link($tScore->t_book->name, ['controller' => 'TBooks', 'action' => 'view', $tScore->t_book->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Score') ?></th>
                    <td><?= h($tScore->score) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($tScore->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($tScore->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($tScore->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Del Flg') ?></th>
                    <td><?= $tScore->del_flg ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
