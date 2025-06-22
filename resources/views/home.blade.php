@extends('layout.template')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700;900&display=swap');

  body, html {
    margin: 0; padding: 0;
    height: 100%; width: 100%;
    font-family: 'Poppins', sans-serif;
    background-color: #f9fafb;
    color: #1f2937;
    overflow-x: hidden;
  }

  .landing-container {
    position: relative;
    min-height: 100vh;
    width: 100%;
    background: url('https://wallpapers.com/images/hd/industry-pictures-1920-x-1080-tjxk959nyyf7ovus.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
  }

  .overlay {
    position: absolute;
    inset: 0;
    background: rgba(17, 24, 39, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1;
  }

  .content-wrapper {
    position: relative;
    z-index: 2;
    max-width: 1100px;
    width: 100%;
    background: white;
    border-radius: 1rem;
    padding: 3rem 4rem;
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    animation: fadeIn 1s ease-in-out;
    margin: 3rem auto;
  }

  h1.title {
    font-size: 2.5rem;
    font-weight: 900;
    color: #111827;
    margin-bottom: 1rem;
  }

  p.subtitle {
    font-size: 1.125rem;
    color: #6b7280;
    margin-bottom: 2.5rem;
    line-height: 1.6;
  }

  .chart-section {
    background: linear-gradient(to right, #f0f9ff, #e0f2fe);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    margin-bottom: 3rem;
  }

  .btn-login {
    background-color: #2563eb;
    color: #fff;
    font-weight: 700;
    font-size: 1.125rem;
    padding: 0.75rem 2.5rem;
    border-radius: 0.75rem;
    transition: 0.3s ease-in-out;
    box-shadow: 0 8px 20px rgba(37,99,235,0.3);
    display: inline-block;
    text-decoration: none;
  }

  .btn-login:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
  }

  .industries {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 2.5rem;
  }

  .industry-card {
    width: 260px;
    background-color: #fefefe;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
  }

  .industry-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12);
  }

  .industry-card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
  }

  .industry-card h2 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 1rem 1rem 0.5rem;
    color: #111827;
  }

  .industry-card p {
    color: #6b7280;
    font-size: 0.95rem;
    padding: 0 1rem 1.25rem;
  }

  #map {
    height: 400px;
    width: 100%;
    border-radius: 1rem;
    margin-top: 1rem;
  }

  #ekonomiChart {
    width: 100%;
    max-width: 1000px;
    height: 600px !important;
    margin: 0 auto;
    display: block;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<div class="landing-container" role="main">
  <div class="overlay"></div>
  @auth
    <div class="content-wrapper">
      <h1 class="title">Welcome Back!</h1>
      <p class="subtitle">Explore the various industries in Nganjuk Regency:</p>
      <div class="industries">
        <div class="industry-card">
          <img src="https://moondoggiesmusic.com/wp-content/uploads/2019/02/Kegiatan-Produksi.jpg" alt="Consumer Goods">
          <h2>Consumer Goods</h2>
          <p>Daily necessities such as food, beverages, and household supplies.</p>
        </div>
        <div class="industry-card">
          <img src="https://cdn1.katadata.co.id/template/template_kemnaker/images/bg_kemnaker_r1.jpg" alt="Raw Materials">
          <h2>Raw Materials & Packaging</h2>
          <p>Processing raw materials into semi-finished goods and packaging.</p>
        </div>
        <div class="industry-card">
          <img src="https://kompas.id/wp-content/uploads/2019/08/000_1JO7AJ_1566489981.jpg" alt="Tech Industry">
          <h2>Technology & Specialty</h2>
          <p>Technical or specialized products like electronics and accessories.</p>
        </div>
      </div>
    </div>
  @endauth
  @guest
    <div class="content-wrapper">
      <h1 class="title">Empowering Local Industries Through Digital Innovation</h1>
      <p class="subtitle">Nganjuk Regency is home to SMEs in agriculture, food, crafts, and more. This project supports them digitally.</p>
      <a href="{{ route('login') }}" class="btn-login">Login</a>
    </div>
  @endguest
</div>

