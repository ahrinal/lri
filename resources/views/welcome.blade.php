<div class="main-container">
    <div class="message">
        <div class="title">
            <h2>LARAVEL LANGUAGE SWITCH</h2>
        </div>
        <div class="main-message">
            @if(session('language_switched'))
                <p>
                    You have switched to <span class="language">
                        {{ session('language_switched') === 'ar' ? 'Arabic' : (session('language_switched') === 'fr' ? 'French' : 'English') }}
                    </span>
                </p>
            @endif
            <div class="text-message">
                <p>{{ __('messages.welcome') }}</p>
            </div>
        </div>
    </div>
    <div class="switch">
        <form action="{{ route('language.switch') }}" method="POST" class="inline-block">
            @csrf
            <select name="language" onchange="this.form.submit()" class="p-2 rounded bg-gray-100 text-gray-800">
                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>French</option>
                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>Arabic</option>
                <!-- Add more options as needed -->
            </select>
        </form>
    </div>
</div>
