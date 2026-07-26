<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MEDILOG</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brandMaroon: '#115E59', /* Deep Teal MEDILOG */
                        brandYellow: '#eab308', /* Kuning untuk logo */
                        inputBg: '#e5e7eb',     /* Abu-abu terang untuk input */
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 overflow-hidden">

    <div class="flex min-h-screen">
        
        <div class="hidden lg:block lg:w-3/5 bg-cover bg-left"
        style= "border-right: 1px solid #ededed;">
        <img src="{{ asset('images/login.jpg') }}" alt="login" width="100%">
        </div>

        <div class="w-full lg:w-2/5 flex items-center justify-center bg-[#fafafa] p-8 sm:p-12">
            <div class="w-full max-w-md">
                
                <div class="text-center mb-12">
                    <div class="flex justify-center items-center mb-2">
                        <img src="{{ asset('images/logo.png') }}" alt="logo">
                    </div>
                    <h1 class="text-3xl font-bold tracking-wider text-brandMaroon mt-2 ">MEDI<span class="text-brandYellow">LOG</span></h1>
                </div>

                @if(session('error'))
                    <div class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm font-medium flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf 
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-bold text-gray-500 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full bg-inputBg border-transparent rounded-lg py-3.5 px-4 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200" 
                            required placeholder="Masukkan email anda">
                    </div>

                    <div class="mb-8">
                        <label for="password" class="block text-sm font-bold text-gray-500 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" 
                                class="w-full bg-inputBg border-transparent rounded-lg py-3.5 px-4 pr-12 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200" 
                                required autocomplete="off" placeholder="Masukkan password anda">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brandMaroon cursor-pointer focus:outline-none transition" title="Tampilkan/Sembunyikan Password">
                                <i class="fas fa-eye text-lg" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-brandMaroon text-white font-semibold tracking-wide py-3.5 px-4 rounded-lg hover:bg-teal-800 transition duration-300 shadow-md">
                        Sign In
                    </button>
                </form>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                        eyeIcon.classList.add('text-brandMaroon');
                    } else {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.remove('text-brandMaroon');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }
        });
    </script>

</body>
</html>