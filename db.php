<?php
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.herokuapp.com')
        $connection = new mysqli('sql11.freemysqlhosting.net', 'sql11511502', '9PXMcq2Mnj', 'sql11511502');

    else
        $connection = new mysqli('127.0.0.1', 'Ilya Bashlyaev', '#vOV(0y2#vOV(0y2', 'restaurant');

?>