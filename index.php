<?php error_reporting( E_ALL );?>
<?php ini_set( 'display_errors', 1 );?>

<?php

require_once "./vendor/autoload.php";

use app\kernel\Kernel;

Kernel::getInstance();