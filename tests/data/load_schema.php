<?php

function getPDO()
{
    $db = new PDO('sqlite::memory:');
    $fh = fopen(__DIR__ . '/schema.sql', 'r');
    while ($line = fread($fh, 4096)) {
        $db->exec($line);
    }
    fclose($fh);
    return $db;
}