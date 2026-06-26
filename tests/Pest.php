<?php
pest()->extend(Tests\TempestTestCase::class)->in(__DIR__ . '/Integration/');

pest()->beforeAll(function () {
    $sqliteFile = __DIR__ . '/testing.sqlite';
    if (file_exists($sqliteFile)) {
        unlink($sqliteFile);
    }
});
