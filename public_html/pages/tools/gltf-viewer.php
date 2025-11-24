<?php
/**
 * GLTF Viewer Tool (.gltf via URL)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">view_in_ar</span>
            GLTF Viewer
        </h1>
        <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
            Load remote .gltf models by URL.
        </div>
    </div>

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
        <!-- Controls -->
        <div class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-gray-200 dark:border-slate-700 p-4 space-y-4 bg-white dark:bg-slate-900">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    GLTF URL
                </label>
                <input
                    id="gltf-url"
                    type="text"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="https://example.com/model/scene.gltf"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    URL must be publicly accessible and CORS-enabled.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button
                    id="gltf-load"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <span class="material-symbols-outlined text-sm">download</span>
                    Load Model
                </button>
            </div>

            <div class="pt-4 space-y-2">
                <p id="gltf-status" class="text-xs text-gray-500 dark:text-gray-400">
                    Enter a URL and click "Load Model".
                </p>
            </div>
        </div>

        <!-- Viewer -->
        <div class="flex-1 bg-gray-900 relative">
            <div id="gltf-viewer" class="w-full h-full"></div>
        </div>
    </div>
</div>

<script type="module">
    import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';
    import { GLTFLoader } from 'https://unpkg.com/three@0.160.0/examples/jsm/loaders/GLTFLoader.js';
    import { OrbitControls } from 'https://unpkg.com/three@0.160.0/examples/jsm/controls/OrbitControls.js';

    const container = document.getElementById('gltf-viewer');
    const urlInput = document.getElementById('gltf-url');
    const loadBtn = document.getElementById('gltf-load');
    const statusEl = document.getElementById('gltf-status');

    let renderer, scene, camera, controls, currentModel;

    function init() {
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x111111);

        const width = container.clientWidth;
        const height = container.clientHeight || 400;

        camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        camera.position.set(2, 2, 4);

        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444, 1.2);
        hemiLight.position.set(0, 20, 0);
        scene.add(hemiLight);

        const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
        dirLight.position.set(5, 10, 7.5);
        scene.add(dirLight);

        const grid = new THREE.GridHelper(10, 20, 0x444444, 0x222222);
        scene.add(grid);

        controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;

        window.addEventListener('resize', onWindowResize);

        animate();
    }

    function onWindowResize() {
        if (!renderer || !camera) return;
        const width = container.clientWidth;
        const height = container.clientHeight || 400;
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    }

    function animate() {
        requestAnimationFrame(animate);
        if (controls) controls.update();
        if (renderer && scene && camera) {
            renderer.render(scene, camera);
        }
    }

    const loader = new GLTFLoader();

    function fitCameraToObject(object) {
        const box = new THREE.Box3().setFromObject(object);
        if (!box.isEmpty()) {
            const size = box.getSize(new THREE.Vector3());
            const center = box.getCenter(new THREE.Vector3());

            const maxDim = Math.max(size.x, size.y, size.z);
            const fov = camera.fov * (Math.PI / 180);
            let cameraZ = Math.abs(maxDim / (2 * Math.tan(fov / 2)));

            cameraZ *= 1.5;
            camera.position.set(center.x + cameraZ, center.y + cameraZ, center.z + cameraZ);

            camera.lookAt(center);
            controls.target.copy(center);
            controls.update();
        }
    }

    function loadGLTF(url) {
        if (!url) return;
        statusEl.textContent = 'Loading: ' + url;

        loader.load(
            url,
            (gltf) => {
                if (currentModel) {
                    scene.remove(currentModel);
                }
                currentModel = gltf.scene;
                scene.add(currentModel);

                fitCameraToObject(currentModel);
                statusEl.textContent = 'Loaded model successfully.';
            },
            undefined,
            (error) => {
                console.error(error);
                statusEl.textContent = 'Error loading model: ' + error.message;
            }
        );
    }

    loadBtn.addEventListener('click', () => {
        const url = urlInput.value.trim();
        if (!url) {
            statusEl.textContent = 'Please enter a URL.';
            return;
        }
        loadGLTF(url);
    });

    init();
</script>
