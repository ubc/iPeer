<?php


class NotificationHandlerComponent extends CakeObject
{
  public $controller;
  public $settings;
  
  public function initialize($controller, $settings)
  {
    $this->controller = $controller;
    $this->settings = $settings;
  }
  
  public function toJson(string $message, int $code): void
  {
    header('Content-Type: application/json');
    http_response_code($code);
    $status = 'info';
    switch ($code) {
      case 200:
      case 201:
      case 203:
      case 204:
      case 205:
        $status = 'success';
        break;
      case 202:
      case 206:
      case 300:
        $status = 'warning';
        break;
      case 400:
      case 401:
      case 402:
      case 403:
      case 404:
        $status = 'error';
        break;
      case 422:
        // Error 422
        // is an HTTP code that tells you that the server can't process your request,
        // although it understands it. The full name of the error_code is 422 "Not Supported."
        header('Allow, GET, POST, PUT');
        exit;
        break;
      default:
        break;
    }
    echo json_encode(['status' => $status, 'code' => $code, 'message' => $message]);
    exit;
  }
  
}