@auth
<div class="content-wrapper mt-10">
  <h1 class="title">Administrative Overview</h1>
  <p class="subtitle">General overview of region, economy, and industries in Nganjuk Regency.</p>
  <div class="industries" style="justify-content: space-between;">
    <div class="industry-card">
      <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" alt="Administration" style="height: 100px; width: auto; margin-top: 1rem;">
      <h2>Administrative Division</h2>
      <p>20 districts, 264 villages, 20 sub-districts. Capital in Nganjuk District.</p>
    </div>
    <div class="industry-card">
      <img src="https://cdn-icons-png.flaticon.com/512/1828/1828919.png" alt="Economy" style="height: 100px; width: auto; margin-top: 1rem;">
      <h2>Local Economy</h2>
      <p>Main sectors: agriculture, trade, and SMEs (rice, shallots, corn).</p>
    </div>
    <div class="industry-card">
      <img src="https://cdn-icons-png.flaticon.com/512/2331/2331948.png" alt="Industry" style="height: 100px; width: auto; margin-top: 1rem;">
      <h2>Local Industry</h2>
      <p>Batik, food, furniture, textiles—SMEs spread across the region.</p>
    </div>
    <div class="industry-card">
      <img src="https://cdn-icons-png.flaticon.com/512/883/883700.png" alt="Infrastructure" style="height: 100px; width: auto; margin-top: 1rem;">
      <h2>Infrastructure</h2>
      <p>Connected by Trans-Java toll road, southern railway, and nearby Kediri airport.</p>
    </div>
  </div>
</div>

<div class="content-wrapper mt-10 chart-section">
  <h1 class="title">Economic Sector Composition</h1>
  <p class="subtitle">This donut chart illustrates the approximate contribution of key sectors to Nganjuk’s economy in percentage terms, giving a clearer view of economic balance.</p>
  <canvas id="ekonomiChart"></canvas>
</div>

<div class="content-wrapper mt-10">
  <h1 class="title">Industrial Area Distribution</h1>
  <p class="subtitle">This map shows small and medium industry areas in Nganjuk.</p>
  <div id="map"></div>
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>

<script>
  const ctx = document.getElementById('ekonomiChart')?.getContext('2d');
  if (ctx) {
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['🌾 Agriculture', '🛒 Trade', '🏭 Industry', '💼 Services', '🚚 Transport'],
        datasets: [{
          data: [35, 25, 20, 10, 10],
          backgroundColor: ['#34d399', '#60a5fa', '#f59e0b', '#f472b6', '#a78bfa'],
          borderColor: '#ffffff',
          borderWidth: 3,
          hoverOffset: 25
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '40%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: {
                family: 'Poppins',
                size: 16,
                weight: '500'
              },
              usePointStyle: true,
              pointStyle: 'circle'
            }
          },
          title: {
            display: true,
            text: 'Sectoral Contribution to GRDP (%)',
            font: {
              size: 26,
              family: 'Poppins',
              weight: 'bold'
            },
            padding: {
              top: 20,
              bottom: 30
            }
          },
          tooltip: {
            backgroundColor: '#1f2937',
            titleColor: '#ffffff',
            bodyColor: '#ffffff',
            borderColor: '#e5e7eb',
            borderWidth: 1,
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                return `${label}: ${value}%`;
              }
            }
          }
        }
      }
    });
  }

  const mapContainer = document.getElementById('map');
  if (mapContainer) {
    const map = L.map('map').setView([-7.6038, 111.904], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch('/storage/kecamatan.geojson')
      .then(res => res.json())
      .then(data => {
        L.geoJSON(data, {
          style: {
            color: '#2563eb',
            weight: 2,
            fillColor: '#60a5fa',
            fillOpacity: 0.4
          },
          onEachFeature: function (feature, layer) {
            const name = feature.properties.WADMKC || 'Unknown';
            const count = Math.floor(Math.random() * 20) + 5;
            layer.bindPopup(`<strong>${name}</strong><br>Total Industries: ${count}`);
          }
        }).addTo(map);
      });
  }
</script>
@endsection
