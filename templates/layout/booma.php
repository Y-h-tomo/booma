<?php




//ANCHOR php code
$cakeDescription = 'Booma:社内書籍管理アプリケーション';
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


$page = $this->name;
$action = $this->getRequest()->getParam('action');
$book_page = ['TBooks','THistories','TFavorites'];
$query = $this->request->getServerParams()['QUERY_STRING'];

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <?= $this->Html->charset() ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>
    <?= $cakeDescription ?>:
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>

  <!-- テーマ部分 -->
  <meta name="keywords"
    content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
  <meta name="author" content="Codedthemes" />
  <!-- Favicon icon -->
  <link rel="icon" href="/assets/images/book-solid.svg" type="image/x-icon">
  <!-- Google font-->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
  <!-- waves.css -->
  <link rel="stylesheet" href="/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
  <!-- Required Fremwork -->
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.min.css">
  <!-- waves.css -->
  <link rel="stylesheet" href="/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
  <!-- themify icon -->
  <link rel="stylesheet" type="text/css" href="/assets/icon/themify-icons/themify-icons.css">
  <!-- font-awesome-n -->
  <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome-n.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
  <!-- scrollbar.css -->
  <link rel="stylesheet" type="text/css" href="/assets/css/jquery.mCustomScrollbar.css">
  <!-- Style.css -->
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css">


  <!-- スクラッチ開発分載せ替え -->

  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/jquery.raty.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
    crossorigin="anonymous" />
  <link rel="stylesheet" href="/css/remodal.css">
  <link rel="stylesheet" href="/css/remodal-default-theme.css">

  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</head>



<body>
  <!-- Pre-loader start -->
  <div class="theme-loader">
    <div class="loader-track">
      <div class="preloader-wrapper">
        <div class="spinner-layer spinner-blue">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="gap-patch">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
        <div class="spinner-layer spinner-red">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="gap-patch">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>

        <div class="spinner-layer spinner-yellow">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="gap-patch">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>

        <div class="spinner-layer spinner-green">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="gap-patch">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Pre-loader end -->
  <div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

      <!-- /* -------------------------------------------------------------------------- */
/*// ANCHOR                                    ヘッダー                                    */
/* -------------------------------------------------------------------------- */ -->

      <!-- ヘッダーメニュー -->
      <nav class="navbar header-navbar pcoded-header">
        <div class="navbar-wrapper">
          <div class="navbar-logo">
            <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
              <i class="ti-menu"></i>
            </a>

            <!-- ヘッダーロゴ -->
            <a href="/">
              <img class="img-fluid" src="/assets/images/booma-logo1.png" alt="Theme-Logo" />
            </a>
            <a class="mobile-options waves-effect waves-light">
              <i class="ti-more"></i>
            </a>
          </div>

          <div class="navbar-container container-fluid">
            <ul class="nav-left">
              <li>
                <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
              </li>
              <li>
                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                  <i class="ti-fullscreen"></i>
                </a>
              </li>
              <li>
                <h6 class="m-t-20 text-light"><?= $this->fetch('title') ?></h6>
              </li>
            </ul>
            <!-- ヘッダーユーザーアイコンメニュー -->
            <ul class="nav-right">
              <?php if (empty($session->check('User.name'))) : ?>
              <li>
                <a href="<?= $this->Url->build(['controller' => 'MUsers', 'action' => 'login']); ?>">
                  <i class="fas fa-sign-in-alt"></i> Login
                </a>
              </li>
              <?php endif; ?>
              <li class="user-profile header-notification">
                <a href="#!" class="waves-effect waves-light">
                  <img src="/assets/images/user-solid.svg" class="img-radius" alt="User-Profile-Image">
                  <span><?= $LoginName ?></span>
                  <?php if ($session->check('User.name')) : ?>
                  <i class="ti-angle-down"></i>
                  <?php endif; ?>
                </a>
                <?php if ($session->check('User.name')) : ?>
                <ul class="show-notification profile-notification">
                  <li class="waves-effect waves-light">
                    <a href="#!">
                      <i class="ti-settings"></i> Settings
                    </a>
                  </li>
                  <li class="waves-effect waves-light">
                    <a href="user-profile.html">
                      <i class="ti-user"></i> Profile
                    </a>
                  </li>
                  <li class="waves-effect waves-light">
                    <a href="<?= $this->Url->build(['controller' => 'MUsers', 'action' => 'logout']); ?>">
                      <i class="ti-layout-sidebar-left"></i> Logout
                    </a>
                  </li>
                </ul>
                <?php endif; ?>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="pcoded-main-container">
        <div class="pcoded-wrapper">

          <!-- /* -------------------------------------------------------------------------- */
