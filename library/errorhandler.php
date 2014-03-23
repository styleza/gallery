<?php
function mvc_errorhandler($errno,  $errstr,  $errfile,  $errline, $errcontext){
    //@todo get view and render it and so on...
    resources::getMvc($request)->run('error');
}
function mvc_exceptionhandler($e){
    $request = new mvc_request(array(
        'controller' => 'error',
        'action' => 'excetpion',
        'exception' => $e
    ));
    echo resources::getMvc($request)->run('exception');
}

//set_error_handler("mvc_errorhandler");
set_exception_handler("mvc_exceptionhandler");