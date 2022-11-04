<?php

namespace App\core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Auth
{
    /**
     * @param string $email
     * @param string $password
     * @return bool|array
     */
    public static function attemptLogin(string $email, string $password): bool|array
    {
        $user = Application::$app->builder
            ->select()
            ->from('users')
            ->where('email', $email)
            ->first();

        if (!$user)
            return false;

        if (!password_verify($password, $user['password']))
            return false;

        // Ritorno tutti campi dell'utente eccetto la password
        return array_diff_key($user, array_flip(['password']));
    }

    /**
     * @param string $password
     * @return string
     */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @return string 
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @param $user
     * @return void 
     */
    public static function sendVerificationMail($user): void
    {
        $queryString = "id={$user['id']}&token={$user['token']}";
        $link = "http://localhost:8888/verify?$queryString";

        $mail = new PHPMailer(true);

        try {
            // Server
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $mail::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->isHTML(true);

            // Recipients
            $mail->setFrom('mvcframework@gmail.com', 'mvcframework');
            $mail->addAddress($user['email'], $user['username']);
            $mail->addReplyTo('mvcframeworkinfo@gmail.com', 'Information');

            // Content
            $mail->Subject = 'Verifica account';
            $mail->Body = "
                <h3>Verifica il tuo account</h3>
                </br>
                <p>Clicca il seguente link per verificare il tuo account</p>
                </br>
                <a href=" . $link . ">Verifica account</a>
                ";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    /**
     * @param $user
     * @return bool
     */
    public static function isVerified($user): bool
    {
        return $user['verified'];
    }

    /**
     * @param $user
     * @return void
     */
    public static function sendResetPasswordMail($user): void
    {
        $queryString = "id={$user['id']}&token={$user['token']}";
        $link = "http://localhost:8888/password-reset?$queryString";

        $mail = new PHPMailer(true);

        try {
            // Server
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $mail::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->isHTML(true);

            // Recipients
            $mail->setFrom('mvcframework@gmail.com', 'mvcframework');
            $mail->addAddress($user['email'], $user['username']);
            $mail->addReplyTo('mvcframeworkinfo@gmail.com', 'Information');

            // Content
            $mail->Subject = 'Reset password';
            $mail->Body = "
                <h3>Clicca il seguente link per resettare la tua password</h3>
                </br>
                <a href=" . $link . ">Reset password</a>
                ";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
