<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <title>{{ $title ?? config('app.name', 'Laravel') . ' Voting' }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <livewire:styles />

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans bg-gray-background text-gray-900 text-sm">
        <header class="flex flex-col md:flex-row items-center justify-between px-8 py-4">
            <a href="/"><img class="w-20" src="{{ asset('img/vote.png') }}" alt="logo"></a>

            <div class="w-200 h-100 flex items-center justify-center">
                <div class="max-w-7xl mx-auto text-center" x-data="{
                        text: '',
                        textArray : ['Share with us Your Amazing Ideas.', 'It is Truly Awesome!', 'You Have to Try It!'],
                        textIndex: 0,
                        charIndex: 0,
                        pauseEnd: 1500,
                        cursorSpeed: 420,
                        pauseStart: 20,
                        typeSpeed: 110,
                        direction: 'forward'
                     }" x-init="(() => {

                        let typingInterval = setInterval(startTyping, $data.typeSpeed);

                     function startTyping(){
                        let current = $data.textArray[ $data.textIndex ];
                        if($data.charIndex > current.length){
                             $data.direction = 'backward';
                             clearInterval(typingInterval);
                             setTimeout(function(){
                                typingInterval = setInterval(startTyping, $data.typeSpeed);
                             }, $data.pauseEnd);
                        }

                        $data.text = current.substring(0, $data.charIndex);

                         if($data.direction == 'forward'){
                            $data.charIndex += 1;
                         } else {
                            if($data.charIndex == 0){
                                $data.direction = 'forward';
                                clearInterval(typingInterval);
                                setTimeout(function(){
                                    $data.textIndex += 1;

                                    if($data.textIndex >= $data.textArray.length){
                                        $data.textIndex = 0;
                                    }

                                    typingInterval = setInterval(startTyping, $data.typeSpeed);
                                }, $data.pauseStart);
                            }
                            $data.charIndex -= 1;
                         }
                     }

                     setInterval(function(){
                        if($refs.cursor.classList.contains('hidden')){
                            $refs.cursor.classList.remove('hidden');
                        } else {
                            $refs.cursor.classList.add('hidden');
                        }
                     }, $data.cursorSpeed);
                     })()">
                    <span class="relative h-auto">
                        <h1 class="font-mono text-5xl text-slate-500 font-" x-text="text"></h1>
                        <span class="absolute right-0 top-0 h-full w-4 -mr-8 bg-slate-500" x-ref="cursor"></span>
                    </span>
                </div>
            </div>

            <div class="flex items-center mt-2 md:mt-0">
                @if (Route::has('login'))
                    <div class="px-6 py-4">
                        @auth
                            <div class="flex items-center space-x-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log out') }}
                                    </a>
                                </form>

                                <livewire:comment-notifications />
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif

                @auth
                    <a href="#">
                        <img src="{{ auth()->user()->getAvatar() }}" alt="avatar" class="w-10 h-10 rounded-full">
                    </a>
                @endauth
            </div>
        </header>

        <main class="container mx-auto max-w-custom flex flex-col md:flex-row">
            <div class="w-70 mx-auto md:mx-0 md:mr-5">
                <div class="bg-white md:top-8 md:sticky border-2 border-blue rounded-xl mt-16"
                style="
                    border-image-source: linear-gradient(to bottom, rgba(50, 138, 241, 0.22), rgba(99, 123, 255, 0));
                    border-image-slice: 1;
                    background-image: linear-gradient(to bottom, #ffffff, #ffffff), linear-gradient(to bottom, rgba(50, 138, 241, 0.22), rgba(99, 123, 255, 0));
                    background-origin: border-box;
                    background-clip: content-box, border-box;
                "
                >
                    <div class="text-center px-6 py-2 pt-6">
                        <h3 class="font-semibold text-base">Add an Idea</h3>
                        <p class="text-xs mt-4">
                            @auth
                                Let us know what you would like, and we'll take a look over!
                            @else
                                Please login to create an idea.
                            @endauth
                        </p>
                    </div>

                    <livewire:create-idea />
                </div>
            </div>
            <div class="w-full px-2 md:px-0 md:w-175">
                <livewire:status-filters />

                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
        </main>

        @if (session('success_message'))
            <x-notification-success
                :redirect="true"
                message-to-display="{{ (session('success_message')) }}"
            />
        @endif

        @if (session('error_message'))
            <x-notification-success
                type="error"
                :redirect="true"
                message-to-display="{{ (session('error_message')) }}"
            />
        @endif

        <livewire:scripts />
    </body>
</html>
