@extends('common')
@section('style')
    <style type="text/css">
    .main {
    -webkit-box-flex: 1;
    -ms-flex: 1 0 auto;
    flex: 1 0 auto;
    position: relative; }
    @media (min-width: 768px){
        .container--mini {
            max-width: 380px;
        }
    }
    </style>
@endsection
@section('body')
<main id="main" class="site-main main">
    <section class="section">
        <div class="container container--mini">
        <form name="loginform" id="loginform" action="https://themes.getbootstrap.com/wp-login.php" method="post" _lpchecked="1">

        <div class="form-group">
            <label for="user_login">Email</label>
            <input type="text" name="log" id="user_login" class="form-control" value="" size="20" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="user_pass">Password</label>
            <!--
            <a class="form-sublink" href="https://themes.getbootstrap.com/my-account/lost-password/">Forgot password?</a>-->
            <input type="password" name="pwd" id="user_pass" class="form-control" value="" size="20" autocomplete="off">
        </div>
        <div class="form-group">
          <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-brand btn-block mb-4" value="Sign In">
          <input type="hidden" name="redirect_to" value="https://themes.getbootstrap.com/my-account/">
    </div>

    </form>
    <p class="small text-center text-gray-soft">Don't have an account yet? <a href="https://themes.getbootstrap.com/my-account/">Sign up</a></p>
</div>
            </div>
        </div>
        </section>
        </main>
@endsection
