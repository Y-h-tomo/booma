<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MUser $mUser
 */


$this->assign('title', 'Edit User : ユーザー編集');

?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><?= $this->fetch('title') ?></h5>
            </div>
            <div class="card-block">
                <?= $this->Form->create($mUser, ['class' => 'form-material']) ?>
                <div class="form-group form-default">
                    <?= $this->Form->control('user_no', ['label' => 'UserNo：ユーザーNo', 'class' => 'form-control']); ?>
                    <span class="form-bar"></span>
                </div>
                <div class="form-group form-default">
                    <?= $this->Form->control('name', ['label' => 'Name：名前', 'class' => 'form-control']); ?>
                    <span class="form-bar"></span>
                </div>
                <div class="form-group form-default">
                    <?= $this->Form->control('login_no', ['label' => 'LoginNo：ログインNo', 'class' => 'form-control']); ?>
                    <span class="form-bar"></span>
                </div>
                <div class="form-group form-default">
                    <?= $this->Form->control('password', ['label' => 'Password：パスワード', 'class' => 'form-control']); ?>
                    <span class="form-bar"></span>
                </div>
                <div class="form-group form-default">
                    <?= $this->Form->control('email', ['label' => 'Email', 'class' => 'form-control']); ?>
                    <span class="form-bar"></span>
                </div>
                <div class="form-group form-default">
                    <label>Role：権限</label>
                    <?= $this->Form->select(
                        'role',
                        [1 => '一般ユーザー', 2 => '担当者', 3 => '管理者'],
                        ['class' => 'form-control']
                    );  ?>
                    <span class="form-bar"></span>
                </div>
                <div class="form-group form-default">
                    <?= $this->Form->control('arrears',  ['label' => 'Arrears：累積延滞時間', 'class' => 'form-control']);  ?>
                    <span class="form-bar"></span>
                </div>
                <?= $this->Form->button('Registration - 更新', ['type' => 'submit', 'class' => 'btn-submit btn']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>