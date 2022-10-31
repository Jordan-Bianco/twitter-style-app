<?php

namespace App\core;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function destroySession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setFlashMessage(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return void
     */
    public function getFlashMessage(string $key): void
    {
        echo $_SESSION[$key];

        $this->remove($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param array $value
     * @return void
     */
    public function setValidationErrors(array $value): void
    {
        $_SESSION['validationErrors'] = $value;
    }

    /**
     * @param null|string $key
     */
    public function getValidationErrors(?string $key = null)
    {
        if ($key) {
            echo $_SESSION['validationErrors'][$key] ?? '';

            unset($_SESSION['validationErrors'][$key]);

            return;
        }

        return $_SESSION['validationErrors'];
    }

    /**
     * @return void
     */
    public function removeValidationErrors(): void
    {
        unset($_SESSION['validationErrors']);
    }

    /**
     * @param array $value
     * @return void
     */
    public function setOldData(array $value): void
    {
        $sensitive = [
            'password',
            'password_confirm'
        ];

        $value = array_diff_key($value, array_flip($sensitive));

        $_SESSION['oldData'] = $value;
    }

    public function getOldData(string $key)
    {
        echo isset($_SESSION['oldData'][$key]) ? $_SESSION['oldData'][$key] : '';

        unset($_SESSION['oldData'][$key]);
    }

    /**
     * @return bool 
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }
}
