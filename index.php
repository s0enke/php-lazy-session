<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('session.cookie_lifetime', 10);
register_shutdown_function(array('MySession', 'shutdown_handler'));
MySession::my_session_start();

if (isset($_GET['clear'])) {
    $_SESSION = array();
}


#shutdown_handler();
#echo 'Session ID: ' . session_id() . '<br>';
#var_dump($_SESSION);
#echo '<br>';

#var_dump($_COOKIE);
#var_dump(ini_get('session.use_cookies'));

class MySession {

    protected static $sessionStarted = false;

    public static function my_session_start()
    {
        if (isset($_COOKIE[ini_get('session.name')])) {
            if (!self::$sessionStarted) {
                session_start();
                #echo 'Session cookie found, started session<br>';
                self::$sessionStarted = true;
                return true;
            }
        } else {
            $_SESSION['foo'] = date('H:i:s');
            return false;
        }
    }

    public static function shutdown_handler()
    {
        if (is_array($_SESSION) && count($_SESSION) > 0) {
            if (!self::$sessionStarted) {
                // the _SESSION superglobal gets overwritten by session_start
                // so we save the contents in a temp var
                $tmp = $_SESSION;
                session_start();
                $_SESSION = $tmp;
                self::$sessionStarted = true;
                echo "starting new session ...<br>";
            } else {
                echo "starting existing session ...<br>";
            }
            session_commit();
            echo '$_SESSION not empty, commit session (' . session_id() . ')<br>';
            var_dump($_SESSION);
        } else {
            self::clearSession();
        }
    }

    public static function clearSession()
    {
        if (self::$sessionStarted) {
            setcookie(ini_get('session.name'), '', 1, ini_get('session.cookie_path'));
            session_destroy();
            echo "no session data, but session started, deleting session cookie<br>";
        }
    }
    

}
