<?php
/**
 * AppError base class for the error handler
 *
 * @uses ErrorHandler
 * @package App
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class AppError extends ErrorHandler
{

    /* public permissionDenied($params) {{{ */
    /**
     * permissionDenied handling permission deny error
     *
     * @param mixed $params parameters
     *
     * @access public
     * @return void
     */
    public function permissionDenied($params)
    {
        $this->_outputMessage('permission_denied');
    }
    /* }}} */
}
