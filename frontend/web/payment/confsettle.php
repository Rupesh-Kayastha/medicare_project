<?php

$req_dump = print_r($_REQUEST, TRUE);
$fp = fopen('log/setl.txt', 'a+');
fwrite($fp, $req_dump);
fclose($fp);


?>