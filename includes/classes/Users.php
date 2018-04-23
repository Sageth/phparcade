<?php
declare(strict_types=1);
namespace PHPArcade;

use PDOException;

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
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $github = filter_var($_POST['github_id'], FILTER_SANITIZE_STRING);
        $facebook = filter_var($_POST['facebook_id'], FILTER_SANITIZE_STRING);
        $twitter = filter_var($_POST['twitter_id'], FILTER_SANITIZE_STRING);
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $stmt =
                mySQL::getConnection()->prepare('CALL sp_Members_UpdateMemberProfile(:email, :github, :facebook, :twitter, :id);');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':github', $github);
            $stmt->bindParam(':facebook', $facebook);
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
        $dbconfig = Core::getDBConfig();
        if ($dbconfig['passwordrecovery'] === 'on')
        { ?>
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
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_GetUsernameAndEmail(:username, :useremail);');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':useremail', $email);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 1) {
                /** @noinspection PhpUndefinedClassInspection */
                /** @noinspection PhpUndefinedNamespaceInspection */
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                try {
                    $body = file_get_contents(INST_DIR . 'includes/messages/forgottenmessage.txt');
                    $body = nl2br(str_replace('%username%', $username, $body));
                    $body = nl2br(str_replace('%password%', $clearpass, $body));
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mail->isSMTP(); // telling the class to use SMTP
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->SMTPDebug = $dbconfig['emaildebug']; //Ask for HTML-friendly debug output
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->Debugoutput = 'html';
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->SMTPAuth = true;                    // enable SMTP authentication
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->SMTPSecure = 'tls';                    // sets the prefix to the server
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->Host = $dbconfig['emailhost']; //SMTP over IPv6
                    //$mail->Host = gethostbyname($dbconfig["emailhost"]); //SMTP over IPv4
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->Port = $dbconfig['emailport'];
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->Username = $dbconfig['emailfrom'];
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->Password = $inicfg['mail']['gmailpassword'];
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mail->setFrom($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mail->addReplyTo($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->Subject = 'Password Reset Request';
                    /** @noinspection PhpUndefinedFieldInspection */
                    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mail->msgHTML($body);
                    $address = $email;
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mail->addAddress($address, $dbconfig['emaildomain']);
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mail->send();
                    /* This allows the next stored procedure in userPasswordUpdatebyEmail() to run simultaneously */
                    $stmt->nextRowset();
                    /* Do the actual update of the password in the database */
                    self::userPasswordUpdatebyEmail($password, $username, $email);
                } catch (PDOException $e) {
                    $status = 'emailfail'; ?>
                    <p class="bg-danger">
                        <?php echo gettext('emailfail');
                    if ($dbconfig['emaildebug'] > 0) {
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->ErrorInfo;
                    } ?>
                    </p><?php
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
    public static function passwordGenerate()
    {
        return bin2hex(random_bytes(5));
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
    public static function userAdd($username, $email, $status = "")
    {
        $dbconfig = Core::getDBConfig();
        $inicfg = Core::getINIConfig();
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
                    try {
                        /** @noinspection PhpUndefinedClassInspection */
                        /** @noinspection PhpUndefinedNamespaceInspection */
                        $mail = new PHPMailer\PHPMailer\PHPMailer();
                        $password = self::passwordGenerate();
                        $clearpass = $password;
                        $password = self::userPasswordHash($password);
                        $body = file_get_contents(INST_DIR . 'includes/messages/registering.txt');
                        $body = nl2br(str_replace('%siteurl%', SITE_URL, $body));
                        $body = nl2br(str_replace('%username%', $username, $body));
                        $body = nl2br(str_replace('%password%', $clearpass, $body));
                        /** @noinspection PhpUndefinedMethodInspection */
                        $mail->isSMTP(); // telling the class to use SMTP
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->SMTPDebug = $dbconfig['emaildebug'];
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->SMTPAuth = true;                    // enable SMTP authentication
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->SMTPSecure = 'tls';                    // sets the prefix to the server
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->Host = $dbconfig['emailhost'];
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->Port = $dbconfig['emailport'];
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->Username = $dbconfig['emailfrom'];
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->Password = $inicfg['mail']['gmailpassword'];
                        /** @noinspection PhpUndefinedMethodInspection */
                        $mail->setFrom($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                        /** @noinspection PhpUndefinedMethodInspection */
                        $mail->addReplyTo($dbconfig['emailfrom'], $dbconfig['emaildomain']);
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->Subject = 'Account Creation';
                        /** @noinspection PhpUndefinedFieldInspection */
                        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
                        /** @noinspection PhpUndefinedMethodInspection */
                        $mail->msgHTML($body);
                        $address = $email;
                        /** @noinspection PhpUndefinedMethodInspection */
                        $mail->addAddress($address, $dbconfig['emaildomain']);
                        /** @noinspection PhpUndefinedMethodInspection */
                        $mail->send();
                        $status = 'confemail';
                    } catch (PDOException $e) {
                        $status = 'emailfail'; ?>
                        <p class="bg-danger">
                            <?php echo gettext('emailfail');
                        if ($dbconfig['emaildebug'] > 0) {
                            /** @noinspection PhpUndefinedFieldInspection */
                            /** @noinspection PhpUndefinedVariableInspection */
                            $mail->ErrorInfo;
                        } ?>
                        </p><?php
                    }
                    $stmt->nextRowset();
                    try {
                        $null = null;
                        $yes = 'Yes';
                        $no = 'No';
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
            \PHPArcade\Core::showSuccess(gettext('deletesuccess'));
        } catch (PDOException $e) {
            \PHPArcade\Core::showWarning(gettext('deleteadminfailure'));
            \PHPArcade\Core::showError($e->getMessage());
        }
    }
    public static function userEdit($id)
    {
        /* Used in admin to edit users. Be careful of the "isadmin" when using it elsewhere */
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Members_EditMember_Admin(:username, :email, :active, :twitter, :isadmin, :memberid);');
        $stmt->bindParam(':memberid', $id);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':active', $_POST['active']);
        $stmt->bindParam(':twitter', $_POST['twitter_id']);
        $stmt->bindParam(':isadmin', $_POST['admin']);
        $stmt->execute();
        \PHPArcade\Core::showSuccess(gettext('updatesuccess'));
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
    public static function userGetGravatar($username, $size=80, $default='retro', $rating='pg')
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Members_getUserEmail(:username);');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $email = $stmt->fetchColumn();
        return GRAVATAR_URL . md5(strtolower(trim($email))) . "?default=" . $default . "&size=" . $size . "&rating=" . $rating;
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
                  'regtime' => $user['regtime'], 'totalgames' => $user['totalgames'], 'facebook' => $user['facebook_id'],
                  'github' => $user['github_id'], 'twitter' => $user['twitter_id'],
                  'admin' => $user['admin'], 'ip' => $user['ip'], 'birth_date' => $user['birth_date'],
                  'last_login' => $user['last_login']);
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
    public static function userUpdatePlaycount()
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
    public static function userUpdateLastLogin()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        /* Null checker to prevent extra log entries when user isn't logged in */
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        if ($user !== null) {
            $stmt = mySQL::getConnection()->prepare('CALL sp_Members_UpdateLastLogin(:userid);');
            $stmt->bindParam(':userid', $user['id']);
            $stmt->execute();
        }
    }
    private function __clone()
    {
    }
}
