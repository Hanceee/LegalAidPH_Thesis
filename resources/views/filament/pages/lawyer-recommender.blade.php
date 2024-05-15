<x-filament-panels::page>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
    <form wire:submit="create" class="max-w-md mx-auto">

        {{ $this->form }}

        @if(!$waitingForResponse)
            <x-filament::button class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded dark:bg-gray-800 dark:hover:bg-gray-700" type="submit" wire:target="submit">
                Send Message
            </x-filament::button>
        @else
            <div class="mt-6">
                <p class="text-gray-500 dark:text-gray-300">Waiting for AI response...</p>
            </div>
        @endif

        @if($reply)
            <div class="response mt-6 bg-gray-100 dark:bg-gray-800 p-4 rounded">
                <p class="font-bold">Your question:</p>
                <p>{{ $lastQuestion }}</p>
                <p class="mt-4 font-bold">AI Response:</p>
                <p>{{ $reply }}</p>
            </div>
        @endif

    </form>




    <div class="mt-6">
        {{ $this->table }}
    </div>

    {{-- <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <button id="micButton" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Start Recording</button>

            <div id="recordingControls" class="hidden flex-col items-center mt-4">
                <p id="recordingTime" class="text-gray-700">Recording: 0s</p>
                <div class="flex space-x-2 mt-2">
                    <button id="cancelButton" class="bg-red-500 text-white px-4 py-2 rounded-lg">Cancel</button>
                    <button id="finishButton" class="bg-green-500 text-white px-4 py-2 rounded-lg">Finish</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const micButton = document.getElementById('micButton');
        const recordingControls = document.getElementById('recordingControls');
        const recordingTime = document.getElementById('recordingTime');
        const cancelButton = document.getElementById('cancelButton');
        const finishButton = document.getElementById('finishButton');

        let mediaRecorder;
        let audioChunks = [];
        let startTime;
        let timerInterval;
        let mediaStream;

        micButton.addEventListener('click', async () => {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                try {
                    mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(mediaStream, { mimeType: 'audio/webm' });
                    mediaRecorder.start();

                    startTime = Date.now();
                    timerInterval = setInterval(() => {
                        const elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                        recordingTime.textContent = `Recording: ${elapsedTime}s`;
                    }, 1000);

                    recordingControls.classList.remove('hidden');
                    micButton.classList.add('hidden');

                    mediaRecorder.ondataavailable = event => {
                        audioChunks.push(event.data);
                    };
                } catch (error) {
                    console.error('Error accessing microphone:', error);
                }
            }
        });

        cancelButton.addEventListener('click', () => {
            mediaRecorder.stop();
            clearInterval(timerInterval);
            stopMediaStream();
            resetRecordingUI();
            audioChunks = [];
        });

        finishButton.addEventListener('click', () => {
            mediaRecorder.stop();
            clearInterval(timerInterval);

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const audioUrl = URL.createObjectURL(audioBlob);
                const downloadLink = document.createElement('a');
                downloadLink.href = audioUrl;
                downloadLink.download = 'recording.webm';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);

                stopMediaStream();
                resetRecordingUI();
                audioChunks = [];
            };
        });

        function stopMediaStream() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
                mediaStream = null;
            }
        }

        function resetRecordingUI() {
            recordingControls.classList.add('hidden');
            micButton.classList.remove('hidden');
            recordingTime.textContent = 'Recording: 0s';
        }
    </script> --}}
</div>
</div>
</div>
</div>
</x-filament-panels::page>
