<?php

class RestResponseHandlerComponent extends CakeObject
{
  public $controller;
  public $settings;
  
  public function initialize($controller, $settings)
  {
    $this->controller = $controller;
    $this->settings = $settings;
  }
  
  public function withStatus($status) {
    return http_response_code($status);
  }
  
  public function withStringBody($body) {
    return json_encode(['data' => $body]);
  }
  
  
  public function toJson(string $message, int $status, array $body=[]): void
  {
    http_response_code($status);
    $statusText = null;
    
    switch ($status) {
      case 200: // OK: no additional information is provided
        $statusText = 'success';
        break;
      case 201: // Created: a new data item was created on the server
        $statusText = 'success';
        break;
      case 202: // Accepted: the server has received the request, but hasn't finished processing it
        $statusText = 'error';
        break;
      case 203: // Non-Authoritative Information: a proxy server sent the response based on a response from the endpoint server, but the response isn’t identical to the response the proxy received
      case 204: // No Content: the server processed the request and returned no content
        $statusText = 'ok';
        break;
      case 205: // Reset Content: like 204, but the requester should reset its data view
        $statusText = 'OK';
        break;
      case 206: // Partial Content: the content is a subset of the entire data set because the request asked for this
      case 207: // Multi-Status: the response body is an XML document containing additional status information
      case 208: // Already Reported: a follow-on related to 207
      case 300:
        $statusText = 'warning';
      break;
      case 400:
      case 401:
      case 402:
      case 403:
      case 404:
      case 500:
        $statusText = 'error';
        break;
      case 422:
        // Error 422 an HTTP code that tells you that the server can't process your request,
        // although it understands it. The full name of the error_code is 422 "Not Supported."
        header('Allow, GET, POST, PUT');
        $statusText = 'error';
        break; // exit;
      default:
        break;
    }
    echo json_encode([
      'statusText' => $statusText,
      'status' => (int) $status,
      'message' => $message,
      'data' => $body
    ]);
    exit;
  }
}
