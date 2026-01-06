<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-green-600 to-purple-600 px-6 py-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
                    <i class="fas fa-key text-3xl text-green-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Reset Password</h1>
                <p class="text-green-100 text-sm">Buat password baru untuk akun Anda</p>
            </div>

            {{-- Form --}}
            <div class="p-6">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    {{-- Display Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                <div class="text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Email Field (Readonly) --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                            Email
                        </label>
                        <input type="email"
                            id="email"
                            value="{{ $email }}"
                            readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                            placeholder="admin@example.com">
                    </div>

                    {{-- Password Field --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Password Baru
                        </label>
                        <input type="password"
                            id="password"
                            name="password"
                            required
                            autofocus
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                            placeholder="Min. 8 karakter">
                    </div>

                    {{-- Password Confirmation Field --}}
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Konfirmasi Password Baru
                        </label>
                        <input type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                            placeholder="Ulangi password baru">
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-green-600 to-purple-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 shadow-md">
                        <i class="fas fa-check-circle mr-2"></i>
                        Reset Password
                    </button>

                    {{-- Back to Login Link --}}
                    <div class="mt-6 text-center">
                        <a href="/" class="text-sm text-green-600 hover:text-green-800 font-medium">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali ke Halaman Utama
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 text-sm text-gray-600">
            <p>Link reset password berlaku selama 60 menit</p>
        </div>
    </div>
</body>
</html>