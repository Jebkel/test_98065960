<header class="bg-gray-800 py-4">
    <div class="container mx-auto flex items-center justify-between">
        <a href="{{ route('index') }}"
           class="text-white text-lg font-semibold">
            {{ request()->get('city')->name }}</a>
        <div class="flex justify-center"> <!-- Добавленный класс justify-center -->
            <a href="{{ route('index') }}" class="text-white text-lg font-semibold mx-4">Главная</a>
            <!-- Пример ссылки -->
            <a href="{{ route('about') }}" class="text-white text-lg font-semibold mx-4">О нас</a>
            <!-- Пример ссылки -->
            <a href="{{ route('news') }}" class="text-white text-lg font-semibold mx-4">Новости</a>
            <!-- Пример ссылки -->
        </div>
        <div class="text-white text-lg font-semibold content-center">
            <img src="https://laravel.com/img/logomark.min.svg" alt="лого" class="h-12">
        </div>
    </div>
</header>
