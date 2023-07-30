<?php

namespace App\Http;

class Request {

    /**
     * Retrieve a GET parameter.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Retrieve a POST parameter.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Retrieve a SERVER parameter.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function server(string $key, mixed $default = null): mixed
    {
        return $_SERVER[$key] ?? $default;
    }

    /**
     * Retrieve a SESSION parameter.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSession(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a SESSION parameter.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setSession(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve the client's IP address.
     *
     * @return string|null
     */
    public function clientIp(): ?string
    {
        //checking possible keys, maybe request comes from proxy
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            $ip = $this->server($key);
            if ($ip !== null) {
                foreach (explode(',', $ip) as $ipPart){                    
                    $ipPart = trim($ipPart);
                    if (filter_var($ipPart, FILTER_VALIDATE_IP) !== false) {
                        return $ipPart;
                    }
                }
            }
        }
        
        return null;
    }
}
