<?php

/**
 * Description of Email
 *
 * @author Petr
 */
class Email {
    
    public function SendEmail($from, $to, $subject, $message) {

        $header = 'From:' . $from;
        $header .= "\nMIME-Version: 1.0\n";
        $header .= "Content-Type: text/html; charset=\"utf-8\"\n";
        return mb_send_mail($to, $subject, $message, $header);
    }
}
