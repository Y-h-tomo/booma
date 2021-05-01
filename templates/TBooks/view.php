<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TBook $tBook
 */

$this->assign('title', 'View Book : 書籍詳細');

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


// debug($tBook);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5><?= $this->fetch('title') ?></h5>
            </div>
            <div class="card-block">
                <p class="sub-title"></p>
                <div class="control-group">
                    <div class="controls">
                        <p>
                            <?= $this->Html->image($tBook['image'], ['alt' => $tBook['name'] . 'の画像', 'width' => '250']); ?>
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">BookName：<br>書籍名</label>
                    <h5><?= h($tBook['name']) ?></h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">BookNo：<br>書籍No</label>
                    <div class="col-sm-4">
                        <h5><?= h($tBook['book_no']) ?></h5>
                    </div>
                    <label class="col-sm-2 col-form-label">BookGenres：<br>書籍ジャンル</label>
                    <div class="col-sm-4">
                        <?php foreach ($genres as $genre) : ?>
                            <span class="badge badge-pill badge-primary ml-1"> <?= h($genre) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Remain：<br>残り冊数</label>
                    <div class="col-sm-4">
                        <h5><?= h($tBook['remain']) ?><span>冊</span></h5>
                    </div>
                    <label class="col-sm-2 col-form-label">Quantity：<br>登録冊数</label>
                    <div class="col-sm-4">
                        <h5><?= h($tBook['quantity']) ?><span>冊</span></h5>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Price：<br>価格</label>
                    <div class="col-sm-4">
                        <h5><span>¥</span><?= number_format(h($tBook['price'])) ?></h5>
                    </div>
                    <label class="col-sm-2 col-form-label">Deadline：<br>最長レンタル時間</label>
                    <div class="col-sm-4">
                        <h5><?= h($tBook['deadline']) ?><span>時間</span></h5>
                    </div>
                </div>

                <?php if ($user_id) : ?>

                    <?php if (empty($tFavorites->toArray())) {
                        $on = "";
                        $off = "invisible";
                    } else {
                        $on = "invisible";
                        $off = "";
                    } ?>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <button id="favorite-btn" class="btn <?= $on ?> btn-info"><i class="fas fa-bookmark favorite-icon"></i><span>登録</span></button>
                            <button id="un-favorite-btn" class="btn <?= $off ?> btn-outline-info"><i class="far fa-bookmark favorite-icon"></i><span>解除</span></button>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $('#favorite-btn').click(function() {
                            var $this = $('.favorite-icon');
                            var click = true;
                            if (click) {
                                click = false;
                                $.ajax({
                                    url: "/t-favorites/add/",
                                    type: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        m_users_id: <?= $user_id ?>,
                                        t_books_id: <?= h($tBook['id']); ?>,
                                    }),
                                }).done(function(data) {
                                    $this.children('span').html(data);
                                    $this.children('i').toggleClass('far')
                                        .toggleClass('fas')
                                        .toggleClass('active');
                                    $this.toggleClass('active');
                                    $('#favorite-btn').addClass('invisible');
                                    $('#un-favorite-btn').removeClass('invisible');
                                    click = true;
                                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                                    console.log("textStatus     : " + textStatus);
                                    console.log("errorThrown    : " + errorThrown.message);
                                });
                            }
                        });
                        $('#un-favorite-btn').click(function() {
                            var $this = $('.favorite-icon');
                            var click = true;
                            if (click) {
                                click = false;
                                $.ajax({
                                    url: "/t-favorites/delete/",
                                    type: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        m_users_id: <?= $user_id ?>,
                                        t_books_id: <?= h($tBook['id']); ?>,
                                    }),
                                }).done(function(data) {
                                    $this.children('span').html(data);
                                    $this.children('i').toggleClass('far')
                                        .toggleClass('fas')
                                        .toggleClass('active');
                                    $this.toggleClass('active');
                                    $('#favorite-btn').removeClass('invisible');
                                    $('#un-favorite-btn').addClass('invisible');
                                    click = true;
                                    // alert('OK');
                                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                                    // alert("NG");
                                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                                    console.log("textStatus     : " + textStatus);
                                    console.log("errorThrown    : " + errorThrown.message);
                                });
                            }
                        });
                    </script>

                <?php endif; ?>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Outline : <br>概要</label>
                    <div class="col-sm-10">
                        <?= nl2br(h($tBook['outline'])) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($role == '2' || $role == '3') : ?>
            <aside class="column">
                <div class="side-nav">
                    <?= $this->Html->link(__('Edit：編集'), ['action' => 'edit', $tBook['id']], ['type' => 'button', 'class' => 'side-nav-item btn btn-success']) ?>
                    <?= $this->Form->postLink(__('Delete：削除'), ['action' => 'delete',  $tBook['id']], ['confirm' => __('本当に{0}を削除しますか?', $tBook['name']), 'class' => 'side-nav-item btn btn-danger']) ?>
                </div>
            </aside>
        <?php endif; ?>
    </div>
</div>