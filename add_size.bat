@echo off 

setlocal enabledelayedexpansion

::打开系统延时

set /a b=0

dir /b/od

::按时间顺序显示当前文件夹下的所有文件名

pause

::给你反悔时间

for /f "delims=" %%f in ('dir /b/od *.*') do (

  if not "%%f"=="%~nx0" (

           set /a b+=1 

           ren "%%f" "!b!%%~xf"

           echo. !b!%%~xf

)

)

pause

cd /d %~dp0
 
set Pic=*.jp*g,*.png,*.bmp,*.gif
md new

call :CreatVBS
(for %%a in (%Pic%) do (
    for /f "tokens=1-3 delims=x" %%b in ('cscript -nologo "%tmp%\GetImgInfo.vbs" "%%~sa"') do (
        echo %%~nxa    %%~bx%%~c  %%~d dpi
        move %%~nxa new
        ren new\%%~nxa img_%%~na_%%~bx%%~c_%%~d%%~xa
    )
))>log.txt
pause
exit
 
:CreatVBS
(echo 'autoName
echo On Error Resume Next
echo Dim Img
echo Set Img = CreateObject^("WIA.ImageFile"^)
echo Img.LoadFile WScript.Arguments^(0^)
echo Wscript.Echo Img.Width ^& "x" ^& Img.Height ^& "x" ^& Img.HorizontalResolution)>"%tmp%\GetImgInfo.vbs"
goto :eof