@ECHO OFF
powershell.exe -command "Get-Childitem -path 'C:/Users/FBI/Documents/GitHub/zcms/' -Recurse | foreach-object { $_.LastWriteTime = '02/02/2020 00:00:00'; $_.CreationTime = '02/02/2020 00:00:00'; $_.LastAccessTime = '02/02/2020 00:00:00' }" 
PAUSE
