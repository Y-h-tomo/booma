<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MUser[]|\Cake\Collection\CollectionInterface $mUsers
 */

$this->disableAutoLayout();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <title>Booma:Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <meta name="keywords"
    content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
  <meta name="author" content="Codedthemes" />
  <!-- Favicon icon -->

  <link rel="icon" href="/assets/images/book-solid.svg" type="image/x-icon">
  <!-- Google font-->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
  <!-- Required Fremwork -->
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap/css/bootstrap.min.css">
  <!-- waves.css -->
  <link rel="stylesheet" href="/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
  <!-- themify-icons line icon -->
  <link rel="stylesheet" type="text/css" href="/assets/icon/themify-icons/themify-icons.css">
  <!-- ico font -->
  <link rel="stylesheet" type="text/css" href="/assets/icon/icofont/css/icofont.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" type="text/css" href="/assets/icon/font-awesome/css/font-awesome.min.css">
  <!-- Style.css -->
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
</head>

<body themebg-pattern="theme1">
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

  <section class="login-block">
    <!-- Container-fluid starts -->
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <!-- Authentication card start -->
          <?= $this->Flash->render() ?>
          <?= $this->Form->create(null, ['class' => 'md-float-material form-material']) ?>
          <div class="text-center">
            <img src="/assets/images/booma-logo.png" alt="logo.png">
            <legend>Books Management App
            </legend>
          </div>
          <div class="auth-box card">
            <div class="card-block">
              <div class="row m-b-20">
                <div class="col-md-12">
                  <h3 class="text-center">Log In</h3>
                  <p>
                    <?= __('ログインNoとパスワードを入力してください') ?>
                  </p>
                </div>
              </div>
              <div class="form-group form-primary">
                <?= $this->Form->control('login_no', ['required' => true, 'class' => 'form-control']) ?>
                <span class="form-bar"></span>
              </div>
              <div class="form-group form-primary">
                <?= $this->Form->control('password', ['required' => true, 'class' => 'form-control']) ?>
                <span class="form-bar"></span>
              </div>
              <div class="row m-t-25 text-left">
                <div class="col-12">
                </div>
              </div>
              <div class="row m-t-30">
                <div class="col-md-12">
                  <?= $this->Form->submit(__('Login'), ['class' => 'btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20']); ?>
                </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-md-10">
                  <p class="text-inverse text-left"><a href="/"><b>書籍一覧に戻る</b></a></p>
                </div>
              </div>
            </div>
          </div>
          <?= $this->Form->end() ?>
          <!-- end of form -->
        </div>
        <!-- end of col-sm-12 -->
      </div>
      <!-- end of row -->
    </div>
    <!-- end of container-fluid -->
  </section>
  <!-- Required Jquery -->
  <script type="text/javascript" src="/assets/js/jquery/jquery.min.js "></script>
  <script type="text/javascript" src="/assets/js/jquery-ui/jquery-ui.min.js "></script>
  <script type="text/javascript" src="/assets/js/popper.js/popper.min.js"></script>
  <script type="text/javascript" src="/assets/js/bootstrap/js/bootstrap.min.js "></script>
  <!-- waves js -->
  <script src="/assets/pages/waves/js/waves.min.js"></script>
  <!-- jquery slimscroll js -->
  <script type="text/javascript" src="/assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
  <script type="text/javascript" src="/assets/js/common-pages.js"></script>
</body>

</html>