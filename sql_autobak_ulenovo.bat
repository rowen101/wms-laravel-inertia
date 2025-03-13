@ECHO OFF
TITLE Backup MS SQL Database

REM Default values
set svrName=DESKTOP-OTDKLOP\SQLEXPRESS
set dbName=WMS
set folderPath=D:\safexpress\wms-laravel-inertia\DB\
set password=@Gonzales101

goto checkserver

:inputserver
set INPUT=
set /P svrName=Server Name  : %=%

:checkserver
if "%svrName%"=="" goto inputserver
goto checkdatabase

:inputdatabase
set INPUT=
set /P dbName=Database Name: %=%

:checkdatabase
if "%dbName%"=="" goto inputdatabase

echo.
echo ================================================
REM Get current date and time
For /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set mydate=%%c%%a%%b)
for /f "tokens=1-4 delims=:." %%a in ("%Time%") do set numbertime=%%a%%b%%c%
set fname=%dbName%_backup_%mydate%%numbertime%.bak
echo Backing up [%dbName%] on [%svrName%]
echo.
echo.
REM Start backup command
SqlCmd -E -S %svrName% -Q "BACKUP DATABASE %dbName% TO Disk='%folderPath%%fname%'"
echo.
echo ================================================
echo.

REM Compress the backup file and delete the original .bak file
if exist "%folderPath%%fname%" (
    "C:\Program Files\7-Zip\7z.exe" a -p%password% "%folderPath%%fname%.zip" "%folderPath%%fname%" && del "%folderPath%%fname%"
)

echo Backup and compression completed.
exit /B

REM The following section is a loop to automatically compress any new .bak files
:loop
for /f "delims=" %%i in ('dir "%folderPath%*.bak" /b /a-d /tw /od') do (
    if not exist "%folderPath%%%~ni.zip" (
        "C:\Program Files\7-Zip\7z.exe" a -p%password% "%folderPath%%%~ni.zip" "%folderPath%%%i" && del "%folderPath%%%i"
    )
)
timeout /t 5 >nul
goto loop
exit
