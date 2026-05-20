<?php
define('PHPUNIT_RUNNING', true);

define('REAL_STDOUT', fopen('php://stdout', 'w'));

// A simple mock for php:// streams
class MockPhpStream {
<<<<<<< HEAD
    public $context;
=======
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
    protected $position;
    public static $input = '';
    protected $path = '';

    public function stream_open($path, $mode, $options, &$opened_path) {
        $this->path = $path;
        $this->position = 0;
        return true;
    }

    public function stream_read($count) {
        if ($this->path !== 'php://input') {
            return '';
        }
        $ret = substr(self::$input, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    public function stream_write($data) {
        fwrite(REAL_STDOUT, $data);
        return strlen($data);
    }

    public function stream_eof() {
        return $this->position >= strlen(self::$input);
    }

    public function stream_stat() {
        return [];
    }
    
    public function stream_set_option($option, $arg1, $arg2) {
        return false;
    }
}

stream_wrapper_unregister("php");
stream_wrapper_register("php", "MockPhpStream");
