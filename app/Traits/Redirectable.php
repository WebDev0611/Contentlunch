<?php

namespace App\Traits;


trait Redirectable
{
    public function success($route, $message)
    {
        return $this->redirectWithFeedback($route, $message, 'success');
    }

    public function danger($route, $message)
    {
        return $this->redirectWithFeedback($route, $message, 'danger');
    }

    protected function redirectWithFeedback($route, $message, $messageType)
    {
        if (gettype($route) == 'array') {
            list($route, $param) = $route;
            $redirect = redirect()->route($route, $param);
        } else {
            $redirect = redirect()->route($route);
        }
        return $redirect->with([
            'flash_message' => $message,
            'flash_message_type' => $messageType,
        ]);
    }
}