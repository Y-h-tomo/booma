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
            <?= $this->Html->link(__('List T Scores'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tScores form content">
            <?= $this->Form->create($tScore) ?>
            <fieldset>
                <legend><?= __('Add T Score') ?></legend>
                <?php
                    echo $this->Form->control('m_users_id', ['options' => $mUsers]);
                    echo $this->Form->control('t_books_id', ['options' => $tBooks]);
                    echo $this->Form->control('score');
                    echo $this->Form->control('del_flg');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
