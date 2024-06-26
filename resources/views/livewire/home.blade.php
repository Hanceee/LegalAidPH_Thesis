<div>
    <!-- resources/views/livewire/home.blade.php -->

    <div class="flex flex-col items-center justify-start h-screen pt-16">
        <div class="flex items-start mb-8">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-32 w-auto">
        </div>

        <div class="mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-black dark:text-gray-200">
                Family Law Expertise at Your Fingertips,<br> Supporting Your Family's Legal Needs.
            </h1>
        </div>

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-lg mb-4 text-lg md:text-xl" wire:click="goToChatDisplay">
            Chat Now
        </button>

        <!-- Video Player -->
        <div class="flex justify-center">
            <video controlsList="nodownload" controls autoplay loop muted class="mt-4" width="640" height="360">
                <source src="{{ asset('video.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>
