<?php
declare(strict_types=1);
Core::stopDirectAccess();

class Users
{
    protected $params;
    protected $status;
    protected $user;
    private function __construct()
    {
    }
    public static function UpdateProfile()
    {
        /* Sanitization */
        $aim = filter_var($_POST['aim'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $github = filter_var($_POST['github_id'], FILTER_SANITIZE_STRING);
        $facebook = filter_var($_POST['facebook_id'], FILTER_SANITIZE_STRING);
        $msn = filter_var($_POST['msn'], FILTER_SANITIZE_STRING);
        $twitter = filter_var($_POST['twitter_id'], FILTER_SANITIZE_STRING);
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $stmt =
                mySQL::getConnection()->prepare('CALL sp_Members_UpdateMemberProfile(:aim, :email, :github, :facebook, :msn, :twitter, :id);');
            $stmt->bindParam(':aim', $aim);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':github', $github);
            $stmt->bindParam(':facebook', $facebook);
            $stmt->bindParam(':msn', $msn);
            $stmt->bindParam(':twitter', $twitter);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            $params[2] = 'wompwomp';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                Core::showSuccess(gettext('emailvalid'));
            } else {
                Core::showError(gettext('emailinvalid'));
            }
            Core::showError($e->getMessage());
        }
    }
    public static function userPasswordUpdateByID($id, $password)
    {
        $password = Users::userPasswordHash($password);
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_UpdateMemberPassword(:password, :memberid);');
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':memberid', $id);
        $stmt->execute();
        Core::showSuccess(gettext('updatesuccess'));
    }
    public static function userPasswordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public static function getUserbyID($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetMemberbyID(:memberid);');
        $stmt->bindParam(':memberid', $id);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            return false;
        }
        return $stmt->fetch();
    }
    public static function getUsersAll()
    {
        /* Only used by admin on the get users page */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetAllMembers();');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getUsersCount()
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetAllIDs();');
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function isUserLoggedIn()
    {
        return isset($_SESSION['user']) ? true : false;
    }
    public static function passwordRecoveryForm()
    {
        $dbconfig = Core::getInstance()->getDBConfig();
        if ($dbconfig['passwordrecovery'] === 'on') {
            ?>
        <form action='<?php echo SITE_URL; ?>' method='post'><br/>
            <?php echo gettext('username'); ?>:<br/>
            <label>
                <input name='username' id="username"/>
            </label><br/><br/>
            <?php echo gettext('email'); ?><br/>
            <label>
                <input name='email' id="email"/><br/>
            </label><br/><br/>
            <input type='submit' value='<?php echo gettext('submit'); ?>' alt='submit'/>
            <input type='hidden' name='params' value='login/recover/do' alt='recover'/>
            </form><?php
        }
    }
    public static function passwordRecovery()
    {
        $dbconfig = Core::getInstance()->getDBConfig();
        $inicfg = Core::getInstance()->getINIConfig();
        $status = '';
        if ($dbconfig['passwordrecovery'] === 'on') {
            /** @noinspection PhpUndefinedVariableInspection */
            $username = $args['username'] ?? trim($_POST['username']);
            $email = $args['email'] ?? $_POST['email'];
            $password = self::passwordGenerate();
            $clearpass = $password;
            $password = self::userPasswordHash($password);
            /* Only sends email if the user actually exists */
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetUsernameAndEmail(:username, :useremail);');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':useremail', $email);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 1) {
                $mail = new PHPMailer();
                $body = file_get_contents(INST_DIR . 'includes/messages/forgottenmessage.txt');
                $body = nl2br(str_replace('%username%', $username, $body));
                $body = nl2br(str_replace('%password%', $clearpass, $body));
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
                /* This allows the next stored procedure in userPasswordUpdatebyEmail() to run simultaneously */
                $stmt->nextRowset();
                /* Do the actual update of the password in the database */
                self::userPasswordUpdatebyEmail($password, $username, $email);
                if (!$mail->send()) {
                    ?>
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
    public static function passwordGenerate($pw = "", $length = 8, $i = 0)
    {
        $possible = '!@#$%^&*0123456789bcdfghjkmnpqrstvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        while ($i < $length) {
            $char = $possible[random_int(0, mb_strlen($possible) - 1)];
            if (false === mb_strpos($pw, $char)) {
                $pw .= $char;
                ++$i;
            }
        }
        return $pw;
    }
    public static function userPasswordUpdatebyEmail($password, $username, $email)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Members_UpdatePasswordbyUserEmail(:password, :username, :useremail);');
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':useremail', $email);
        $stmt->execute();
    }
    public static function updateUserPlaycount()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        /* Null checker to prevent extra log entries when user isn't logged in */
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        if ($user !== null) {
            /* Uses index */
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_UpdatePlaycount(:userid);');
            $stmt->bindParam(':userid', $user['id']);
            $stmt->execute();
        }
    }
    public static function uploadAvatar($id, $path, $filename)
    {
        /* Comes from the Profile page.  Take the ID in so you can do database work.
          Then concat the path and filename from profile.php and upload. */
        $avatarpath = $path . $filename;
        /* Needs to be 10MB and either png, jpg, or gif MIME Type */
        $validator = new FileUpload\Validator\Simple(1024 * 1024 * 10, ['image/png']);
        /* Upload path */
        $pathresolver = new FileUpload\PathResolver\Simple($path);
        /* The machine's filesystem */
        $filesystem = new FileUpload\FileSystem\Simple();
        /* File Uploader itself */
        $fileupload = new FileUpload\FileUpload($_FILES['uploadavatar'], $_SERVER);
        $filenamegenerator = new FileUpload\FileNameGenerator\Custom($filename);
        /* Adding it all together.  Note: can always add multiple validators, or use none */
        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->setFileNameGenerator($filenamegenerator);
        $fileupload->addValidator($validator);
        /* Uploading */
        /** @noinspection PhpUnusedLocalVariableInspection */
        list($files, $headers) = $fileupload->processAll();
        /* Now update the database */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_UpdateAvatar(:id, :avatarurl);');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':avatarurl', $avatarpath);
        $stmt->execute();
        return;
    }
    public static function userAdd($username, $email, $status = "")
    {
        $dbconfig = Core::getInstance()->getDBConfig();
        $inicfg = Core::getInstance()->getINIConfig();
        if (!empty($username) || !empty($email)) {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetUsernameOREmail(:username, :useremail);');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':useremail', $email);
            $stmt->execute();
            $rowcount = $stmt->rowCount();
            if ($rowcount > 0) {
                $status = 'usertaken';
            } else {
                if ($rowcount == 0) {
                    $password = self::passwordGenerate();
                    $clearpass = $password;
                    $password = self::userPasswordHash($password);
                    $mail = new PHPMailer();
                    $body = file_get_contents(INST_DIR . 'includes/messages/registering.txt');
                    $body = nl2br(str_replace('%siteurl%', SITE_URL, $body));
                    $body = nl2br(str_replace('%username%', $username, $body));
                    $body = nl2br(str_replace('%password%', $clearpass, $body));
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
                        $stmt->nextRowset();
                        try {
                            $stmt =
                                mySQL::getConnection()->prepare('CALL sp_Members_AddMember(:memberid, :memberusername, :memberpassword, :memberemail, :memberactive, :memberadmin, :memberip);');
                            $stmt->bindParam(':memberid', $null);
                            $stmt->bindParam(':memberusername', $username);
                            $stmt->bindParam(':memberpassword', $password);
                            $stmt->bindParam(':memberemail', $email);
                            $stmt->bindParam(':memberactive', $yes);
                            $stmt->bindParam(':memberadmin', $no);
                            $stmt->bindParam(':memberip', $_SERVER['REMOTE_ADDR']);
                            $stmt->execute();
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
    public static function userDelete($id, $admin = 'No')
    {
        /* Delete users, unless they're an admin.  Don't delete admins. Bad idea. */
        try {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_DeleteMember(:memberid, :admin);');
            $stmt->bindParam(':memberid', $id);
            $stmt->bindParam(':admin', $admin);
            $stmt->execute();
            Core::showSuccess(gettext('deletesuccess'));
        } catch (PDOException $e) {
            Core::showWarning(gettext('deleteadminfailure'));
            Core::showError($e->getMessage());
        }
    }
    public static function userEdit($id)
    {
        /* Used in admin to edit users. Be careful of the "isadmin" when using it elsewhere */
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Members_EditMember_Admin(:username, :email, :active, :twitter, :aim, :msn, :isadmin, :memberid);');
        $stmt->bindParam(':memberid', $id);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':active', $_POST['active']);
        $stmt->bindParam(':twitter', $_POST['twitter_id']);
        $stmt->bindParam(':aim', $_POST['aim']);
        $stmt->bindParam(':msn', $_POST['msn']);
        $stmt->bindParam(':isadmin', $_POST['isadmin']);
        $stmt->execute();
        Core::showSuccess(gettext('updatesuccess'));
    }
    public static function userVerifyPassword($username, $password)
    {
        /* Check if you're still using MD5. If you are, regenerate it as PHP Default's password algorithm */
        if (self::userGetPassword($username) === self::userPasswordMD5($password)) {
            self::userGeneratePassword($username, $password);
        }
        $hash = self::userGetPassword($username);
        if (password_verify($password, self::userGetPassword($username))) {
            // Login successful.
            if (password_needs_rehash(self::userGetPassword($username), PASSWORD_DEFAULT)) {
                $hash = self::userGeneratePassword($username, $password);
            }
            Users::userSessionStart($username);
        } else {
            Users::userSessionEnd();
            return gettext('wrongup');
        }
        return $hash;
    }
    public static function userGetPassword($username)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetPassword(:username);');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public static function userPasswordMD5($password)
    {
        /* This method is only used for legacy accounts. It generally won't be used, as
           all accounts which are logged into or set up after December 2016 use the PHP
           password_hash() and password_verify() functions.  The initial admin password
           also requires this function, as the hash is predictable. */
        return md5($password);
    }
    public static function userGeneratePassword($username, $password)
    {
        $hashandsalt = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GeneratePassword(:username, :hashandsalt);');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hashandsalt', $hashandsalt);
        $stmt->execute();
        return $hashandsalt;
    }
    public static function userSessionStart($username)
    {
        /* Sanitization */
        $username = filter_var($username, FILTER_SANITIZE_STRING);

        if (!isset($_SESSION)) {
            session_start();
        }
        session_regenerate_id();
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetSession(:username);');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        $_SESSION['user'] =
            array('id' => $user['id'], 'name' => $username, 'email' => $user['email'], 'active' => $user['active'],
                  'regtime' => $user['regtime'], 'totalgames' => $user['totalgames'], 'aim' => $user['aim'],
                  'facebook' => $user['facebook_id'], 'github' => $user['github_id'], 'msn' => $user['msn'],
                  'twitter' => $user['twitter_id'], 'avatar' => $user['avatarurl'], 'admin' => $user['admin'],
                  'ip' => $user['ip'], 'birth_date' => $user['birth_date'], 'last_login' => $user['last_login']);
    }
    public static function userSessionEnd()
    {
        /* Resume sesion, then destroy it */
        if (!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION['user']);
        if (isset($_SESSION['user'])) {
            session_destroy();
        }
        header('Location: index.php');
    }
    private function __clone()
    {
    }
}