/*// ANCHOR                                    サイドバー                                   */
/* -------------------------------------------------------------------------- */ -->

          <!-- サイドメニュー -->
          <nav class="pcoded-navbar">
            <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
            <div class="pcoded-inner-navbar main-menu">
              <div class="">
                <!-- ユーザーアイコン -->
                <!-- <div class="main-menu-header">
                  <img class="img-80 img-radius" src="/assets/images/user-solid.svg" alt="User-Profile-Image">
                  <div class="user-details">
                    <span id="more-details"> <#?= $LoginName ?>

                      <#?php if ($session->check('User.name')) : ?>
                        <i class="fa fa-caret-down"></i>
                      <#?php endif; ?>
                    </span>
                  </div>
                </div> -->

                <!-- ユーザーリスト（アコーディオン） -->
                <div class="main-menu-content">
                  <?php if ($session->check('User.name')) : ?>
                  <ul>
                    <li class="more-details">
                      <a href="user-profile.html"><i class="ti-user"></i>View Profile</a>
                      <a href="#!"><i class="ti-settings"></i>Settings</a>
                      <a href="<?= $this->Url->build(['controller' => 'MUsers', 'action' => 'logout']); ?>">
                        <i class="ti-layout-sidebar-left"></i> Logout
                      </a>
                    </li>
                  </ul>
                  <?php endif; ?>
                </div>
              </div>

              <!-- リンクメニュー -->

              <!-- ANCHOR 検索メニュー -->
              <?php if ( ( in_array($page, $book_page) && $action == 'index') ||  ($page == 'THistories' && $action == 'view')): ?>

              <div class="pcoded-navigation-label">Search</div>
              <div class="p-15 p-b-0" id="js-search-book">
                <form class="form-material" method="get">
                  <div class="form-group form-primary">
                    <input type="text" name="search_books" class="form-control" v-model="name">
                    <span class="form-bar"></span>
                    <label class="float-label"><i class="fa fa-search m-r-10"></i>Search Books:書籍名</label>
                  </div>

                  <div class="p-b-10">
                    <span class="badge badge-pill badge-primary ml-1" v-for="genre in genres">
                      {{ genre }}
                    </span>
                  </div>

                  <div class="form-group form-primary">
                    <div>
                      <a href="#modal_b"><button type="button"
                          class="btn btn-outline-primary btn-sm">ジャンル選択</button></a>
                      <button type="button" class="btn btn-info btn-sm" @click.prevent='clear'>再選択</button>
                    </div>


                    <!-- /* --------------------------------- モーダルエリア -------------------------------- */ -->

                    <div class="remodal col" data-remodal-id="modal_b">
                      <?php foreach ($genres as $g) : ?>
                      <input type="checkbox" value="<?= $g['id'] . ':' . $g['genre'] ?>" v-model="genres"
                        :disabled="isDisabled" id="genre-btn-<?= $g['id'] ?>" class="genre-box">
                      <label class="genre-btn btn" for="genre-btn-<?= $g['id'] ?>"><?= $g['genre'] ?>
                      </label>
                      <?php endforeach; ?>
                      <br>
                      <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                      <button type="button" class="btn btn-info" @click.prevent='clear'>再選択</button>
                    </div>
                    <!-- /* -------------------------------------------------------------------------- */ -->

                    <input type="hidden" name="genre" :value="genres">
                  </div>
                  <input type="submit" class="btn btn-primary" value="検索">
                  <button type="button" class="btn btn-outline-secondary" @click.prevent="reset">リセット</button>
                </form>
              </div>
              <?php endif; ?>

              <?php if ($page == 'MUsers' && $action == 'index') : ?>
              <div class="p-15 p-b-0" id="js-search-user">
                <form class="form-material" method="get">
                  <div class="form-group form-primary">
                    <input type="text" name="search_users" class="form-control" v-model="name">
                    <span class="form-bar"></span>
                    <label class="float-label"><i class="fa fa-search m-r-10"></i>Search Users:ユーザー名</label>
                  </div>
                  <div class="search-role form-group">
                    <input type="radio" name="search_role" value="1" class="role-box" id="role1" v-model="role">
                    <label for="role1" class="btn role-btn">一般ユーザー
                    </label>
                    <input type="radio" name="search_role" value="2" class="role-box" id="role2" v-model="role">
                    <label for="role2" class="btn role-btn">担当者
                    </label>
                    <input type="radio" name="search_role" value="3" class="role-box" id="role3" v-model="role">
                    <label for="role3" class="btn role-btn">管理者
                    </label>
                  </div>
                  <input type="submit" class="btn btn-primary" value="検索">
                  <button type="button" class="btn btn-outline-secondary" @click.prevent="reset">リセット</button>
                </form>
              </div>
              <?php endif; ?>


              <!-- ANCHOR リスト（アコーディオン） -->
              <div class="pcoded-navigation-label">Navigation</div>
              <ul class="pcoded-item pcoded-left-item">
                <li>
                  <a href="/" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-book"></i></span>
                    <span class="pcoded-mtext">Book List</span>
                    <span class="pcoded-mcaret"></span>
                  </a>
                </li>
              </ul>
              <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu ">
                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>M</b></span>
                    <span class="pcoded-mtext">My Menu</span>
                    <span class="pcoded-mcaret"></span>
                  </a>
                  <?php if ($user_id) : ?>
                  <ul class="pcoded-submenu">
                    <li class="">
                      <a href="/t-histories/view" class="waves-effect waves-dark">
                        <span class="pcoded-mtext">My Page</span><br>
                        <span class="pcoded-mtext">レンタル中書籍</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <li class="">
                      <a href="/t-histories/index" class="waves-effect waves-dark">
                        <span class="pcoded-mtext">My History</span><br>
                        <span class="pcoded-mtext">レンタル履歴</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <li class="">
                      <a href="/t-favorites/index" class="waves-effect waves-dark">
                        <span class="pcoded-mtext">My Favorite</span><br>
                        <span class="pcoded-mtext">お気に入り</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                  </ul>
                  <?php else : ?>
                  <ul class="pcoded-submenu">
                    <li class="">
                      <a href="/m-users/login" class="waves-effect waves-dark">
                        <span class="pcoded-mtext text-danger">My Menu利用にはログインが必要です</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                  </ul>
                  <?php endif; ?>

                </li>
              </ul>

              <!-- /* -------------------------------- CSV出力  書籍 ------------------------------- */ -->

              <?php if ( ( in_array($page, $book_page) && $action == 'index') ||  ($page == 'THistories' && $action == 'view')): ?>
              <ul class="pcoded-item pcoded-left-item">
                <li class="">
                  <a href="?csv=1&<?= $query ?>" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-write"></i><b>C</b></span>
                    <span class="pcoded-mtext">CSV Export</span>
                    <span class="pcoded-mcaret"></span>
                  </a>
                </li>
              </ul>
              <?php endif; ?>

              <!-- /* ------------------------------- csv出力 ユーザー ------------------------------- */ -->

              <?php if ( ($page == 'MUsers') && ($action == 'index')) : ?>
              <ul class="pcoded-item pcoded-left-item">
                <li class="">
                  <a href="?csv=1&<?= $query ?>" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-write"></i><b>C</b></span>
                    <span class="pcoded-mtext">CSV Export</span>
                    <span class="pcoded-mcaret"></span>
                  </a>
                </li>
              </ul>
              <?php endif; ?>

              <!-- ANCHOR----- レンタル処理 --------------------------------- */ -->
              <?php if ($page == 'TBooks' && $action == 'view') : ?>
              <ul class="pcoded-item pcoded-left-item">
                <li class="bg-warning">
                  <?php if ($user_id) : ?>
                  <?php if (($tBook['remain']) !== 0) : ?>
                  <a href="#modal_b<?= $tBook['id'] ?>">レンタル</a>
                  <!-- /* --------------------------------- レンタルモーダルエリア -------------------------------- */ -->
                  <div class="remodal col" data-remodal-id="modal_b<?= $tBook['id'] ?>">
                    <p>※<?= $tBook['name'] . 'をレンタルしますか？'  ?> </p>
                    <?= $this->Form->postButton(__('レンタル'), ['controller' => 'tHistories', 'action' => 'add'], ['data' => ['t_books_id' => $tBook['id'], 'm_users_id' => $user_id], 'class' => 'btn btn-warning m-b-5']) ?>
                    <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                  </div>
                  <!-- /* -------------------------------------------------------------------------- */ -->
                  <?php endif; ?>
                </li>
                <li class="bg-dark">
                  <?php if ($tBook['remain'] < $tBook['quantity']) : ?>
                  <a href="#modal_r<?= $tBook['id'] ?>">返却</a>
                  <!-- /* --------------------------------- 返却モーダルエリア -------------------------------- */ -->
                  <div class="remodal col" data-remodal-id="modal_r<?= $tBook['id'] ?>">
                    <p>※<?= $tBook['name'] . 'を返却しますか？'  ?> </p>
                    <?= $this->Form->postButton(__('返却'), ['controller' => 'tHistories', 'action' => 'delete'], ['data' => ['t_books_id' => $tBook['id'], 'm_users_id' => $user_id], 'class' => 'btn btn-dark m-b-5']) ?>
                    <button data-remodal-action="cancel" class="remodal-cancel btn">閉じる</button>
                  </div>
                  <!-- /* -------------------------------------------------------------------------- */ -->
                  <?php endif; ?>
                  <?php else : ?>
                  <span class="text-danger">レンタル・返却機能の利用にはログインが必要です</span>
                  <?php endif; ?>

                  <span class="pcoded-mcaret"></span>

                </li>
              </ul>
              <?php endif; ?>

              <!-- ANCHOR 管理メニューリスト（アコーディオン） -->
              <?php if ($role == '2' || $role == '3') : ?>
              <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu ">
                  <a href="javascript:void(0)" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-hummer"></i><b>B</b></span>
                    <span class="pcoded-mtext">Admin : 管理</span>
                    <span class="pcoded-mcaret"></span>
                  </a>
                  <ul class="pcoded-submenu">
                    <li class="">
                      <a href="<?= $this->Url->build(['controller' => 'TBooks', 'action' => 'add']) ?>"
                        class="waves-effect waves-dark">
                        <span class="pcoded-mtext">Add Book</span><br>
                        <span class="pcoded-mtext">書籍登録</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <li class="">
                      <a href="<?= $this->Url->build(['controller' => 'MGenres', 'action' => 'index']) ?>"
                        class="waves-effect waves-dark">
                        <span class="pcoded-mtext">Genre List</span><br>
                        <span class="pcoded-mtext">ジャンル一覧</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <?php if ($role == '3') : ?>
                    <li class="">
                      <a href="<?= $this->Url->build(['controller' => 'MGenres', 'action' => 'add']) ?>"
                        class="waves-effect waves-dark">
                        <span class="pcoded-mtext">Add Genre</span><br>
                        <span class="pcoded-mtext">ジャンル登録</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <li class="">
                      <a href="<?= $this->Url->build(['controller' => 'MUsers', 'action' => 'index']) ?>"
                        class="waves-effect waves-dark">
                        <span class="pcoded-mtext">User List</span><br>
                        <span class="pcoded-mtext">ユーザーリスト</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <li class="">
                      <a href="<?= $this->Url->build(['controller' => 'MUsers', 'action' => 'add']) ?>"
                        class="waves-effect waves-dark">
                        <span class="pcoded-mtext">Add User</span><br>
                        <span class="pcoded-mtext">ユーザー登録</span>
                        <span class="pcoded-mcaret"></span>
                      </a>
                    </li>
                    <?php endif; ?>
                  </ul>
                </li>
              </ul>
              <?php endif; ?>
            </div>
          </nav>


          <div class="pcoded-content">

            <!--ANCHOR ページコンテンツヘッダー -->
            <div class="page-header">
              <div class="page-block">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <div class="page-header-title">
                      <h5 class="m-b-10"><?= $this->fetch('title') ?></h5>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <ul class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a href="/"> <i class="fa fa-home"></i> </a>
                      </li>
                      <li class="breadcrumb-item"><a href="#!"><?= $this->fetch('title') ?></a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <!-- Page-header end -->

            <div class="pcoded-inner-content">

              <!-- default.phpから移設部分 -->
              <!--  start-->
              <main class="main">
                <div class="container">
                  <div class="flash">
                    <?= $this->Flash->render() ?>
                  </div>
                  <?= $this->fetch('content') ?>
                </div>
              </main>
              <footer>
              </footer>
              <!-- end -->

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Required Jquery -->
    <script type="text/javascript" src="/assets/js/jquery/jquery.min.js "></script>
    <script type="text/javascript" src="/assets/js/jquery-ui/jquery-ui.min.js "></script>
    <script type="text/javascript" src="/assets/js/popper.js/popper.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap/js/bootstrap.min.js "></script>


    <!-- vue.jsのCDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script type="text/javascript" src="/js/vue.js"></script>

    <!-- axios CDN -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- waves js -->
    <script src="/assets/pages/waves/js/waves.min.js"></script>

    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="/assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- slimscroll js -->
    <script src="/assets/js/jquery.mCustomScrollbar.concat.min.js "></script>

    <!-- menu js -->
    <script src="/assets/js/pcoded.min.js"></script>
    <script src="/assets/js/vertical/vertical-layout.min.js "></script>
    <script type="text/javascript" src="/assets/js/script.js "></script>

    <!-- スクラッチ開発分載せ替え -->
    <script type="text/javascript" src="/js/remodal.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <script type="text/javascript" src="/js/jquery.raty.js"></script>
    <script type="text/javascript" src="/js/preview.js"></script>

</body>

</html>