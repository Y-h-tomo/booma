<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MGenre $mGenre
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit M Genre'), ['action' => 'edit', $mGenre->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete M Genre'), ['action' => 'delete', $mGenre->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mGenre->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List M Genres'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New M Genre'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mGenres view content">
            <h3><?= h($mGenre->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Genre') ?></th>
                    <td><?= h($mGenre->genre) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($mGenre->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mGenre->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mGenre->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Del Flg') ?></th>
                    <td><?= $mGenre->del_flg ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
