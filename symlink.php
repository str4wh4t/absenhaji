<?php
    $target = __DIR__ . DIRECTORY_SEPARATOR . 'writable/node_modules';
    $link = __DIR__ . DIRECTORY_SEPARATOR . 'public/assets/node_modules';
    if (symlink( $target, $link )) {
        echo "SYMLINK - CREATED \n";
    } else {
        echo "SYMLINK - FAILED \n";
    }
?>