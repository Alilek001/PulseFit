@echo off
cd /d c:\Users\alile\PulseFit
echo Inicializando repositorio git...
git init
echo.
echo Agregando remote origin...
git remote add origin https://github.com/Alilek001/PulseFit.git
echo.
echo Configurando usuario de git...
git config user.email "alilekhaal772@gmail.com"
git config user.name "Alilek001"
echo.
echo Agregando todos los archivos...
git add .
echo.
echo Haciendo commit...
git commit -m "Initial commit: Upload PulseFit project"
echo.
echo Haciendo push a la rama main...
git push -u origin main
echo.
echo ¡Completado!"
