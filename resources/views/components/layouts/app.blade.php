<!doctype html>
<html class="h-full bg-white dark:bg-gray-900" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'SecPal' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full"
      x-data="{
        showTimeoutModal: false,
        showLogoutModal: false,
        timeoutInterval: null,
        countDownValue: 60,
        timeoutValue: 240,
        timePassed: Math.floor((Date.now() - (parseInt(localStorage.getItem('lastAction')) || Date.now())) / 1000),

        // If timeout is exceeded on load, show modal or log out.
        shouldActOnLoad: function() {
            if(this.timePassed > this.timeoutValue) {
                if(this.timePassed > (this.timeoutValue + this.countDownValue)){
                    this.$dispatch('idle-timeout');
                }else{
                    this.showTimeoutModal = true;
                }
            }
        },

        startTimer: function() {
            // If localStorage is empty, set lastAction to now
            if(!localStorage.getItem('lastAction')) {
                localStorage.setItem('lastAction', Date.now());
            }

            this.shouldActOnLoad();

            this.timeoutInterval = setInterval(() => {
                this.timePassed = Math.floor((Date.now() - parseInt(localStorage.getItem('lastAction'))) / 1000);
                if(this.timePassed > this.timeoutValue){
                    this.showTimeoutModal = true;
                    if(this.timePassed > (this.timeoutValue + this.countDownValue)){
                        this.showTimeoutModal = false;
                        this.showLogoutModal = true;
                        this.$dispatch('idle-timeout');
                    }
                }
            }, 1000);
        },
        resetTimer: function() {
            localStorage.setItem('lastAction', Date.now());
            clearInterval(this.timeoutInterval);
            this.timePassed = 0;
            this.showTimeoutModal = false;
            this.startTimer();
        }
      }"
      x-init="
      () => {
            startTimer();
            window.addEventListener('idle-timeout', () => {
                showLogoutModal = true
            });
            window.addEventListener('showLogoutModal', () => {
                showLogoutModal = true
            });
      }"
      @mousemove="resetTimer"
      @keydown.window="resetTimer"
      @scroll.window="resetTimer">

{{ $slot }}

<!-- Timeout Modal -->
<div x-show="showTimeoutModal"
     style="display: none"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 dark:bg-gray-800 dark:bg-opacity-80">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-5 sm:p-10 mx-2 sm:mx-0 sm:w-1/3">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-5">{{ __('Are you still there?') }}</h2>
        <p class="text-gray-900 dark:text-gray-300 mb-5"
           x-text="(countDownValue - (timePassed - timeoutValue)) + ' {{ __('second(s) remain to auto logout. Move your mouse or press any key to cancel.') }}'"></p>
    </div>
</div>

<!-- Logout Modal -->
<div x-show="showLogoutModal"
     style="display: none"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-95 dark:bg-gray-800 dark:bg-opacity-95">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-5 sm:p-10 mx-2 sm:mx-0 sm:w-1/3">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-5">{{ __('You are going to be logged out!') }}</h2>
    </div>
</div>

<livewire:auth.logout/>
@livewireScripts
</body>
</html>
