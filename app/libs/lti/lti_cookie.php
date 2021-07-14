<?php
namespace lti;

use IMSGlobal\LTI\Cookie;

class LTICookie extends Cookie {

    public function set_cookie($name, $value, $exp = 3600, $options = []) {
        $cookie_options = [
            'expires' => time() + $exp
        ];

        // SameSite none and secure will be required for tools to work inside iframes
        $same_site_options = [
            'samesite' => 'None',
            'secure' => true
        ];

        if (PHP_VERSION_ID < 70300) {
            $options += [
                'path' => "/",
                'domain' => "",
                'secure' => false,
                'httponly' => false
            ];
            extract(array_merge($cookie_options, $options)); // => $expires, $path, $domain, $secure, $httponly
            @setcookie("LEGACY_" . $name, $value, $expires, $path, $domain, $secure, $httponly);
            return $this;
        }

        @setcookie($name, $value, array_merge($cookie_options, $same_site_options, $options));

        // Set a second fallback cookie in the event that "SameSite" is not supported
        @setcookie("LEGACY_" . $name, $value, array_merge($cookie_options, $options));
        return $this;
    }
}