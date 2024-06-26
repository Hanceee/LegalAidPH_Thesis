import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        "./resources/views/livewire/chat-display.blade.php",
        "./resources/views/filament/pages/chat-display.blade.php",
        `resources/css/filament/admin/theme.css`,
        './app/Livewire/**/*.php',
        './resources/views/livewire/**/*.blade.php',

    ],


}
