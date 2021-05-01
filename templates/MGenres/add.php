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
            <?= $this->Html->link(__('List M Genres'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mGenres form content">
            <?= $this->Form->create($mGenre) ?>
            <fieldset>
                <legend><?= __('Add M Genre') ?></legend>
                <?php
                    echo $this->Form->control('genre');
                    echo $this->Form->control('del_flg');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
