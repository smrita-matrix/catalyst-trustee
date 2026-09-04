@echo off
REM Starts the site for local work with the upload limits the dashboard needs.
REM
REM "php artisan serve" otherwise uses whichever php.ini the PHP on your PATH
REM happens to have, and a stock one caps uploads at 2M - too small for the
REM notice PDFs and banners. Passing the limits here means it works whatever
REM PHP is installed.
REM
REM Usage:  serve.bat          (starts on http://127.0.0.1:8001)
REM         serve.bat 8080     (starts on another port)

set PORT=%1
if "%PORT%"=="" set PORT=8001

php -d upload_max_filesize=64M ^
    -d post_max_size=72M ^
    -d memory_limit=256M ^
    -d max_file_uploads=50 ^
    -d max_execution_time=300 ^
    -d max_input_time=300 ^
    artisan serve --port=%PORT%
