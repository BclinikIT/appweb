<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public function sendEmail(
        $recipient,
        $subject,
        $view,
        $data = [],
        $attachments = [],
        $fromEmail = 'noreply@bclinik.com',
        $fromName,
        $replyToEmail = 'noreply@bclinik.com',
        $replyToName
    ) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'bclinik.com';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@bclinik.com';
        $mail->Password = 'Password$001';
        $mail->setFrom($fromEmail, $fromName);
        $mail->addReplyTo($replyToEmail, $replyToName);
        $mail->addAddress($recipient);

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->MsgHTML(view($view, $data)->render());

        foreach ($attachments as $attachment) {
            if (is_array($attachment)) {
                $mail->addStringAttachment($attachment['content'], $attachment['name']);
            } else {
                $mail->addAttachment($attachment);
            }
        }

        if (!$mail->send()) {
            throw new \Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
        }
    }

}
