<?php

class JsonResponseComponent extends CakeObject
{
    public $controller;
    public $settings;
    public $components = [];
    
    private $statuses = [
        '200' => ['status' => 200, 'statusText' => 'OK', 'type' => 'success'],
        '201' => ['status' => 201, 'statusText' => 'Created', 'type' => 'success'],
        '202' => ['status' => 202, 'statusText' => 'Accepted', 'type' => 'success'],
        '204' => ['status' => 204, 'statusText' => 'No Content', 'type' => 'success'],
        '400' => ['status' => 400, 'statusText' => 'Bad Request', 'type' => 'error'],
        '401' => ['status' => 401, 'statusText' => 'Unauthorized', 'type' => 'error'],
        '403' => ['status' => 403, 'statusText' => 'Forbidden', 'type' => 'error'],
        '404' => ['status' => 404, 'statusText' => 'Not Found', 'type' => 'error'],
        '405' => ['status' => 405, 'statusText' => 'Method Not Allowed', 'type' => 'error'],
        '500' => ['status' => 500, 'statusText' => 'Internal Server Error', 'type' => 'error'],
    ];
    private $response;
    private $status;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    public function __construct() {}
    public function __destruct()
    {
        if($this->response) {
            http_response_code($this->status);
            echo json_encode($this->response);
        }
    }
    
    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    
    /**
     * @return JsonResponseComponent
     */
    protected function setHeaders(): JsonResponseComponent
    {
        return $this;
    }
    
    /**
     * Set the content on the response.
     * @param array $data
     * @return $this
     */
    public function setContent(array $data): JsonResponseComponent
    {
        if(!isset($data)) return $this;
        $this->response['data'] = $data;
        return $this;
    }
    
    /**
     * @param string $message
     * @return $this
     */
    public function withMessage(string $message): JsonResponseComponent
    {
        if(!isset($message)) return $this;
        $this->response['message'] = $message;
        return $this;
    }
    
    /**
     * @param int $code
     * @return $this
     */
    public function withStatus(int $code): JsonResponseComponent
    {
        if(!isset($code)) return $this;
        $allowedStatuses = array_keys($this->statuses);
        if (!in_array($code, $allowedStatuses)) {
            $this->status = 200; // todo: find a better status code to use as default.
        } else {
            $this->status = $code;
        }
        
        foreach ($this->statuses[$this->status] as $key => $value) {
            $this->response[$key] = $value;
        }
        
        return $this;
    }
}
