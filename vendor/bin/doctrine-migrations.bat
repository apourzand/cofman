@ECHO OFF
SET BIN_TARGET=%~dp0/../doctrine/migrations/bin/doctrine-migrations
php "%BIN_TARGET%" %*
