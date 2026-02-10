<?php
file_put_contents(__DIR__ . "/test_post_log.txt", print_r($_POST, true));
echo "OK";
