@echo off
cd /d D:\project\eSewaan
set "PHP=C:\Users\User\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
if not exist "%PHP%" (
  echo PHP 8.4 tidak dijumpai:
  echo %PHP%
  pause
  exit /b 1
)
echo.
echo SPEKTRA: http://10.103.103.17:8080
echo Jangan tutup tetingkap ini.
echo.
"%PHP%" -v
echo.
"%PHP%" -d upload_max_filesize=40M -d post_max_size=48M artisan serve --host=0.0.0.0 --port=8080
echo.
echo Pelayan berhenti.
pause
