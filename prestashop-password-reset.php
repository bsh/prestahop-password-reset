<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Prestashop password reset</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Created by Laszlo Kovacs. Latest version: https://github.com/bsh/prestashop-password-reset -->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="text-center">Prestashop password reset</h1>
            <?php
            if ((!file_exists('init.php') || !file_exists('config/config.inc.php')) && _COOKIE_KEY_ != '') {
                echo '<div class="alert alert-danger"><strong>Error: </strong> An include file not found. Check the path.</div>';
            } else {
                include('config/config.inc.php');
                require_once('init.php');
            }

            if (isset($_POST['email']) && isset($_POST['password']) && $_POST['email'] != '' && $_POST['password'] != '') {
                $sql = 'SELECT id_employee FROM ' . _DB_PREFIX_ . 'employee where email = "' . PSQL($_POST['email']) . '" LIMIT 1';
                if ($results = Db::getInstance()->ExecuteS($sql)) {
                    if (isset($results[0]['id_employee']) && $results[0]['id_employee'] != '') {
                        $query = Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . 'employee SET passwd = "' . md5(PSQL(_COOKIE_KEY_ . $_POST['password'])) . '" WHERE email = "' . PSQL($_POST['email']) . '" LIMIT 1');
                        if (!$query) {
                            echo '<div class="alert alert-danger"><strong>Error: </strong> Mysql error.</div>';
                            die();
                        } else {
                            echo '<div class="alert alert-success">Done. Don\'t forgete to <a href="?delete">delete</a> this script.</div>';
                        }
                    } else echo '<div class="alert alert-danger"><strong>Error: </strong> E-mail not found.</div>';
                } else echo '<div class="alert alert-danger"><strong>Error: </strong> E-mail not found.</div>';
            } else if (isset($_GET['delete'])) {
                unlink(__FILE__);
                echo '<div class="alert alert-success">Done. File deleted.</div>';
                die();
            } else {
                ?>
                <form action='' method="POST">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">New password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn pull-right">Change password</button>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>