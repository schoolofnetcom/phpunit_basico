@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/vendor/phpunit/phpunit/phpunit
php7 "%BIN_TARGET%" %*
