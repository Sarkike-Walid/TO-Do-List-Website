<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance — Lumido</title>
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f7f5f0; color: #2a2a2a; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; text-align: center; }
        .container { max-width: 600px; padding: 40px; background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        h1 { font-family: 'Cormorant Garamond', serif; font-size: 36px; margin-bottom: 20px; font-weight: 400; color: #1a1a1a; }
        p { color: #888; font-size: 16px; line-height: 1.6; }
        .announcement { background: rgba(196,168,130,0.1); border-left: 4px solid #c4a882; padding: 15px 20px; margin-top: 30px; border-radius: 4px; text-align: left; color: #8a6d4a; font-size: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>We'll be right back!</h1>
        <p>Lumido is currently undergoing scheduled maintenance to bring you a better experience. Please check back shortly.</p>
        @if(!empty($announcement))
        <div class="announcement">
            <strong>Admin Message:</strong><br><br>
            {{ $announcement }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 40px;">
            @csrf
            <button type="submit" style="background: #1a1a1a; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-family: inherit; font-size: 14px; font-weight: 500;">Return to Homepage</button>
        </form>
    </div>
</body>
</html>
