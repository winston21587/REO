<? ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Download Reo Mobile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="icon" type="image/png" href="{{ asset('images/reoc-nobg.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/reoc-nobg.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400..700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #8B0000;
      --primary-dark: #5c0000;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      background: #ffffff;
      font-family: "Inter", system-ui, -apple-system, sans-serif;
      color: #1e1f2c;
      line-height: 1.5;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2.5rem;
      border-bottom: 1px solid #edf2f7;
      background: white;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-weight: 700;
      font-size: 1.6rem;
      letter-spacing: -0.02em;
      color: var(--primary);
    }
    .logo img {
      height: 1.6rem;
      width: auto;
    }
    .nav-links {
      display: flex;
      gap: 2.5rem;
      align-items: center;
    }
    .nav-links a {
      text-decoration: none;
      color: #2d3748;
      font-weight: 500;
      transition: color 0.15s;
      font-size: 1rem;
    }
    .nav-links a:hover {
      color: var(--primary);
    }
    .nav-download {
      background: var(--primary);
      color: white !important;
      padding: 0.5rem 1.5rem;
      border-radius: 40px;
      font-weight: 600;
      box-shadow: 0 4px 10px rgba(139, 0, 0, 0.25);
    }
    .nav-download:hover {
      background: var(--primary-dark);
    }
    .container {
      max-width: 1300px;
      margin: 0 auto;
      padding: 0 4rem;
    }
    .hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      align-items: center;
      justify-items: center;
      min-height: calc(100vh - 200px);
      padding: 0 3rem;
    }
    .hero-left h1 {
      font-size: 3.4rem;
      font-weight: 700;
      line-height: 1.1;
      letter-spacing: -0.03em;
      color: #1a1e2b;
      margin-bottom: 1rem;
    }
    .hero-left .tagline {
      font-size: 1.25rem;
      color: #4a5568;
      max-width: 90%;
      margin-bottom: 2rem;
      font-weight: 400;
    }
    .hero-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      align-items: center;
    }
    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      background: var(--primary);
      color: white;
      border: none;
      padding: 0.9rem 2.2rem;
      border-radius: 60px;
      font-weight: 600;
      font-size: 1.1rem;
      text-decoration: none;
      box-shadow: 0 8px 18px -6px rgba(139, 0, 0, 0.4);
      transition: all 0.2s ease;
    }
    .btn-primary i {
      color: white;
    }
    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 14px 24px -8px rgba(139, 0, 0, 0.5);
    }
    .hero-right {
      display: flex;
      justify-content: center;
    }
    .screenshot-wrapper {
      max-width: 380px;
      width: 100%;
      filter: drop-shadow(0 20px 28px -12px rgba(0, 0, 0, 0.2));
      transition: transform 0.2s;
    }
    .screenshot-wrapper img {
      display: block;
      width: 100%;
      height: auto;
      background: transparent;
      border-radius: 32px;
    }
    @media (max-width: 900px) {
      .hero {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 2rem;
      }
      .hero-left .tagline {
        max-width: 100%;
      }
      .hero-buttons {
        justify-content: center;
      }
    }
    @media (max-width: 600px) {
      .navbar {
        padding: 1rem 1.2rem;
        flex-wrap: wrap;
        gap: 0.5rem;
      }
      .nav-links {
        gap: 1.2rem;
      }
      .hero-left h1 {
        font-size: 2.8rem;
      }
      .btn-primary {
        width: 100%;
        justify-content: center;
      }
      .container {
        padding: 0 1.2rem;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="logo">
      <img src="{{ asset('images/reoc-nobg.png') }}" alt="REO Mobile Logo">
      <span>REO Mobile</span>
    </div>
    <div class="nav-links">
      <a href="https://reoph.site" style="border-bottom: 1.5px solid #000000;">Learn More</a>
      <a href="https://github.com/charlottedbszx/ReoMobile/releases/download/Apk/Reo.apk" class="nav-download">Download <i class="fas fa-download"></i></a>
    </div>
  </nav>

  <main class="container">
    <div class="hero">
      <div class="hero-left">
        <h1>REO Mobile<br><span style="color:var(--primary);">by SayLess</span></h1>
        <div class="tagline">Your research companion: Simplify submissions, track progress, and get approvals on the go.</div>
        <div class="hero-buttons">
          <a href="https://github.com/charlottedbszx/ReoMobile/releases/download/Apk/Reo.apk" class="btn-primary"><i class="fa-brands fa-android"></i> Download Application</a>
        </div>
      </div>
      <div class="hero-right">
        <div class="screenshot-wrapper">
          <img src="{{ asset('images/mobile-screenshot.png') }}" alt="REO Mobile interface" onerror="this.src='https://placehold.co/400x800/f8fafc/8B0000?text=REO+Mobile&font=inter'; this.style.background='transparent';">
        </div>
      </div>
    </div>
  </main>
</body>
</html>
