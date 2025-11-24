<?php
/**
 * GLB Viewer Tool (local .glb)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)]">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">view_in_ar</span>
            GLB Viewer
        </h1>
        <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
            Drop or pick a .glb file to preview it.
        </div>
    </div>

    <div class="flex flex-1 flex-col md:flex-row overflow-hidden">
        <!-- Controls -->
        <div class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-gray-200 dark:border-slate-700 p-4 space-y-4 bg-white dark:bg-slate-900">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Select .glb file
                </label>
                <input
                    id="glb-file-input"
                    type="file"
                    accept=".glb,model/gltf-binary"
                    class="block w-full text-sm text-gray-900 dark:text-gray-100
                           file:mr-4 file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-white
                           hover:file:bg-primary/90"
                />
            </div>

            <div
                id="glb-dropzone"
                class="mt-4 flex h-32 items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 text-sm text-gray-500 dark:text-gray-400 text-center px-3"
            >
                Drop .glb file here
            </div>

            <div class="pt-4 space-y-2">
                <p id="glb-status" class="text-xs text-gray-500 dark:text-gray-400">
                    Waiting for file...
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Use mouse to orbit, zoom, and pan around the model.
                </p>
            </div>
        </div>

        <!-- Viewer -->
        <div class="flex-1 bg-gray-900 relative">
            <div id="glb-viewer" class="w-full h-full"></div>
        </div>
    </div>
</div>

<script type="module">
    import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';
    import { GLTFLoader } from 'https://unpkg.com/three@0.160.0/examples/jsm/loaders/GLTFLoader.js';
    import { OrbitControls } from 'https://unpkg.com/three@0.160.0/examples/jsm/controls/OrbitControls.js';

    const container = document.getElementById('glb-viewer');
    const fileInput = document.getElementById('glb-file-input');
    const dropzone = document.getElementById('glb-dropzone');
    const statusEl = document.getElementById('glb-status');

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

        // Lights
        const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444, 1.2);
        hemiLight.position.set(0, 20, 0);
        scene.add(hemiLight);

        const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
        dirLight.position.set(5, 10, 7.5);
        scene.add(dirLight);

        // Ground
        const grid = new THREE.GridHelper(10, 20, 0x444444, 0x222222);
        scene.add(grid);

        // Controls
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

    function loadGLBFromFile(file) {
        if (!file) return;
        statusEl.textContent = 'Loading: ' + file.name + ' ...';

        const url = URL.createObjectURL(file);

        loader.load(
            url,
            (gltf) => {
                if (currentModel) {
                    scene.remove(currentModel);
                }
                currentModel = gltf.scene;
                scene.add(currentModel);

                fitCameraToObject(currentModel);
                statusEl.textContent = 'Loaded: ' + file.name;

                URL.revokeObjectURL(url);
            },
            undefined,
            (error) => {
                console.error(error);
                statusEl.textContent = 'Error loading model: ' + error.message;
                URL.revokeObjectURL(url);
            }
        );
    }

    // File input
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            loadGLBFromFile(file);
        }
    });

    // Drag & drop
    ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ;['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.add('border-primary', 'bg-primary/5');
        });
    });

    ;['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('border-primary', 'bg-primary/5');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if (file) {
            loadGLBFromFile(file);
        }
    });

    init();
</script>
