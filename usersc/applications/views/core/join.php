<?php
// This is a user-facing page
/*
UserSpice 5
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ini_set('allow_url_fopen', 1);
header('X-Frame-Options: DENY');
require_once '../../../../users/init.php';

$hooks = getMyHooks();

if ($user->isLoggedIn()) {
    Redirect::to($us_url_root.'index.php');
}

includeHook($hooks, 'pre');

$form_method = 'POST';
$form_action = 'join.php';
$vericode = randomstring(15);

//Decide whether or not to use email activation
$act = $db->query('SELECT * FROM email')->first()->email_act;

$errors = [];
$form_valid = false;

//If you say in email settings that you do NOT want email activation,
//new users are active in the database, otherwise they will become
//active after verifying their email.
if ($act == 1) {
    $pre = 0;
} else {
    $pre = 1;
}

if (Input::exists()) {
    $token = $_POST['csrf'];
    if (!Token::check($token)) {
        include $abs_us_root.$us_url_root.'usersc/scripts/token_error.php';
    }

    $fname = Input::get('fname');
    $lname = Input::get('lname');
    $email = Input::get('email');
    $username = Input::get('username');


    $validation = new Validate();
    if (pluginActive('userInfo', true)) {
        $is_not_email = false;
    } else {
        $is_not_email = true;
    }

    $validation->check($_POST, [
        'username' => [
            'display' => lang('GEN_UNAME'),
            'is_not_email' => $is_not_email,
            'required' => true,
            'min' => $settings->min_un,
            'max' => $settings->max_un,
            'unique' => 'users',
        ],
        'fname' => [
            'display' => lang('GEN_FNAME'),
            'required' => true,
            'min' => 1,
            'max' => 60,
        ],
        'lname' => [
            'display' => lang('GEN_LNAME'),
            'required' => true,
            'min' => 1,
            'max' => 60,
        ],
        'email' => [
            'display' => lang('GEN_EMAIL'),
            'required' => true,
            'valid_email' => true,
            'unique' => 'users',
            'min' => 5,
            'max' => 100,
        ],

        'password' => [
            'display' => lang('GEN_PASS'),
            'required' => true,
            'min' => $settings->min_pw,
            'max' => $settings->max_pw,
        ],
        'confirm' => [
            'display' => lang('PW_CONF'),
            'required' => true,
            'matches' => 'password',
        ],
    ]);

    if ($eventhooks = getMyHooks(['page' => 'joinAttempt'])) {
        includeHook($eventhooks, 'body');
    }

    if ($validation->passed()) {
        $form_valid = true;
        //add user to the database
        $user = new User();
        $join_date = date('Y-m-d H:i:s');
        $params = [
                    'fname' => Input::get('fname'),
                    'email' => $email,
                    'username' => $username,
                    'vericode' => $vericode,
                    'join_vericode_expiry' => $settings->join_vericode_expiry,
                    ];
        $vericode_expiry = date('Y-m-d H:i:s');

        if ($act == 1) {
            //Verify email address settings
            $to = rawurlencode($email);
            $subject = html_entity_decode($settings->site_name, ENT_QUOTES);
            $body = email_body('_email_template_verify.php', $params);
            email($to, $subject, $body);
            $vericode_expiry = date('Y-m-d H:i:s', strtotime("+$settings->join_vericode_expiry hours", strtotime(date('Y-m-d H:i:s'))));
        }
        try {
            if(isset($_SESSION['us_lang'])){
                $newLang = $_SESSION['us_lang'];
            }else{
                $newLang = $settings->default_language;
            }
            $fields = [
                'username' => $username,
                'fname' => ucfirst(Input::get('fname')),
                'lname' => ucfirst(Input::get('lname')),
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password', true), PASSWORD_BCRYPT, ['cost' => 12]),
                'permissions' => 1,
                'join_date' => $join_date,
                'email_verified' => $pre,
                'vericode' => $vericode,
                'vericode_expiry' => $vericode_expiry,
                'oauth_tos_accepted' => true,
                'language'=>$newLang,
                            ];
            $activeCheck = $db->query('SELECT active FROM users');
            if (!$activeCheck->error()) {
                $fields['active'] = 1;
            }
            $theNewId = $user->create($fields);

            includeHook($hooks, 'post');
        } catch (Exception $e) {
            if ($eventhooks = getMyHooks(['page' => 'joinFail'])) {
                includeHook($eventhooks, 'body');
            }
            die($e->getMessage());
        }
        if ($form_valid == true) {
            //this allows the plugin hook to kill the post but it must delete the created user
            include $abs_us_root.$us_url_root.'usersc/scripts/during_user_creation.php';

            if ($act == 1) {
                logger($theNewId, 'User', 'Registration completed and verification email sent.');

                // Redirect::to($us_url_root . "users/complete.php?action=thank_you_verify");
                Redirect::to("index.php");


            } else {
                logger($theNewId, 'User', 'Registration completed.');
                if (file_exists($abs_us_root.$us_url_root.'usersc/views/_joinThankYou.php')) {

                    Redirect::to($us_url_root . "users/complete.php?action=thank_you_join");

                } else {
                    Redirect::to($us_url_root . "users/complete.php?action=thank_you");
                }

            }
        }

    }else{
        $errors = $validation->errors();

        foreach($validation->_errors as $e){
            usError($e);
        }
        // Redirect::to(currentPage());
    } //Validation
} //Input exists

?>

<?php
$qs_ggsxxsh 		= $db->query("SELECT files.web_path as url_logo, posisi_logo_login FROM ggsxxsh LEFT JOIN files ON files.id = ggsxxsh.id_files_company_logo");
$rs_ggsxxsh	= $qs_ggsxxsh->first();
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Register</title>

    <?php
        require_once($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/template_css_load.php');
    ?>

</head>

<body class="gray-bg">
    <?php
        if($settings->registration == 1) {
        // jika boleh register
        // BEGIN form register
    ?>
            <!-- <div class="middle-box text-center loginscreen animated fadeInDown"> -->
            <div class="loginColumns">
                <?php
                    includeHook($hooks, 'body');
                ?>
                <div>
                    <div>
                        <img class="" style="width:40%" src="<?php echo $rs_ggsxxsh->url_logo;?>"></img> 
                    </div>
                    <h3><?=$settings->site_name?> Registration</h3>
                    <?php if(count($errors) > 0 ){ ?>
                        <div class="bg-danger">
                            <ul>
                    <?php 
                        foreach($errors as $err){
                            echo '<li>' . $err . '</li>';
                        } 
                    ?>
                        </ul></div>
                    <?php }else{
                        echo '';
                    } ?>

                    
                    <hr/>
                    <form class="m-t" role="form" action="" method="POST" id="payment-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="" value="<?php if (!$form_valid && !empty($_POST)) {
                                        echo $username;
                                    } ?>"
                                    required autofocus>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="email" id="email" placeholder="" value="<?php if (!$form_valid && !empty($_POST)) {
                                        echo $email;
                                    } ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nama Depan</label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="" value="<?php if (!$form_valid && !empty($_POST)) {
                                        echo $fname;
                                    } ?>"
                                    required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nama Belakang</label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="" value="<?php if (!$form_valid && !empty($_POST)) {
                                        echo $lname;
                                    } ?>"
                                    required>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                            $character_range = lang("GEN_MIN") . " " . $settings->min_pw . " " . lang("GEN_AND") . " " . $settings->max_pw . " " . lang("GEN_MAX") . " " . lang("GEN_CHAR");
                            $character_statement = '<span id="character_range" class="text-muted">' . $character_range . ' </span>';

                            if ($settings->req_cap == 1) {
                                $num_caps = '1'; //Password must have at least 1 capital
                                if ($num_caps != 1) {
                                $num_caps_s = 's';
                                }
                                $num_caps_statement = '<span id="caps" class="text-muted">' . lang("JOIN_HAVE") . $num_caps . lang("JOIN_CAP") . '</span>';
                            }

                            if ($settings->req_num == 1) {
                                $num_numbers = '1'; //Password must have at least 1 number
                                if ($num_numbers != 1) {
                                $num_numbers_s = 's';
                                }

                                $num_numbers_statement = '<span id="number" class="text-muted">' . lang("JOIN_HAVE") . $num_numbers . " " . lang("GEN_NUMBER") . '</span>';
                            }
                            $password_match_statement = '<span id="password_match" class="text-muted">' . lang("JOIN_TWICE") . '</span>';
                        ?>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="ibox-content">
                                    <p><strong><?= lang("PW_SHOULD"); ?></strong></p>
                                    <ul class="list-unstyled">
                                        <li>
                                            <span id="character_range_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $character_statement; ?>
                                        </li>
                                        <?php
                                        if ($settings->req_cap == 1) { ?>
                                            <li>
                                            <span id="num_caps_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $num_caps_statement; ?>
                                            </li>
                                        <?php }
                                        if ($settings->req_num == 1) { ?>
                                            <li><span id="num_numbers_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $num_numbers_statement; ?>
                                            </li>
                                        <?php } ?>

                                        <li><span id="password_match_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $password_match_statement; ?></li>
                                    </ul>
                                    <p><a class="nounderline" id="password_view_control" style="cursor: pointer;"><span class="fa fa-eye"></span> <?= lang("PW_SHOWS"); ?></a></p>

                                    <div class="form-group">
                                        <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Password" required aria-describedby="passwordhelp">
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input class="form-control" type="password" name="confirm" id="confirm" placeholder="Password" required >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                
                            </div>
                            <div class="col-lg-6">
                                <input type="hidden" value="<?= Token::generate(); ?>" name="csrf">
                            </div>
                        </div>
                        <br>
                        <?php
                            includeHook($hooks, 'form');
                            // include($abs_us_root . $us_url_root . 'usersc/scripts/additional_join_form_fields.php');
                        ?>

                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>
                            </div>
                            <div class="col-lg-6">
                                <a class="btn btn-sm btn-warning btn-block" href="login.php">Login</a>
                            </div>
                        </div>
                        
                    </form>
                    
                </div>

                <hr/>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Brewed with <i class="fa fa-heart text-danger"></i></strong> <a href="http://www.solusi-indonesia.com" target="_blank">Solusi Indonesia</a>
                    </div>
                    <div class="col-md-6 text-right">
                    <small>© 2016-<script>document.write(new Date().getFullYear())</script></small>
                    </div>
                </div>
            </div>
    <?php
        // END form register
        }else{
            // BEGIN can't register
    ?>
            <div class="row">
                <div class="col-12 text-center py-5">
                    <h1><?=lang("JOIN_SUC");?><?=$settings->site_name?></h1>
                    <p><?=lang("JOIN_CLOSED");?></p>
                    <p><a href="login.php" class="btn btn-primary"><?=lang("SIGNIN_TEXT");?></a></p>
                </div>
            </div>
    <?php
        // END can't register
        }
    ?>

    <!-- Mainly scripts -->
    <?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
    <script>
        $(document).ready(function(){

            $("#password").keyup(function() {
                var pswd = $("#password").val();

                //validate the length
                if (pswd.length >= <?= $settings->min_pw ?> && pswd.length <= <?= $settings->max_pw ?>) {
                    ToggleClasses(true, $("#character_range_icon"), $("#character_range"));
                } else {
                    ToggleClasses(false, $("#character_range_icon"), $("#character_range"));
                }

                //validate capital letter
                if (pswd.match(/[A-Z]/)) {
                    ToggleClasses(true, $("#num_caps_icon"), $("#caps"));
                } else {
                    ToggleClasses(false, $("#num_caps_icon"), $("#caps"));
                }

                //validate number
                if (pswd.match(/\d/)) {
                    ToggleClasses(true, $("#num_numbers_icon"), $("#number"));
                } else {
                    ToggleClasses(false, $("#num_numbers_icon"), $("#number"));
                }
                });

                $("#confirm").keyup(function() {
                var pswd = $("#password").val();
                var confirm_pswd = $("#confirm").val();

                //validate password_match
                if (pswd == confirm_pswd) {
                    ToggleClasses(true, $("#password_match_icon"), $("#password_match"));
                } else {
                    ToggleClasses(false, $("#password_match_icon"), $("#password_match"));
                }
                });

                function ToggleClasses(conditionMet, icon, text) {
                if (conditionMet) {
                    icon.removeClass("text-muted").removeClass("fa-close").addClass("text-success").addClass("fa-check");
                    text.removeClass("text-muted");
                } else {
                    icon.removeClass("text-success").removeClass("fa-check").addClass("text-muted").addClass("fa-close");
                    text.addClass("text-muted");
                }
                }
                
            $('#password_view_control').hover(function () {
                $('#password').attr('type', 'text');
                $('#confirm').attr('type', 'text');
            }, function () {
                $('#password').attr('type', 'password');
                $('#confirm').attr('type', 'password');
            });
            
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>

</body>
</html>
