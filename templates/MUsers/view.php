<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MUser $mUser
 */

$this->assign('title', 'View Book : ユーザー詳細');

?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit M User'), ['action' => 'edit', $mUser->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete M User'), ['action' => 'delete', $mUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mUser->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List M Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New M User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mUsers view content">
            <h3><?= h($mUser->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($mUser->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Login No') ?></th>
                    <td><?= h($mUser->login_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($mUser->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($mUser->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('User No') ?></th>
                    <td><?= $this->Number->format($mUser->user_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $this->Number->format($mUser->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Arrears') ?></th>
                    <td><?= $this->Number->format($mUser->arrears) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mUser->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mUser->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Del Flg') ?></th>
                    <td><?= $mUser->del_flg ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Password') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($mUser->password)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>