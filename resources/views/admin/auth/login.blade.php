<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Cairo', sans-serif;
            background-color: #131a2b;
            overflow: hidden;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 900px;
            height: 550px;
            display: flex;
            background: #1e2230;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .login-form-side {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-info-side {
            flex: 1;
            background: linear-gradient(135deg, #00d2ff 0%, #0077b6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group input {
            width: 100%;
            background: #eef2f7;
            border: none;
            padding: 18px 50px 18px 15px;
            border-radius: 15px;
            outline: none;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            transition: all 0.3s;
        }

        .input-group i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #00d2ff;
        }

        .login-btn {
            background: #00d2ff;
            color: white;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 10px 20px rgba(0, 210, 255, 0.3);
            transition: all 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 210, 255, 0.4);
        }

        .logo-box {
            width: 60px;
            height: 75px;
            border: 2px solid white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 35px;
            font-weight: 900;
            margin: 0 5px;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                height: auto;
                max-width: 400px;
                margin: 20px;
            }
            .login-info-side {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>

    <div class="login-card">
        <!-- Form Side -->
        <div class="login-form-side">
            <h1 class="text-3xl font-black text-[#00d2ff] mb-10 text-center">تسجيل الدخول</h1>

            @if($errors->any())
            <div class="bg-red-500/10 text-red-500 p-4 rounded-2xl mb-6 text-xs font-bold leading-relaxed border border-red-500/20">
                <i class="fa-solid fa-circle-exclamation ml-2"></i>
                @foreach($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-2">
                @csrf
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="admin@example.com">
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2 mb-8 px-1">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded bg-white/10 border-none">
                    <label for="remember" class="text-sm text-gray-400 font-bold cursor-pointer">تذكرني</label>
                </div>

                <button type="submit" class="login-btn">
                    تسجيل الدخول
                </button>
            </form>
        </div>

        <!-- Info Side -->
        <div class="login-info-side">
            <div class="flex items-center">
                <div class="logo-box">أ</div>
                <div class="logo-box">ف</div>
                <div class="logo-box">ش</div>
                <div class="logo-box">ا</div>
                <div class="logo-box">ت</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 120, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#00d2ff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5, "random": false },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#00d2ff", "opacity": 0.4, "width": 1 },
                "move": { "enable": true, "speed": 3.5, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" }, "resize": true },
                "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 1 } }, "push": { "particles_nb": 4 } }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>
