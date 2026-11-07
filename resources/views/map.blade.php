<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="https://awsimages.detik.net.id/content/2014/05/30/1562/prabowosubianto.jpeg">
    <title>GIS GADM Studio - Web GIS & Depot Management</title>

    <!-- Leaflet CSS via CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --bg-canvas: #090d16;
            --surface-panel: #ffffff;
            --surface-subtle: #f8fafc;
            --surface-muted: #f1f5f9;
            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --primary: #0f172a;
            --primary-hover: #1e293b;
            --accent-depot: #dc2626;
            --accent-depot-bg: #fee2e2;
            --accent-point: #0284c7;
            --accent-point-bg: #e0f2fe;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --shadow-float: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
            --shadow-subtle: 0 1px 3px rgba(15, 23, 42, 0.08);
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: var(--font-sans);
            background-color: var(--bg-canvas);
            color: var(--text-body);
            -webkit-font-smoothing: antialiased;
        }

        /* Full Bleed Map Viewport */
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background-color: #e5e7eb;
        }

        /* Floating Command Island (Top Left) */
        .command-island {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 8px 14px;
            box-shadow: var(--shadow-float);
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-right: 12px;
            border-right: 1px solid var(--border-subtle);
        }

        .brand-badge svg {
            width: 20px;
            height: 20px;
            color: var(--primary);
        }

        .brand-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.2px;
            white-space: nowrap;
        }

        .command-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .control-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .select-input {
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-heading);
            background: var(--surface-panel);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s ease;
        }

        .select-input:focus-visible {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.15);
        }

        .loading-pill {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #0369a1;
            background: var(--accent-point-bg);
            padding: 4px 8px;
            border-radius: var(--radius-sm);
        }

        .spinner {
            width: 12px;
            height: 12px;
            border: 2px solid #bae6fd;
            border-top-color: #0284c7;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease;
            white-space: nowrap;
        }

        .btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 1px;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-subtle {
            background: var(--surface-muted);
            color: var(--text-heading);
            border-color: var(--border-subtle);
        }
        .btn-subtle:hover {
            background: var(--border-subtle);
        }

        .btn-danger {
            background: var(--accent-depot-bg);
            color: var(--accent-depot);
            border-color: #fca5a5;
        }
        .btn-danger:hover {
            background: #fecaca;
        }

        .btn-icon {
            padding: 6px;
            line-height: 1;
        }

        /* Top Right Drawer Toggle Button */
        .drawer-toggle-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 1000;
            background: var(--surface-panel);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            padding: 8px 14px;
            box-shadow: var(--shadow-float);
            color: var(--text-heading);
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .drawer-toggle-btn:hover {
            background: var(--surface-subtle);
        }

        .counter-badge {
            background: var(--primary);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
        }

        /* Side Docking Panel / Drawer */
        .side-drawer {
            position: absolute;
            top: 16px;
            right: 16px;
            bottom: 16px;
            width: 380px;
            max-width: calc(100vw - 32px);
            z-index: 1001;
            background: var(--surface-panel);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-float);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease;
        }

        .side-drawer.collapsed {
            transform: translateX(420px);
            opacity: 0;
            pointer-events: none;
        }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-subtle);
            background: var(--surface-panel);
        }

        .drawer-header h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.2px;
        }

        .drawer-tabs {
            display: flex;
            padding: 4px;
            background: var(--surface-muted);
            border-radius: var(--radius-sm);
            margin: 10px 16px 0;
            gap: 4px;
        }

        .tab-btn {
            flex: 1;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            background: transparent;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .tab-btn.active {
            background: var(--surface-panel);
            color: var(--text-heading);
            box-shadow: var(--shadow-subtle);
        }

        .drawer-content {
            flex: 1;
            overflow-y: auto;
            padding: 14px 16px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-input {
            width: 100%;
            padding: 7px 10px;
            font-size: 12px;
            color: var(--text-heading);
            background: var(--surface-panel);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            outline: none;
            transition: border-color 0.15s ease;
        }

        .form-input:focus-visible {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.15);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .form-hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.4;
        }

        /* Point List Items */
        .points-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .point-card {
            background: var(--surface-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-sm);
            padding: 10px 12px;
            transition: border-color 0.15s ease, background-color 0.15s ease;
        }

        .point-card:hover {
            border-color: var(--border-strong);
            background: #ffffff;
        }

        .point-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .point-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-heading);
        }

        .badge-type {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .badge-depot {
            background: var(--accent-depot-bg);
            color: var(--accent-depot);
            border: 1px solid #fca5a5;
        }

        .badge-lokasi {
            background: var(--accent-point-bg);
            color: var(--accent-point);
            border: 1px solid #bae6fd;
        }

        .point-coords {
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--text-muted);
            font-variant-numeric: tabular-nums;
            margin-bottom: 6px;
        }

        .point-desc {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .point-actions {
            display: flex;
            gap: 6px;
        }

        /* Live Telemetry HUD (Bottom Left) */
        .telemetry-hud {
            position: absolute;
            bottom: 16px;
            left: 16px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #f8fafc;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-family: var(--font-mono);
            font-variant-numeric: tabular-nums;
            border: 1px solid rgba(255, 255, 255, 0.1);
            pointer-events: none;
            user-select: none;
        }

        .hud-item {
            display: flex;
            gap: 5px;
        }

        .hud-label {
            color: #94a3b8;
            font-weight: 600;
        }

        .hud-val {
            color: #ffffff;
            font-weight: 600;
        }

        /* Minimal Map Legend (Bottom Right) */
        .map-legend {
            position: absolute;
            bottom: 16px;
            right: 16px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 500;
            box-shadow: var(--shadow-subtle);
        }

        .legend-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .legend-depot {
            background-color: var(--accent-depot);
        }

        .legend-point {
            background-color: var(--accent-point);
        }

        /* Custom SVG Leaflet Markers */
        .custom-marker-icon {
            background: none !important;
            border: none !important;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .command-island {
                top: 10px;
                left: 10px;
                right: 10px;
                flex-wrap: wrap;
                gap: 8px;
            }

            .drawer-toggle-btn {
                top: auto;
                bottom: 60px;
                right: 10px;
            }

            .side-drawer {
                top: auto;
                left: 10px;
                right: 10px;
                bottom: 10px;
                width: auto;
                height: 55vh;
            }

            .telemetry-hud {
                display: none;
            }

            .map-legend {
                bottom: 10px;
                left: 10px;
                right: auto;
            }
        }
    </style>
</head>

<body>

    <!-- Full-Bleed Map Viewport -->
    <div id="map"></div>

    <!-- Floating Command Island (Top Left) -->
    <div class="command-island">
        <div class="brand-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon>
                <line x1="9" y1="3" x2="9" y2="18"></line>
                <line x1="15" y1="6" x2="15" y2="21"></line>
            </svg>
            <span class="brand-title">GIS Studio</span>
        </div>

        <div class="command-group">
            <label for="levelSelect" class="control-label">Batas GADM:</label>
            <select id="levelSelect" class="select-input" autocomplete="off" onchange="loadGadmLevel(this.value, false)">
                <option value="none">Nonaktif</option>
                <option value="0" selected>Lvl 0 (Negara)</option>
                <option value="1">Lvl 1 (Provinsi)</option>
                <option value="2">Lvl 2 (Kab/Kota)</option>
                <option value="3">Lvl 3 (Kecamatan)</option>
                <option value="4">Lvl 4 (Desa)</option>
            </select>
        </div>

        <div class="command-group">
            <button type="button" class="btn btn-subtle" onclick="fitMapToDepots(true)" title="Pusatkan peta ke cakupan titik depot">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg>
                Fokus Depot
            </button>
            <button type="button" class="btn btn-subtle" onclick="resetToSampleData()" title="Muat 7 titik contoh wilayah Indramayu dari modul">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                Reset Contoh
            </button>
        </div>

        <div id="loadingIndicator" class="loading-pill">
            <div class="spinner"></div>
            <span id="loadingText">Memuat...</span>
        </div>
    </div>

    <!-- Toggle Button to Open Side Drawer -->
    <button type="button" class="drawer-toggle-btn" onclick="toggleDrawer(true)">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        <span>Kelola Titik</span>
        <span id="btnPointCounter" class="counter-badge">0</span>
    </button>

    <!-- Side Docking Drawer Panel (CRUD & List) -->
    <div id="sideDrawer" class="side-drawer">
        <div class="drawer-header">
            <h3>Manajemen Titik Lokasi</h3>
            <button type="button" class="btn btn-subtle btn-icon" onclick="toggleDrawer(false)" title="Tutup panel">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div class="drawer-tabs">
            <button type="button" id="tabBtnList" class="tab-btn active" onclick="switchDrawerTab('list')">Daftar Titik</button>
            <button type="button" id="tabBtnForm" class="tab-btn" onclick="switchDrawerTab('form')">+ Tambah Titik</button>
        </div>

        <div class="drawer-content">
            <!-- Tab Pane 1: Daftar Titik -->
            <div id="tabList" class="tab-pane active">
                <div id="pointsContainer" class="points-list">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Tab Pane 2: Form Input -->
            <div id="tabForm" class="tab-pane">
                <form id="depotForm" onsubmit="handleFormSubmit(event)">
                    <input type="hidden" id="pointId" value="">

                    <div class="form-group">
                        <label for="pointName">Nama Titik *</label>
                        <input type="text" id="pointName" class="form-input" required placeholder="Contoh: Depot Indramayu Pusat">
                    </div>

                    <div class="form-group">
                        <label for="pointType">Tipe Titik *</label>
                        <select id="pointType" class="form-input" required>
                            <option value="depot">Depot Titik Awal (Pin Merah)</option>
                            <option value="lokasi">Titik Tujuan / Lokasi (Pin Biru)</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="pointLat">Latitude *</label>
                            <input type="number" step="any" id="pointLat" class="form-input" required placeholder="-6.326389">
                        </div>
                        <div class="form-group">
                            <label for="pointLng">Longitude *</label>
                            <input type="number" step="any" id="pointLng" class="form-input" required placeholder="108.320000">
                        </div>
                    </div>
                    <p class="form-hint">Tip: Klik titik mana saja pada peta untuk mengisi koordinat secara otomatis.</p>

                    <div class="form-group" style="margin-top: 10px;">
                        <label for="pointDesc">Keterangan Tambahan</label>
                        <input type="text" id="pointDesc" class="form-input" placeholder="Catatan operasional (opsional)">
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 16px;">
                        <button type="submit" id="btnSubmitForm" class="btn btn-primary" style="flex: 1;">Simpan Titik</button>
                        <button type="button" id="btnCancelEdit" class="btn btn-subtle" onclick="cancelEdit()" style="display: none;">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Telemetry HUD (Bottom Left) -->
    <div class="telemetry-hud">
        <div class="hud-item">
            <span class="hud-label">LAT</span>
            <span id="hudLat" class="hud-val">-</span>
        </div>
        <div class="hud-item">
            <span class="hud-label">LNG</span>
            <span id="hudLng" class="hud-val">-</span>
        </div>
        <div class="hud-item">
            <span class="hud-label">ZOOM</span>
            <span id="hudZoom" class="hud-val">5</span>
        </div>
    </div>

    <!-- Minimal Map Legend (Bottom Right) -->
    <div class="map-legend">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span class="legend-indicator legend-depot"></span>
            <span>Depot (Awal)</span>
        </div>
        <div style="display: flex; align-items: center; gap: 6px;">
            <span class="legend-indicator legend-point"></span>
            <span>Titik Lokasi</span>
        </div>
    </div>

    <!-- Leaflet JS via CDN -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. Inisialisasi Peta Leaflet (Center Indonesia)
        const map = L.map('map', {
            zoomControl: false
        }).setView([-2.548926, 118.014863], 5);

        // Zoom control repositioned to bottom right above legend
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Basemap OpenStreetMap
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        let currentGeoJsonLayer = null;
        let gadmRequestId = 0;
        let activeGadmLevel = null;
        let depotMarkersLayer = L.featureGroup().addTo(map);
        let depotPointsData = [];
        let clickTempMarker = null;

        // Custom High-Contrast SVG Icon Generator
        function createCustomIcon(type) {
            const isDepot = (type === 'depot');
            const pinColor = isDepot ? '#dc2626' : '#0284c7';
            const borderColor = isDepot ? '#991b1b' : '#0369a1';
            const dotColor = '#ffffff';

            const svgHtml = `
                <div style="width: 28px; height: 38px; position: relative; filter: drop-shadow(0 2px 5px rgba(15,23,42,0.35)); cursor: pointer;">
                    <svg viewBox="0 0 30 42" width="28" height="38" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 0C6.716 0 0 6.716 0 15c0 11.25 15 27 15 27s15-15.75 15-27c0-8.284-6.716-15-15-15z" fill="${pinColor}" stroke="${borderColor}" stroke-width="1.2"/>
                        <circle cx="15" cy="14" r="5" fill="${dotColor}"/>
                    </svg>
                </div>
            `;

            return L.divIcon({
                className: 'custom-marker-icon',
                html: svgHtml,
                iconSize: [28, 38],
                iconAnchor: [14, 38],
                popupAnchor: [0, -34]
            });
        }

        // ==========================================
        // Live Telemetry Event Handlers
        // ==========================================
        map.on('mousemove', function(e) {
            document.getElementById('hudLat').innerText = e.latlng.lat.toFixed(6);
            document.getElementById('hudLng').innerText = e.latlng.lng.toFixed(6);
        });

        map.on('zoomend', function() {
            document.getElementById('hudZoom').innerText = map.getZoom();
        });

        // ==========================================
        // UI Drawer Tabs & Visibility
        // ==========================================
        function toggleDrawer(open) {
            const drawer = document.getElementById('sideDrawer');
            if (open) {
                drawer.classList.remove('collapsed');
            } else {
                drawer.classList.add('collapsed');
            }
        }

        function switchDrawerTab(tab) {
            const tabList = document.getElementById('tabList');
            const tabForm = document.getElementById('tabForm');
            const btnList = document.getElementById('tabBtnList');
            const btnForm = document.getElementById('tabBtnForm');

            if (tab === 'list') {
                tabList.classList.add('active');
                tabForm.classList.remove('active');
                btnList.classList.add('active');
                btnForm.classList.remove('active');
            } else {
                tabList.classList.remove('active');
                tabForm.classList.add('active');
                btnList.classList.remove('active');
                btnForm.classList.add('active');
            }
        }

        // ==========================================
        // GADM GEOJSON Service & Rendering
        // ==========================================
        function loadGadmLevel(level, autoFitGadm = false) {
            level = String(level);
            activeGadmLevel = level;

            try {
                localStorage.setItem('gis_gadm_selected_level', level);
            } catch (e) {
                console.warn('LocalStorage error:', e);
            }

            const selectElem = document.getElementById('levelSelect');
            if (selectElem && selectElem.value !== level) {
                selectElem.value = level;
            }

            if (level === 'none') {
                if (currentGeoJsonLayer) {
                    map.removeLayer(currentGeoJsonLayer);
                    currentGeoJsonLayer = null;
                }
                const loading = document.getElementById('loadingIndicator');
                if (loading) loading.style.display = 'none';
                return;
            }

            const thisRequestId = ++gadmRequestId;
            const loading = document.getElementById('loadingIndicator');
            const loadingText = document.getElementById('loadingText');
            if (loading) {
                loading.style.display = 'flex';
                loadingText.innerText = `Lvl ${level}...`;
            }

            if (currentGeoJsonLayer) {
                map.removeLayer(currentGeoJsonLayer);
                currentGeoJsonLayer = null;
            }

            fetch(`/api/geojson/gadm/${level}`)
                .then(res => {
                    if (!res.ok) throw new Error(`GeoJSON Level ${level} tidak ditemukan.`);
                    return res.json();
                })
                .then(data => {
                    if (thisRequestId !== gadmRequestId) return;

                    currentGeoJsonLayer = L.geoJSON(data, {
                        style: {
                            color: '#1e293b',
                            weight: 1.2,
                            fillColor: '#38bdf8',
                            fillOpacity: 0.22
                        },
                        onEachFeature: function(feature, layer) {
                            const p = feature.properties || {};
                            const namaWilayah = p.NAME_4 || p.NAME_3 || p.NAME_2 || p.NAME_1 || p.NAME_0 || 'Wilayah';
                            const tipeWilayah = p.TYPE_2 || p.TYPE_1 || '';

                            layer.on('click', function(e) {
                                const lat = e.latlng.lat.toFixed(6);
                                const lng = e.latlng.lng.toFixed(6);
                                const altId = 'alt_' + Math.random().toString(36).substr(2, 9);

                                document.getElementById('pointLat').value = lat;
                                document.getElementById('pointLng').value = lng;

                                const popupHtml = `
                                    <div style="font-family: var(--font-sans); font-size: 13px; min-width: 180px;">
                                        <strong style="font-size: 14px; color: #0f172a;">${namaWilayah}</strong><br>
                                        <span style="color: #64748b; font-size: 11px;">${tipeWilayah ? tipeWilayah + ' ' : ''}(GADM Lvl ${level})</span>
                                        <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #e2e8f0; font-size: 12px; font-family: var(--font-mono); line-height: 1.6; color: #334155;">
                                            <div>LAT: <strong>${lat}</strong></div>
                                            <div>LNG: <strong>${lng}</strong></div>
                                            <div id="${altId}">ALT: <span style="color: #64748b;">Memuat...</span></div>
                                        </div>
                                    </div>
                                `;

                                layer.bindPopup(popupHtml).openPopup(e.latlng);

                                fetch(`https://api.open-meteo.com/v1/elevation?latitude=${lat}&longitude=${lng}`)
                                    .then(res => res.json())
                                    .then(elevData => {
                                        const el = document.getElementById(altId);
                                        if (el && elevData && elevData.elevation && elevData.elevation.length > 0) {
                                            el.innerHTML = `ALT: <strong>${elevData.elevation[0]} mdpl</strong>`;
                                        }
                                    })
                                    .catch(() => {
                                        const el = document.getElementById(altId);
                                        if (el) el.innerHTML = `ALT: -`;
                                    });
                            });
                        }
                    }).addTo(map);

                    // Kamera zoom: jika autoFitGadm true atau belum ada depot, fit ke layer GADM
                    if (depotPointsData.length > 0 && !autoFitGadm) {
                        fitMapToDepots(false);
                    } else if (currentGeoJsonLayer.getBounds().isValid()) {
                        map.fitBounds(currentGeoJsonLayer.getBounds());
                    }
                })
                .catch(err => {
                    if (thisRequestId === gadmRequestId) alert(err.message);
                })
                .finally(() => {
                    if (thisRequestId === gadmRequestId && loading) {
                        loading.style.display = 'none';
                    }
                });
        }

        // ==========================================
        // CRUD Titik Depot & Lokasi
        // ==========================================
        function fitMapToDepots(animate = false) {
            if (!depotPointsData || depotPointsData.length === 0) return;

            if (depotPointsData.length === 1) {
                const pt = depotPointsData[0];
                if (animate) {
                    map.flyTo([pt.lat, pt.lng], 12, { duration: 0.8 });
                } else {
                    map.setView([pt.lat, pt.lng], 12);
                }
                return;
            }

            const bounds = depotMarkersLayer.getBounds();
            if (bounds && bounds.isValid()) {
                if (animate) {
                    map.flyToBounds(bounds, { padding: [60, 60], maxZoom: 14, duration: 0.8 });
                } else {
                    map.fitBounds(bounds, { padding: [60, 60], maxZoom: 14 });
                }
            }
        }

        function fetchDepots(andFitBounds = true) {
            fetch('/api/depots')
                .then(res => res.json())
                .then(res => {
                    depotPointsData = res.data || [];
                    renderMarkers();
                    renderList();

                    const counter = document.getElementById('btnPointCounter');
                    if (counter) counter.innerText = depotPointsData.length;

                    if (depotPointsData.length > 0) {
                        fitMapToDepots(andFitBounds === 'animate' || andFitBounds === true);
                    }
                })
                .catch(err => {
                    console.error('Gagal mengambil data depot:', err);
                });
        }

        function renderMarkers() {
            depotMarkersLayer.clearLayers();

            depotPointsData.forEach(item => {
                const icon = createCustomIcon(item.type);
                const marker = L.marker([item.lat, item.lng], { icon: icon });

                const isDepot = item.type === 'depot';
                const badgeClass = isDepot ? 'badge-depot' : 'badge-lokasi';
                const badgeLabel = isDepot ? 'Depot Titik Awal' : 'Titik Lokasi';

                const popupContent = `
                    <div style="font-family: var(--font-sans); min-width: 170px; padding: 2px;">
                        <span class="badge-type ${badgeClass}" style="display: inline-block; margin-bottom: 6px;">${badgeLabel}</span><br>
                        <strong style="font-size: 14px; color: #0f172a;">${item.name}</strong>
                        <div style="font-size: 11px; font-family: var(--font-mono); color: #475569; margin: 6px 0;">
                            LAT: ${item.lat.toFixed(6)}<br>
                            LNG: ${item.lng.toFixed(6)}
                            ${item.description ? `<br><em style="color: #64748b; font-family: var(--font-sans);">${item.description}</em>` : ''}
                        </div>
                        <div style="display: flex; gap: 4px; margin-top: 8px;">
                            <button onclick="editPoint('${item.id}')" style="flex: 1; padding: 4px 8px; font-size: 11px; font-weight: 600; background: #0f172a; color: white; border: none; border-radius: 4px; cursor: pointer;">Edit</button>
                            <button onclick="deletePoint('${item.id}')" style="flex: 1; padding: 4px 8px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 4px; cursor: pointer;">Hapus</button>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                marker.pointId = item.id;
                depotMarkersLayer.addLayer(marker);
            });
        }

        function renderList() {
            const container = document.getElementById('pointsContainer');
            if (!container) return;

            if (depotPointsData.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; color: var(--text-muted); padding: 30px 10px; font-size: 12px;">
                        Belum ada data titik.<br>Klik tombol <strong>+ Tambah Titik</strong> atau muat contoh modul.
                    </div>
                `;
                return;
            }

            container.innerHTML = depotPointsData.map(item => {
                const isDepot = item.type === 'depot';
                const badgeClass = isDepot ? 'badge-depot' : 'badge-lokasi';
                const badgeText = isDepot ? 'Depot' : 'Lokasi';

                return `
                    <div class="point-card">
                        <div class="point-card-header">
                            <span class="point-name">${item.name}</span>
                            <span class="badge-type ${badgeClass}">${badgeText}</span>
                        </div>
                        <div class="point-coords">${item.lat.toFixed(6)}, ${item.lng.toFixed(6)}</div>
                        ${item.description ? `<div class="point-desc">${item.description}</div>` : ''}
                        <div class="point-actions">
                            <button type="button" class="btn btn-subtle" style="flex: 1; padding: 4px;" onclick="focusOnPoint(${item.lat}, ${item.lng}, '${item.id}')">Fokus</button>
                            <button type="button" class="btn btn-subtle" style="padding: 4px 8px;" onclick="editPoint('${item.id}')">Edit</button>
                            <button type="button" class="btn btn-danger" style="padding: 4px 8px;" onclick="deletePoint('${item.id}')">Hapus</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Map Click: Auto populate coordinates & temporary crosshair
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            const mapAltId = 'map_alt_' + Math.random().toString(36).substr(2, 9);

            document.getElementById('pointLat').value = lat;
            document.getElementById('pointLng').value = lng;

            if (clickTempMarker) {
                map.removeLayer(clickTempMarker);
            }

            clickTempMarker = L.circleMarker([lat, lng], {
                radius: 6,
                color: '#dc2626',
                fillColor: '#ffffff',
                fillOpacity: 1,
                weight: 2
            }).addTo(map);

            const mapPopupHtml = `
                <div style="font-family: var(--font-sans); font-size: 12px; min-width: 160px;">
                    <strong style="color: #0f172a;">Koordinat Terpilih</strong>
                    <div style="margin-top: 6px; font-family: var(--font-mono); font-size: 11px; line-height: 1.5; color: #334155;">
                        <div>LAT: ${lat}</div>
                        <div>LNG: ${lng}</div>
                        <div id="${mapAltId}">ALT: <span style="color: #64748b;">Memuat...</span></div>
                    </div>
                </div>
            `;

            clickTempMarker.bindPopup(mapPopupHtml).openPopup();

            fetch(`https://api.open-meteo.com/v1/elevation?latitude=${lat}&longitude=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const el = document.getElementById(mapAltId);
                    if (el && data && data.elevation && data.elevation.length > 0) {
                        el.innerHTML = `ALT: <strong>${data.elevation[0]} mdpl</strong>`;
                    }
                })
                .catch(() => {
                    const el = document.getElementById(mapAltId);
                    if (el) el.innerHTML = `ALT: -`;
                });
        });

        // Form Submit: Add or Update Point
        function handleFormSubmit(event) {
            event.preventDefault();

            const id = document.getElementById('pointId').value;
            const name = document.getElementById('pointName').value.trim();
            const type = document.getElementById('pointType').value;
            const lat = parseFloat(document.getElementById('pointLat').value);
            const lng = parseFloat(document.getElementById('pointLng').value);
            const description = document.getElementById('pointDesc').value.trim();

            const payload = { name, type, lat, lng, description };
            const isEdit = Boolean(id);
            const url = isEdit ? `/api/depots/${id}` : '/api/depots';
            const method = isEdit ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (clickTempMarker) {
                        map.removeLayer(clickTempMarker);
                        clickTempMarker = null;
                    }
                    resetForm();
                    fetchDepots(false);
                    switchDrawerTab('list');
                    focusOnPoint(lat, lng, data.data ? data.data.id : id);
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Error'));
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan jaringan.');
                console.error(err);
            });
        }

        function editPoint(id) {
            const item = depotPointsData.find(p => p.id === id);
            if (!item) return;

            document.getElementById('pointId').value = item.id;
            document.getElementById('pointName').value = item.name;
            document.getElementById('pointType').value = item.type;
            document.getElementById('pointLat').value = item.lat;
            document.getElementById('pointLng').value = item.lng;
            document.getElementById('pointDesc').value = item.description || '';

            document.getElementById('btnSubmitForm').innerText = 'Perbarui Titik';
            document.getElementById('btnCancelEdit').style.display = 'inline-block';

            switchDrawerTab('form');
            toggleDrawer(true);
            focusOnPoint(item.lat, item.lng, item.id);
        }

        function cancelEdit() {
            resetForm();
            switchDrawerTab('list');
        }

        function deletePoint(id) {
            const item = depotPointsData.find(p => p.id === id);
            const name = item ? item.name : 'titik ini';

            if (!confirm(`Hapus titik "${name}"?`)) return;

            fetch(`/api/depots/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    resetForm();
                    fetchDepots(false);
                } else {
                    alert('Gagal menghapus: ' + (data.message || 'Error'));
                }
            })
            .catch(err => {
                console.error(err);
            });
        }

        function resetForm() {
            document.getElementById('pointId').value = '';
            document.getElementById('depotForm').reset();
            document.getElementById('btnSubmitForm').innerText = 'Simpan Titik';
            document.getElementById('btnCancelEdit').style.display = 'none';

            if (clickTempMarker) {
                map.removeLayer(clickTempMarker);
                clickTempMarker = null;
            }
        }

        function focusOnPoint(lat, lng, pointId) {
            map.flyTo([lat, lng], 13, { duration: 0.8 });

            setTimeout(() => {
                depotMarkersLayer.eachLayer(layer => {
                    if (layer.pointId === pointId) {
                        layer.openPopup();
                    }
                });
            }, 850);
        }

        function resetToSampleData() {
            if (!confirm('Reset daftar titik ke data sampel Modul GIS (Indramayu)?')) return;

            fetch('/api/depots/reset-sample', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                resetForm();
                fetchDepots(true);
            })
            .catch(err => {
                alert('Gagal mereset data sampel');
            });
        }

        // ==========================================
        // Inisialisasi Awal
        // ==========================================
        let initialLevel = '0';
        try {
            const savedLevel = localStorage.getItem('gis_gadm_selected_level');
            const validLevels = ['none', '0', '1', '2', '3', '4'];
            if (savedLevel && validLevels.includes(savedLevel)) {
                initialLevel = savedLevel;
            }
        } catch (e) {
            console.warn('LocalStorage error:', e);
        }

        const selectElem = document.getElementById('levelSelect');
        if (selectElem) {
            selectElem.value = initialLevel;
        }

        // 1. Muat data titik depot & otomatis zoom ke cakupan rerata titik depot
        fetchDepots(false);

        // 2. Muat layer batas wilayah GADM di latar belakang
        loadGadmLevel(initialLevel, false);
    </script>

</body>

</html>
