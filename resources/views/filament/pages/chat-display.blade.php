<x-filament-panels::page>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <livewire:chat-display style="overflow: visible;"/>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit="create">
        {{ $this->form }}

        @if(!$waitingForResponse)
            <x-filament::button
                    class="mt-6"
                    type="submit"
                    wire:target="submit">
                Send Message
            </x-filament::button>
        @else
            <div class="mt-6">
                <p>Waiting for AI response...</p>
            </div>
        @endif

        @if($reply)
            <div class="mt-6">
                <p class="font-bold">Your question:</p>
                <p>{{ $lastQuestion }}</p>
                <p class="mt-6 font-bold">AI Response:</p>
                <p>{{ $reply }}</p>
            </div>
        @endif
    </form>
</x-filament-panels::page>
