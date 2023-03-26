<?php

declare(strict_types=1);

namespace Payme;

use Payme\Controllers\FileController;

require_once __DIR__.'/autoloader.php';

$localFile = require __DIR__.'/config/file.php';

// TODO: Implement an API to handle POST (upload files) and GET (read a file)

// At present, we are only able to read files, so we use FileController
$fileController = new FileController($localFile);
$fileController->generatePayroll();




