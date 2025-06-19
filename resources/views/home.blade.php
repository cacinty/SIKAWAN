@extends('layout.template')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap');

  body, html, #app {
    margin: 0; padding: 0; height: 100%;
    width: 100%;
    font-family: 'Poppins', sans-serif;
    background-color: #ffffff;
    color: #111827;
    overflow-x: hidden;
  }

  .landing-container {
    min-height: 100vh;
    width: 100vw;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 4rem 2rem; /* generous vertical spacing */
    position: relative;
    overflow: hidden;
    background: url('https://wallpapers.com/images/hd/industry-pictures-1920-x-1080-tjxk959nyyf7ovus.jpg') no-repeat center center/cover;
  }

  .overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
  }

  .content-wrapper {
    position: relative;
    z-index: 2;
    max-width: 700px;
    width: 100%;
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    padding: 3rem 3.5rem;
    text-align: center;
  }

  h1.title {
    font-weight: 800;
    font-size: 3.5rem;
    margin-bottom: 1rem;
    color: #1f2937;
    line-height: 1.1;
  }

  p.subtitle {
    font-weight: 500;
    font-size: 1.25rem;
    color: #6b7280;
    margin-bottom: 3.5rem;
    line-height: 1.5;
  }

  .btn-login {
    background-color: #111827;
    color: white;
    font-size: 1.375rem;
    font-weight: 700;
    padding: 1rem 4rem;
    border: none;
    border-radius: 0.75rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0 8px 18px rgb(17 24 39 / 0.2);
    display: inline-block;
    margin-top: 1rem;
  }

  .btn-login:hover,
  .btn-login:focus {
    background-color: #374151;
    outline: none;
    transform: scale(1.05);
  }
</style>

<div class="landing-container" role="main" aria-label="Landing page main content">
  <div class="overlay"></div>

  <div class="content-wrapper">
    <h1 class="title">Empowering Local Industries Through Digital Innovation</h1>
    <p class="subtitle">Kabupaten Nganjuk is home to a growing ecosystem of small-to-medium enterprises (SMEs) in agriculture, food processing, textiles, and handicrafts. This project aims to map, analyze, and empower these industries—bridging the gap between local potential and global reach through industrial-grade web applications.</p>

    <a href="{{ route('login') }}" class="btn-login" role="button" aria-label="Login to system">Login</a>
  </div>
</div>
@endsection
