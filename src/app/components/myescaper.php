<?php

namespace App\Components;

use Phalcon\Escaper;

class myescaper
{
    public function sanitize($data)
    {
        $escaper = new Escaper();
        $data = array(
            "full_name" => $escaper->escapeHtml($data['full_name']),
            "email" => $escaper->escapeHtml($data['email']),
            "username" => $escaper->escapeHtml($data['username']),
            "password" => $escaper->escapeHtml($data['password']),
            "confirm_password" => $escaper->escapeHtml($data['confirm_password']),
        );
        return $data;
    }
}