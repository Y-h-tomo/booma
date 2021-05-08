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
                    <div class="col-sm-10">
                        <h5><?= h($tBook['name']) ?></h5>
                    </div>
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
                    <label class="col-sm-2 col-form-label">Deadline：<br>最長レンタル期間</label>
                    <div class="col-sm-4">
                        <h5><?= round((h($tBook['deadline'] / 24)), 2); ?><span>日</span></h5>
                    </div>
                </div>

                <!-- /* ------------------------------ ANCHOR お気に入り登録 ----------------------------- */ -->

                <?php if ($user_id) : ?>
                    <?php if (empty($tFavorites)) {
                        $on = "";
                        $off = "invisible";
                    } else {
                        $on = "invisible";
                        $off = "";
                    } ?>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Favorite : <br>お気に入り登録</label>
                        <div class="col-sm-4">
                            <button id="favorite-btn" class="btn <?= $on ?> btn-info"><i class="fas fa-bookmark favorite-icon"></i><span>登録</span></button>
                            <button id="un-favorite-btn" class="btn <?= $off ?> btn-outline-secondary"><i class="far fa-bookmark favorite-icon"></i><span>解除</span></button>
                        </div>
                    </div>
                    <script type="text/javascript">
                        // お気に入り登録
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
                        // お気に入り解除
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
                                }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert("評価処理に失敗しました。");
                                    console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                                    console.log("textStatus     : " + textStatus);
                                    console.log("errorThrown    : " + errorThrown.message);
                                });
                            }
                        });
                    </script>
                <?php endif; ?>

                <!-- /* ---------------------------------- ANCHOR 評価機能 ---------------------------------- */ -->

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Scores : <br>評価</label>
                    <div class="col-sm-5">
                        <p>平均評価 （<span class="js-score-count"><?= count($tBook->t_scores) ?></span>）件</p>
                        <div class="js-score-avg"></div>
                        <span class="js-score-avg-point"><?php printf("%03.2f", $avgScore['avg']) ?></span><span>点</span>
                    </div>
                    <div class="col-sm-5">
                        <!-- 評価の有無でadd edit 分岐 -->
                        <?php if ($user_id) : ?>
                            <p>あなたの現在評価</p>
                            <?php if (!empty($tScores)) : ?>
                                <div id="<?= $tScores['id'] ?>" class="raty raty<?= $tScores['id'] ?>">
                                </div>
                                <span class="js-score-my-point"><?php printf("%03.2f", $tScores['score']) ?></span><span>点</span>
                            <?php else : ?>
                                <div id="new" class="raty raty-new"></div>
                                <p>現在評価していません。<br>初回評価時はリロードが発生します。</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <script type="text/javascript">
                    score = $('.js-score-avg').html();
                    $('.js-score-avg').raty({
                        readOnly: true,
                        score: <?= $avgScore['avg'] =  $avgScore['avg'] ? $avgScore['avg'] : 0; ?>
                    });
                </script>
                <?php if ($user_id) : ?>
                    <!-- 評価の有無でadd edit 分岐 -->
                    <?php if (!empty($tScores)) : ?>
                        <script type="text/javascript">
                            var $score = $('.js-score-avg');
                            var $count = $('.js-score-count');
                            var $avgPoint = $('.js-score-avg-point');
                            var $myPoint = $('.js-score-my-point');
                            var click = true;
                            $("div.raty<?= $tScores['id'] ?>").raty({
                                score: <?= $tScores['score'] ?>,
                                click: function(score, evt) {
                                    if (click) {
                                        click = false;
                                        $.ajax({
                                            url: "/t-scores/edit/",
                                            type: 'POST',
                                            contentType: 'application/json',
                                            data: JSON.stringify({
                                                id: <?= $tScores['id']  ?>,
                                                m_users_id: <?= $tScores['m_users_id'] ?>,
                                                t_books_id: <?= $tScores['t_books_id'] ?>,
                                                score: score
                                            }),
                                        }).done(function(data) {
                                            $avgPoint.html('').prepend(Number(data).toFixed(2));
                                            $myPoint.html('').prepend(Number(score).toFixed(2));
                                            $score.html('').raty({
                                                readOnly: true,
                                                score: data
                                            });
                                            click = true;
                                        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                                            alert("評価処理に失敗しました。");
                                            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                                            console.log("textStatus     : " + textStatus);
                                            console.log("errorThrown    : " + errorThrown.message);
                                        });
                                    }
                                }
                            });
                        </script>
                    <?php else : ?>
                        <script type="text/javascript">
                            var $score = $('.js-score-avg');
                            var $count = $('.js-score-count');
                            var $avgPoint = $('.js-score-avg-point');
                            var $myPoint = $('.js-score-my-point');
                            var click = true;
                            $('div.raty-new').raty({
                                score: 0,
                                click: function(score, evt) {
                                    if (click) {
                                        click = false;
                                        $.ajax({
                                            url: "/t-scores/add/",
                                            type: 'POST',
                                            contentType: 'application/json',
                                            data: JSON.stringify({
                                                m_users_id: <?= $user_id ?>,
                                                t_books_id: <?= h($tBook['id']) ?>,
                                                score: score
                                            }),
                                        }).done(function(data) {
                                            $avgPoint.html('').prepend(Number(data).toFixed(2));
                                            $myPoint.html('').prepend(Number(score).toFixed(2));
                                            $score.html('').raty({
                                                readOnly: true,
                                                score: data
                                            });
                                            click = true;
                                            location.reload();
                                        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                                            alert("評価処理に失敗しました。");
                                            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                                            console.log("textStatus     : " + textStatus);
                                            console.log("errorThrown    : " + errorThrown.message);
                                        });
                                    }
                                }
                            });
                        </script>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- /* ----------------------------------- ANCHOR 概要 ----------------------------------- */ -->
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