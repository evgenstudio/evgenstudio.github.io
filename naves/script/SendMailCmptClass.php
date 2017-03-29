<?php
/**
* SendMailSmtpClass
* 
* Класс для отправки писем через SMTP с авторизацией
* Может работать через SSL протокол
* Тестировалось на почтовых серверах yandex.ru, mail.ru и gmail.com
* 
* @author Ipatov Evgeniy <admin@ipatov-soft.ru>
* @version 1.0
*/
/**
желательно, что бы почтовый ящик был размещен на одном из почтовых серверов
yandex.ru или mail.ru или gmail.com
*/
//почтовый ящик, с которого будут отправляться сообщения
$smtp_username = 'info@naves-kovka.ru';

//пароль этого почтового ящика
$smtp_password = 'qwerty12345';

//сервер отправки почты
/*
gmail.com - ssl://smtp.gmail.com

yandex.ru - ssl://smtp.yandex.ru
mail.ru - ssl://smtp.mail.ru
list.ru - ssl://smtp.list.ru
bk.ru - ssl://smtp.bk.ru
inbox.ru - ssl://smtp.inbox.ru
*/
$smtp_host = 'ssl://smtp.yandex.ru';

//Ваше имя или название Вашего сайта или организации
//в сообщении, в поле "от кого" будет эта информация
$smtp_from = 'info@naves-kovka.ru';

//порт
$smtp_port = 465;

$mailSMTP = new SendMailSmtpClass($smtp_username, $smtp_password, $smtp_host, $smtp_from, $smtp_port);

class SendMailSmtpClass {

    /**
    * 
    * @var string $smtp_username - логин
    * @var string $smtp_password - пароль
    * @var string $smtp_host - хост
    * @var string $smtp_from - от кого
    * @var integer $smtp_port - порт
    * @var string $smtp_charset - кодировка
    *
    */   
    public $smtp_username;
    public $smtp_password;
    public $smtp_host;
    public $smtp_from;
    public $smtp_port;
    public $smtp_charset;

    public function __construct($smtp_username, $smtp_password, $smtp_host, $smtp_from, $smtp_port = 25, $smtp_charset = "utf-8") {
        $this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host = $smtp_host;
        $this->smtp_from = $smtp_from;
        $this->smtp_port = $smtp_port;
        $this->smtp_charset = $smtp_charset;
    }

    /**
    * Отправка письма
    * 
    * @param string $mailTo - получатель письма
    * @param string $subject - тема письма
    * @param string $message - тело письма
    * @param string $headers - заголовки письма
    *
    * @return bool|string В случаи отправки вернет true, иначе текст ошибки    *
    */
    function send($mailTo, $subject, $message, $headers) {
        $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
        $contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?'  . base64_encode($subject) . "=?=\r\n";
        $contentMail .= $headers . "\r\n";
        $contentMail .= $message . "\r\n";

        try {
            if(!$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30)){
                throw new Exception($errorNumber.".".$errorDescription);
            }
            if (!$this->_parseServer($socket, "220")){
                throw new Exception('Connection error');
            }

            $server_name = $_SERVER["SERVER_NAME"];
            fputs($socket, "HELO $server_name\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: HELO');
            }

            fputs($socket, "AUTH LOGIN\r\n");
            if (!$this->_parseServer($socket, "334")) {
                fclose($socket);
                throw new Exception('Autorization error');
            }



            fputs($socket, base64_encode($this->smtp_username) . "\r\n");
            if (!$this->_parseServer($socket, "334")) {
                fclose($socket);
                throw new Exception('Autorization error');
            }

            fputs($socket, base64_encode($this->smtp_password) . "\r\n");
            if (!$this->_parseServer($socket, "235")) {
                fclose($socket);
                throw new Exception('Autorization error');
            }

            fputs($socket, "MAIL FROM: <".$this->smtp_username.">\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: MAIL FROM');
            }

            $mailTo = ltrim($mailTo, '<');
            $mailTo = rtrim($mailTo, '>');
            fputs($socket, "RCPT TO: <" . $mailTo . ">\r\n");     
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception('Error of command sending: RCPT TO');
            }

            fputs($socket, "DATA\r\n");     
            if (!$this->_parseServer($socket, "354")) {
                fclose($socket);
                throw new Exception('Error of command sending: DATA');
            }

            fputs($socket, $contentMail."\r\n.\r\n");
            if (!$this->_parseServer($socket, "250")) {
                fclose($socket);
                throw new Exception("E-mail didn't sent");
            }

            fputs($socket, "QUIT\r\n");
            fclose($socket);
        } catch (Exception $e) {
            return  $e->getMessage();
        }
        return true;
    }

    private function _parseServer($socket, $response) {
        while (@substr($responseServer, 3, 1) != ' ') {
            if (!($responseServer = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($responseServer, 0, 3) == $response)) {
            return false;
        }
        return true;

    }
}
   $to = 'info@naves-kovka.ru'; 
   $subject = 'Заявка'; 
   $message = "Имя: ".$data['name']."; Телефон: ".$data['phone']."\nНавес ".$_POST['hight']."x".$_POST['weight'].", ".$_price."р."."\nВид: ".$_kind."\nУдалённость от МКАД: ".$_range."\nВремя установки: ".$_term;
   $headers = "Content-type: text/plain; charset=utf-8 \r\n";
   $result = $mailSMTP->send($to, $subject, $message, $headers);

	if(!empty($data['email'])) {
		$to = $data['email']; 
		$subject = 'Ваша скидка и расчёт цены навеса'; 
		$message = "Здравствуйте! Вы оставили заявку на расчет стоимости навеса на нашем сайте и получили скидку!\nВ данном письме мы предлагаем Вам примерную стоимость стандартной конструкции навеса и Вашу индивидуальную скидку.\nПримерная стоимость Вашего заказа: ".$_price."р.\nМы изготавливаем конструкцию на производстве, а затем производим монтаж готового изделия.\nСрок выполнения заказа от 7 до 12 дней.\nГрафик установки согласовывается индивидуально.\nКод Вашей индивидуальной скидки: GH5TY7S. \nВы можете заказать консультацию инженера, оставив заявку на сайте naves-kovka.ru \nв форме «ЗАКАЗАТЬ ЗВОНОК» в верхней части сайта, справа под контактным номером, а так же по \nуказанным ниже телефонам:\n8 (926) 333 9776\n8 (499) 397 7778\nС уважением, руководитель отдела продаж \nБарышников Евгений Владимирович.";
		$headers = "Content-type: text/plain; charset=utf-8 \r\n";
		$result = $mailSMTP->send($to, $subject, $message, $headers);
	}
?>