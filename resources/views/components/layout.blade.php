<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Employee </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-black text-white font-hanken-grotesk ">

    <div class="px-10">
        <nav class="flex justify-between item-center py-4 border-b border-white">
            <div class="space-x-6 font-bold px-6">
                <a class=" p-4 {{ request()->routeIs('country.*') ? 'text-gray-300' : 'hover:text-purple-500' }}"
                    href="/country">
                    @lang('layout.country')
                </a>
                <a class="{{ request()->routeIs('state.*') ? 'text-gray-300' : 'hover:text-purple-500' }}"
                    href="/state">
                    @lang('layout.state')
                </a>
                <a class="{{ request()->routeIs('city.*') ? 'text-gray-300' : 'hover:text-purple-500' }}" href="/city">
                    @lang('layout.city')
                </a>
                <a class="{{ request()->routeIs('employee.*') ? 'text-gray-300' : 'hover:text-purple-500' }}"
                    href="/employee">
                    @lang('layout.employee')
                </a>
            </div>
            <div class="language-switcher px-5">
                <select id="languageSwitcher" class="form-select form-select-sm bg-black" onchange="changeLanguage(this)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="ur" {{ app()->getLocale() == 'ur' ? 'selected' : '' }}>اردو</option>
                </select>
            </div>
        </nav>

        <main class="mt-10 max-w-[1200px] mx-auto pb-20">
            {{ $slot }}
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function changeLanguage(selectElement) {
            var selectedLang = selectElement.value;
            var url = "{{ route('locale.change', ':lang') }}".replace(':lang', selectedLang);
            window.location.href = url;
        }
    </script>
</body>

