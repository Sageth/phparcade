<?php
declare(strict_types=1);
Core::stopDirectAccess();

class Users {
    protected $status;
    protected $user;
    private function __construct() {}
    public static function edit_profile_do() {
        if (!isset($_SESSION)) {
            session_start();
        }
        global $params;
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $user = $_SESSION['user'];
        $birthdate = !empty($_POST['birth_date']) ? $_POST['birth_date'] : '';
        if ($user == false) {
            $params[3] = 'failure';
        }
        if ($_FILES['uploadavatar']['name'] > '') {
            $fileExt = '';
            if (strpos($_FILES['uploadavatar']['name'], 'jpg') > "") {
                $fileExt = 'jpg';
            } elseif (strpos($_FILES['uploadavatar']['name'], 'gif') > "") {
                $fileExt = 'gif';
            } elseif (strpos($_FILES['uploadavatar']['name'], 'jpeg') > "") {
                $fileExt = 'jpeg';
            } elseif (strpos($_FILES['uploadavatar']['name'], 'png') > "") {
                $fileExt = 'png';
            }
            if ($fileExt > '') {
                // Where the file is going to be placed
                $target_path = 'uploads/';
                /* Add the original filename to our target path.
                Result is "uploads/filename.extension" */
                $target_path .= basename($_FILES['uploadavatar']['name']);
                move_uploaded_file($_FILES['uploadavatar']['tmp_name'], $target_path);
                $_POST['avatarurl'] = 'uploads/' . basename($_FILES['uploadavatar']['name']);
            } else {
                //illegal file type, so save nothing
                $_POST['avatarurl'] = '';
            }
        } else {
            $_POST['avatarurl'] = str_replace('//', "", $_POST['avatarurl']);
        }
        /* Uses index */
        $sql = 'UPDATE `members`
			SET `aim` = :aim,
				`yahoo` = :yahoo,
				`msn` = :msn,
				`twitter_id` = :twitter,
				`facebook_id` = :facebook,
				`github_id` = :github,
				`avatarurl` = :avatar,
				`birth_date` = :birthdate,
				`email` = :email
			WHERE `id` = :id';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':aim', $_POST['aim']);
            $stmt->bindParam(':avatar', $_POST['avatarurl']);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $user['id']);
            $stmt->bindParam(':github', $_POST['github_id']);
            $stmt->bindParam(':facebook', $_POST['facebook_id']);
            $stmt->bindParam(':msn', $_POST['msn']);
            $stmt->bindParam(':twitter', $_POST['twitter_id']);
            $stmt->bindParam(':yahoo', $_POST['yahoo']);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                Core::showSuccess(gettext('emailvalid'));
            } else {
                Core::showError(gettext('emailinvalid'));
            }
            Core::showError($e->getMessage());
        }
        if ($_POST['password'] != '') {
            self::userPasswordUpdateByID($user['id'], $_POST['password']);
        }
        $params[3] = 'success';
        header('Location: user/profile');
    }
    public static function userPasswordUpdateByID($id, $password) {
        /* Uses index */
        $sql = 'UPDATE `members` SET `password` = :password WHERE `id` = :memberid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':password', Users::userPasswordHash($password));
            $stmt->bindParam(':memberid', $id);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('updatesuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    public static function userPasswordHash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public static function getUserbyID($id) {
        /* Uses index */
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetMemberbyID(:userid);');
            $stmt->bindParam(':userid', $id);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($stmt->rowCount() != 1) {
                return false;
            }
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $user;
    }
    public static function getUsersAll() {
        /* Only used by admin on the get users page */
        /* Uses index */
        try {
            $sql = 'SELECT `id`,`username`,`totalgames`,`ip`,`last_login` FROM `members` ORDER BY `username` ASC;';
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->execute();
            $user = $stmt->fetchAll();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $user;
    }
    public static function getUsersCount() {
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetAllIDs();');
            $stmt->execute();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $stmt->rowCount();
    }
    public static function isUserLoggedIn() {
        return isset($_SESSION['user']) ? true : false;
    }
    public static function passwordRecoveryForm() {
        $dbconfig = Core::getDBConfig();
        if ($dbconfig['passwordrecovery'] === 'on') { ?>
        <form action='<?php echo SITE_URL; ?>' method='post'><br/>
            <?php echo gettext('username'); ?>:<br/>
            <label>
                <input type='text' name='username' id="username"/>
            </label><br/><br/>
            <?php echo gettext('email'); ?><br/>
            <label>
                <input type='text' name='email' id="email"/><br/>
            </label><br/><br/>
            <input type='submit' value='<?php echo gettext('submit'); ?>' alt='submit'/>
            <input type='hidden' name='params' value='login/recover/do' alt='recover'/>
            </form><?php
        }
    }
    public static function passwordRecovery() {
        $dbconfig = Core::getDBConfig();
        $inicfg = Core::getINIConfig();
        $status = '';
        if ($dbconfig['passwordrecovery'] === 'on') {
            /** @noinspection PhpUndefinedVariableInspection */
            $username = $args['username'] ?? trim($_POST['username']);
            $email = $args['email'] ?? $_POST['email'];
            $password = self::passwordGenerate();
            $clearpass = $password;
            $password = self::userPasswordHash($password);
            /* Only sends email if the user actually exists */
            /* Uses index */
            $sql =
                'SELECT `username`,`email` FROM `members` WHERE `username` = :username AND `email`= :useremail LIMIT 1;';
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':useremail', $email);
            $stmt->execute();
            $count = $stmt->rowCount();
            $stmt->closeCursor();
            if ($count == 1) {
                $mail = new PHPMailer();
                $body = file_get_contents(INST_DIR . 'includes/messages/forgottenmessage.txt');
                $body = nl2br(str_replace('[username]', $username, $body));
                $body = nl2br(str_replace('[password]', $clearpass, $body));
                $mail->isSMTP(); // telling the class to use SMTP
                $mail->SMTPDebug = $dbconfig['emaildebug']; //Ask for HTML-friendly debug output
                $mail->Debugoutput = 'html';
                $mail->SMTPAuth = true;                    // enable SMTP authentication
                $mail->SMTPSecure = 'tls';                    // sets the prefix to the server
                $mail->Host = $dbconfig['emailhost']; //SMTP over IPv6
                //$mail->Host = gethostbyname($dbconfig["emailhost"]); //SMTP over IPv4
                $mail->Port = $dbconfig['emailport'];
                $mail->Username = $dbconfig['emailfrom'];
                $mail->Password = $inicfg['mail']['gmailpassword'];
                $mail->setFrom($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                $mail->addReplyTo($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                $mail->Subject = 'Password Reset Request';
                $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
                $mail->msgHTML($body);
                $address = $email;
                $mail->addAddress($address, $dbconfig['emaildomain']);
                /* Uses index */
                $sql =
                    'UPDATE `members` SET `password` = :password WHERE `username` = :username AND `email` = :useremail;';
                try {
                    $stmt = mySQL::getConnection()->prepare($sql);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':useremail', $email);
                    $stmt->execute();
                    $stmt->closeCursor();
                } catch (PDOException $e) {
                    Core::showError($e->getMessage());
                }
                if (!$mail->send()) { ?>
                    <p class="bg-danger">
                    <?php echo gettext('emailfail'); ?>
                    </p><?php
                    if ($dbconfig['emaildebug'] > 0) {
                        $status = 'emailfail';
                        $mail->ErrorInfo;
                    }
                } else {
                    $status = 'recoveryemailsent';
                }
            } else {
                Core::showError(gettext('passwordrecoverinvalid'));
            }
        } else {
            $status = 'generic';
        }
        unset($mail);
        return $status;
    }
    public static function passwordGenerate($pw = "", $length = 8, $i = 0) {
        $possible = '!@#$%^&*0123456789bcdfghjkmnpqrstvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        while ($i < $length) {
            $char = $possible[random_int(0, strlen($possible) - 1)];
            if (false === strpos($pw, $char)) {
                $pw .= $char;
                ++$i;
            }
        }
        return $pw;
    }
    public static function updateUserPlaycount() {
        if (!isset($_SESSION)) {
            session_start();
        }
        /* Null checker to prevent extra log entries when user isn't logged in */
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if ($user !== null) {
            /* Uses index */
            $sql = 'UPDATE `members` SET `totalgames` = `totalgames` + 1 WHERE `id` = :userid';
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':userid', $user['id']);
            $stmt->execute();
            $stmt->closeCursor();
        }
    }
    public static function userAdd($username, $email, $status = "") {
        $dbconfig = Core::getDBConfig();
        $inicfg = Core::getINIConfig();
        if (!empty($username) || !empty($email)) {
            /* Uses index */
            $sql = 'SELECT `username`, `email` FROM `members` WHERE `username` = :username OR `email` = :useremail;';
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':useremail', $email);
            $stmt->execute();
            $rowcount = $stmt->rowCount();
            $stmt->closeCursor();
            if ($rowcount > 0) {
                $stmt->closeCursor();
                $status = 'usertaken';
            } else {
                if ($rowcount == 0) {
                    $stmt->closeCursor();
                    $password = self::passwordGenerate();
                    $clearpass = $password;
                    $password = self::userPasswordHash($password);
                    $mail = new PHPMailer();
                    $body = file_get_contents(INST_DIR . 'includes/messages/registering.txt');
                    $body = nl2br(str_replace('[username]', $username, $body));
                    $body = nl2br(str_replace('[password]', $clearpass, $body));
                    $mail->isSMTP(); // telling the class to use SMTP
                    $mail->SMTPDebug = $dbconfig['emaildebug'];
                    $mail->SMTPAuth = true;                    // enable SMTP authentication
                    $mail->SMTPSecure = 'tls';                    // sets the prefix to the server
                    $mail->Host = $dbconfig['emailhost'];
                    $mail->Port = $dbconfig['emailport'];
                    $mail->Username = $dbconfig['emailfrom'];
                    $mail->Password = $inicfg['mail']['gmailpassword'];
                    $mail->setFrom($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                    $mail->addReplyTo($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                    $mail->Subject = 'Account Creation';
                    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
                    $mail->msgHTML($body);
                    $address = $email;
                    $mail->addAddress($address, $dbconfig['emaildomain']);
                    if (!$mail->send()) {
                        $status = 'emailfail';
                        if ($dbconfig['emaildebug'] > 0) {
                            $mail->ErrorInfo;
                        }
                    } else {
                        $null = null;
                        $yes = 'Yes';
                        $no = 'No';
                        /* Inserts shouldn't use index */
                        $sql = 'INSERT INTO `members`
                                  (`id`,`username`,`password`,`email`,`active`,`admin`,`ip`)
						        VALUES
						          (:memberid, :memberusername, :memberpassword, :memberemail, :memberactive, :memberadmin, :memberip)';
                        try {
                            $stmt = mySQL::getConnection()->prepare($sql);
                            $stmt->bindParam(':memberid', $null);
                            $stmt->bindParam(':memberusername', $username);
                            $stmt->bindParam(':memberpassword', $password);
                            $stmt->bindParam(':memberemail', $email);
                            $stmt->bindParam(':memberactive', $yes);
                            $stmt->bindParam(':memberadmin', $no);
                            $stmt->bindParam(':memberip', $_SERVER['REMOTE_ADDR']);
                            $stmt->execute();
                            $stmt->closeCursor();
                        } catch (PDOException $e) {
                            Core::showError($e->getMessage());
                        }
                        $status = 'confemail';
                    }
                }
            }
        } else {
            $status = 'notallfields';
        }
        unset($mail);
        return $status;
    }
    public static function userDelete($id, $admin = 'No') {
        /* Uses index */
        $sql = 'DELETE FROM `members` WHERE `id` = :userid AND `admin` = :admin;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':userid', $id);
            $stmt->bindParam(':admin', $admin);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('deletesuccess'));
        } catch (PDOException $e) {
            Core::showWarning(gettext('deleteadminfailure'));
            Core::showError($e->getMessage());
        }
    }
    public static function userEdit($id) {
        /* Uses index */
        $sql = 'UPDATE 	`members`
					SET    	`username` = :username,
							`email` = :email,
							`active` = :active,
							`twitter_id` = :twitter,
							`aim` = :aim,
							`yahoo` = :yahoo,
							`msn` = :msn,
							`admin` = :isadmin
					WHERE  	`id` = :memberid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':username', $_POST['username']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':active', $_POST['active']);
            $stmt->bindParam(':twitter', $_POST['twitter_id']);
            $stmt->bindParam(':aim', $_POST['aim']);
            $stmt->bindParam(':yahoo', $_POST['yahoo']);
            $stmt->bindParam(':msn', $_POST['msn']);
            $stmt->bindParam(':isadmin', $_POST['isadmin']);
            $stmt->bindParam(':memberid', $id);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('updatesuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    public static function userVerifyPassword($username, $password) {
        /* Check if you're still using MD5. If you are, regenerate it as PHP Default's password algorithm */
        if (self::userGetPassword($username) === self::userPasswordMD5($password)) {
            self::userGeneratePassword($username, $password);
        }
        $hash = self::userGetPassword($username);
        if (password_verify($password, $hash)) {
            // Login successful.
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $hash = self::userGeneratePassword($username, $password);
            }
            Users::userSessionStart($username);
        } else {
            Users::userSessionEnd();
            return gettext('wrongup');
        }
        return $hash;
    }
    public static function userGetPassword($username) {
        $sql = 'SELECT `password` from `members` WHERE `username` = :username;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $password = $stmt->fetchColumn();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        /** @noinspection PhpUndefinedVariableInspection */
        return $password;
    }
    public static function userPasswordMD5($password) {
        /* This method is only used for legacy accounts. It generally won't be used, as
           all accounts which are logged into or set up after December 2016 use the PHP
           password_hash() and password_verify() functions.  */
        return md5($password);
    }
    public static function userGeneratePassword($username, $password) {
        $hashandsalt = password_hash($password, PASSWORD_DEFAULT);
        /* Uses index */
        $sql = 'UPDATE `members` SET `password` = :hashandsalt WHERE `username` = :username;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':hashandsalt', $hashandsalt);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo gettext('error') . ' ' . $e->getMessage() . "\n";
        }
        return $hashandsalt;
    }
    public static function userSessionStart($username) {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_regenerate_id();
        /* Uses reference lookup */
        $sql = "SELECT * FROM `members` WHERE `username` = :username AND `active` = 'Yes';";
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch();
            $stmt->closeCursor();
            $_SESSION['user'] =
                array('id' => $user['id'], 'name' => $username, 'email' => $user['email'], 'active' => $user['active'],
                      'regtime' => $user['regtime'], 'totalgames' => $user['totalgames'], 'aim' => $user['aim'],
                      'facebook' => $user['facebook_id'], 'github' => $user['github_id'], 'msn' => $user['msn'],
                      'twitter' => $user['twitter_id'], 'yahoo' => $user['yahoo'], 'avatar' => $user['avatarurl'],
                      'admin' => $user['admin'], 'ip' => $user['ip'], 'birth_date' => $user['birth_date'],
                      'last_login' => $user['last_login']);
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    public static function userSessionEnd() {
        if (isset($_SESSION)) {
            $_SESSION = array();
            session_destroy();
            header('Location: index.php');
        }
    }
    private function __clone() {
    }
}