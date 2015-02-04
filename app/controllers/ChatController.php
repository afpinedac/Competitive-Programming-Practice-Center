<?php
define('DBPATH', Config::get('database.connections.mysql.host'));
define('DBUSER', Config::get('database.connections.mysql.username'));
define('DBPASS', Config::get('database.connections.mysql.password'));
define('DBNAME', Config::get('database.connections.mysql.database'));

class ChatController extends LMSController {

    
    private $dblink;
    
    function __construct() {
        $this->dblink = mysqli_connect(DBPATH, DBUSER, DBPASS);        
        mysqli_select_db($this->dblink,DBNAME);
        mysqli_autocommit($this->dblink, true);
        session_start();
        $_SESSION['username'] = Auth::user()->id;
    }

    public function getChatheartbeat() {


        $this->init();
        $sql = "select * from lms_chat where (lms_chat.to = '" . mysqli_real_escape_string($this->dblink,$_SESSION['username']) . "' AND recd = 0) order by id ASC";
        $query = mysqli_query($this->dblink,$sql);
        $items = '';

        $chatBoxes = array();

        while ($chat = mysqli_fetch_array($query)) {

            if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
                $items = $_SESSION['chatHistory'][$chat['from']];
            }

            $chat['message'] = $this->sanitize($chat['message']);

            $items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;

            if (!isset($_SESSION['chatHistory'][$chat['from']])) {
                $_SESSION['chatHistory'][$chat['from']] = '';
            }

            $_SESSION['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;

            unset($_SESSION['tsChatBoxes'][$chat['from']]);
            $_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
        }

        if (!empty($_SESSION['openChatBoxes'])) {
            foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
                if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
                    $now = time() - strtotime($time);
                    $time = date('g:iA M dS', time());

                    $message = "Enviado a las $time";
                    if ($now > 180) {
                        $items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

                        if (!isset($_SESSION['chatHistory'][$chatbox])) {
                            $_SESSION['chatHistory'][$chatbox] = '';
                        }

                        $_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
                        $_SESSION['tsChatBoxes'][$chatbox] = 1;
                    }
                }
            }
        }

        $sql = "update lms_chat set recd = 1 where lms_chat.to = '" . mysqli_real_escape_string($this->dblink,$_SESSION['username']) . "' and recd = 0";
        $query = mysqli_query($this->dblink,$sql);

        if ($items != '') {
            $items = substr($items, 0, -1);
        }
        header('Content-type: application/json');
        ?>
        {
        "items": [
        <?php echo $items; ?>
        ]
        }

        <?php
        exit(0);
    }

    public function postSendchat() {
        $this->init();

        $from = $_SESSION['username'];
        $to = $_POST['to'];
        $message = $_POST['message'];

        $_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());

        $messagesan = $this->sanitize($message);

        if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
            $_SESSION['chatHistory'][$_POST['to']] = '';
        }

        $_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


        unset($_SESSION['tsChatBoxes'][$_POST['to']]);

        
        $sql = "insert into lms_chat (lms_chat.from,lms_chat.to,message,sent) values ('" . mysqli_real_escape_string($this->dblink,$from) . "', '" . mysqli_real_escape_string($this->dblink,$to) . "','" . mysqli_real_escape_string($this->dblink,$message) . "',NOW())";
        $query = mysqli_query($this->dblink,$sql);
        echo "1";
        exit(0);
    }

    function chatBoxSession($chatbox) {

        $items = '';

        if (isset($_SESSION['chatHistory'][$chatbox])) {
            $items = $_SESSION['chatHistory'][$chatbox];
        }

        return $items;
    }

    public function postClosechat() {
        $this->init();

        unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);

        echo "1";
        exit(0);
    }

    public function getStartchatsession() {
        $this->init();
        $items = '';
        if (!empty($_SESSION['openChatBoxes'])) {
            foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
                $items .= $this->chatBoxSession($chatbox);
            }
        }


        if ($items != '') {
            $items = substr($items, 0, -1);
        }

        header('Content-type: application/json');
        ?>
        {
        "username": "<?php echo $_SESSION['username']; ?>",
        "items": [
        <?php echo $items; ?>
        ]
        }

        <?php
        exit(0);
    }

    private function init() {
        if (!isset($_SESSION['chatHistory'])) {
            $_SESSION['chatHistory'] = array();
        }

        if (!isset($_SESSION['openChatBoxes'])) {
            $_SESSION['openChatBoxes'] = array();
        }
    }

    #funcion que limpia un string que se envia desde el chat

    function sanitize($text) {
        $text = htmlspecialchars($text, ENT_QUOTES);
        $text = str_replace("\n\r", "\n", $text);
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\n", "<br>", $text);
        return $text;
    }

}
?>