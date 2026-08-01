<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-extrabold text-gray-900">إنشاء حساب جديد</h2>
        <p class="text-sm text-gray-500 mt-2">انضم إلى منصة المُساندة الطبية</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل</label>
            <div class="relative">
                <i class="fas fa-user absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full pr-11 pl-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition"
                    placeholder="الاسم الكامل">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
            <div class="relative">
                <i class="fas fa-envelope absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full pr-11 pl-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition"
                    placeholder="example@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور</label>
            <div class="relative">
                <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                    class="w-full pr-11 pl-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">تأكيد كلمة المرور</label>
            <div class="relative">
                <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                    class="w-full pr-11 pl-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-gradient-to-l from-teal-600 to-cyan-600 text-white font-bold py-3.5 rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span>إنشاء الحساب</span>
            </button>
        </div>

        <div class="text-center pt-2">
            <a class="text-sm text-gray-600 hover:text-teal-600 font-semibold transition" href="{{ route('login') }}">
                لديك حساب بالفعل؟ تسجيل الدخول
            </a>
        </div>
    </form>
</x-guest-layout>
