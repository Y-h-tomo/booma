<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MUser $mUser
 */

use function PHPSTORM_META\type;

$this->assign('title', 'Add User : ユーザー登録');

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
                        [1 => '１：一般ユーザー', 2 => '２：担当者', 3 => '３：管理者'],
                        ['class' => 'form-control']
                    );  ?>
                    <span class="form-bar"></span>
                </div>
                <input type="hidden" name="del_flg" value="0">
                <?= $this->Form->button('Register - 登録', ['type' => 'submit', 'class' => 'btn-primary btn']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>