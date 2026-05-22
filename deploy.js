const { execSync } = require('child_process');
const path = require('path');

const projectPath = 'c:\\Users\\alile\\PulseFit';

try {
  console.log('📁 Cambiando a directorio del proyecto...');
  process.chdir(projectPath);

  console.log('🔧 Inicializando repositorio git...');
  execSync('git init', { stdio: 'inherit' });

  console.log('🔗 Agregando remote origin...');
  execSync('git remote add origin https://github.com/Alilek001/PulseFit.git', { stdio: 'inherit' });

  console.log('👤 Configurando usuario de git...');
  execSync('git config user.email "alilekhaal772@gmail.com"', { stdio: 'inherit' });
  execSync('git config user.name "Alilek001"', { stdio: 'inherit' });

  console.log('📦 Agregando todos los archivos...');
  execSync('git add .', { stdio: 'inherit' });

  console.log('💾 Haciendo commit...');
  execSync('git commit -m "Initial commit: Upload PulseFit project"', { stdio: 'inherit' });

  console.log('🚀 Haciendo push a rama main...');
  execSync('git push -u origin main', { stdio: 'inherit' });

  console.log('\n✅ ¡Completado exitosamente!');
} catch (error) {
  console.error('❌ Error:', error.message);
  process.exit(1);
}
