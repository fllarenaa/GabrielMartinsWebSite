import * as THREE from 'https://unpkg.com/three@0.163.0/build/three.module.js';
import { OBJLoader } from 'https://unpkg.com/three@0.163.0/examples/jsm/loaders/OBJLoader.js';

// Cena
const scene = new THREE.Scene();
scene.background = new THREE.Color(0x111111);

// Câmera
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.z = 3;

// Renderizador
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

// Luz
const light = new THREE.DirectionalLight(0xffffff, 1);
light.position.set(2, 2, 2);
scene.add(light);

// Carregar modelo OBJ
const loader = new OBJLoader();
loader.load(
  'Pizza.obj', // nome do arquivo .obj
  function (obj) {
    obj.scale.set(1, 1, 1);
    obj.position.set(0, 0, 0);
    scene.add(obj);
  },
  function (xhr) {
    console.log((xhr.loaded / xhr.total * 100) + '% carregado');
  },
  function (error) {
    console.error('Erro ao carregar o modelo:', error);
  }
);

// Redimensionar janela
window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});

// Loop de renderização
function animate() {
  requestAnimationFrame(animate);
  scene.rotation.y += 0.01; // gira lentamente
  renderer.render(scene, camera);
}
animate();
