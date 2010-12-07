<?php

class AppError extends ErrorHandler {

  function permissionDenied($params) {
    $this->_outputMessage('permission_denied');
  }
} 
