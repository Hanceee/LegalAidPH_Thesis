
<div>


    <!-- Grid layout for columns -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Column 1 -->
        <div class="col-span-1 @if(!$showFirstColumn) hidden @endif">
            <!-- Start new chat button -->
            <div class="mb-8 border-b-2 border-gray-400 pb-8">
                <button wire:click="startNewChat"
                        class="p-4 text-center dark:text-white dark:hover:bg-blue-900 hover:bg-blue-200 bg-blue-300 dark:bg-blue-700 rounded-xl w-full">
                    Start a new chat
                </button>
            </div>

@php
    $todayDividerDisplayed = false;
    $yesterdayDividerDisplayed = false;
    $thisWeekDividerDisplayed = false;
    $previous7DividerDisplayed = false;
    $previous30DividerDisplayed = false;
    $sortedChats = $chats->where('is_archived', false)->sortByDesc('updated_at');

@endphp
<!-- List of chats -->
@foreach($sortedChats as $chat)

    @php
        // Get the date of the chat
        $chatDate = date('Y-m-d', strtotime($chat->updated_at));
        // Get today's date
        $todayDate = date('Y-m-d');
        // Calculate the difference in days
        $daysDifference = floor(strtotime($todayDate) - strtotime($chatDate)) / (60 * 60 * 24);
    @endphp

    <!-- Check if the chat is from today -->
    @if($daysDifference == 0 && !$todayDividerDisplayed)
        <div class="text-left text-sm text-gray-500 dark:text-gray-400 mt-4 mb-2">Today</div>
        @php $todayDividerDisplayed = true; @endphp
    <!-- Check if the chat is from yesterday -->
    @elseif($daysDifference == 1 && !$yesterdayDividerDisplayed)
        <div class="text-left text-sm text-gray-500 dark:text-gray-400 mt-4 mb-2">Yesterday</div>
        @php $yesterdayDividerDisplayed = true; @endphp
    <!-- Check if the chat is from this week -->
    @elseif($daysDifference > 1 && date('W', strtotime($todayDate)) == date('W', strtotime($chatDate)) && !$thisWeekDividerDisplayed)
        <div class="text-left text-sm text-gray-500 dark:text-gray-400 mt-4 mb-2">This Week</div>
        @php $thisWeekDividerDisplayed = true; @endphp
    <!-- Check if the chat is from the previous 7 days -->
    @elseif($daysDifference > 1 && $daysDifference <= 7 && !$previous7DividerDisplayed)
        <div class="text-left text-sm text-gray-500 dark:text-gray-400 mt-4 mb-2">Previous 7 Days</div>
        @php $previous7DividerDisplayed = true; @endphp
    <!-- Check if the chat is from the previous 30 days -->
    @elseif($daysDifference > 7 && $daysDifference <= 30 && !$previous30DividerDisplayed)
        <div class="text-left text-sm text-gray-500 dark:text-gray-400 mt-4 mb-2">Previous 30 Days</div>
        @php $previous30DividerDisplayed = true; @endphp
    @endif

    <div class="flex items-center justify-between relative" x-data="{ isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
    @if($editingChat === $chat->id)
        <!-- Edit Chat Name -->
        <input type="text" wire:model="editChatName" wire:keydown.enter="updateChat" wire:click.away="clickAway" class="w-full p-4 mb-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:text-white">
    @else
        <!-- Button 1 -->
        <button class="w-full p-4 text-left dark:text-white @if($chatID === $chat->id) bg-green-200 hover:bg-green-300 dark:hover:bg-green-800 dark:bg-green-600 @else bg-gray-200 hover:bg-gray-300 dark:hover:bg-gray-800 dark:bg-gray-600 @endif rounded-xl mb-4" wire:click="changeChat({{ $chat->id }})">
            {{ $chat->name }}
        </button>
            <!-- Button 2 -->
    <div x-data="{ open: false, deleteConfirmation: false }" class="absolute top-0 right-0">
        <button @click="open = !open" class="text-gray-500 dark:text-gray-100 dark:hover:text-gray-500 hover:text-gray-700 focus:outline-none @if($chatID !== $chat->id) opacity-20 dark:opacity-10 @endif hover:opacity-75 dark:hover:opacity-100">
            <svg class="h-14 w-11" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="8" cy="12" r="1" fill="currentColor" />
                <circle cx="12" cy="12" r="1" fill="currentColor" />
                <circle cx="16" cy="12" r="1" fill="currentColor" />
            </svg>
        </button>
        <!-- Button 2's dropdown menu -->
        <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white border dark:bg-gray-900 dark:border-white border-gray-200 rounded-lg shadow-md z-10 origin-right bottom-1">
            <button wire:click="editChat({{ $chat['id'] }}); open = false;" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 w-full text-left">Rename</button>
            <button @click="deleteConfirmation = true; open = false;" class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 w-full text-left">Delete</button>
            <button wire:click="archiveChat({{ $chat['id'] }}); open = false; " class="block px-4 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 w-full text-left">Archive</button>
        </div>
        <!-- Delete Confirmation Popup -->
        <div x-show="deleteConfirmation" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <p class="mb-2 text-black dark:text-white">Are you sure you want to delete <strong>{{ $chat['name'] }}</strong>?</p>
                <div class="flex justify-end">
                    <button @click="deleteConfirmation = false" class="mr-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</button>
                    <button wire:click="hardDeleteChat({{ $chat['id'] }}); deleteConfirmation = false; reloadPage();" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endforeach

{{-- @if(session('reload'))
<script>
    // Reload the page twice
    window.location.reload();
</script>
@endif --}}




        </div>

        <!-- Column 2 -->
        <div class="col-span-2 @if(!$showFirstColumn) col-span-3 @endif">
            <!-- Show/hide history button -->
            <div class="mb-8 pb-1">
                <button wire:click="toggleFirstColumn" class="mt-4 hover:bg-gray-400 dark:hover:bg-gray-800 text-gray-800 dark:text-white font-bold py-2 px-4 rounded">
                    @if($showFirstColumn)
                        <
                    @else
                        >
                    @endif
                </button>
                <h1 class="text-3xl font-bold dark:text-white">
                    @if($chatID)
                        {{ $chats->where('id', $chatID)->first()['name'] }}
                    @else
                        LegaAidPH Conversation
                    @endif
                </h1>


                <!-- Display messages -->
                @forelse($messages as $message)
                    <div class=" last-of-type:border-none py-4 flex items-right">
                        <!-- Sender's avatar -->
                        @if($message['sender'] === 'LegalAidPH')
                            <img src="{{ asset('chat.png') }}" alt="LegalAidPH Logo" class=" w-8 h-8 rounded-full mr-4 ">
                        @else
                            @php
                                // Split the sender's name into words
                                $words = explode(' ', $message['sender']);
                                // Initialize an empty string to store initials
                                $initials = '';
                                // Loop through each word
                                foreach ($words as $word) {
                                    // Append the first letter of each word to initials
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                                // Construct the URL for the avatar using the initials
                                $avatarUrl = "https://ui-avatars.com/api/?name={$message['sender']}&background=random&color=ffffff&size=128&rounded=true&bold=true";
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="User Avatar" class="w-8 h-8 rounded-full mr-4">
                        @endif
                        <!-- Message content -->
                        <div>
                            <h2 class="text-2xl dark:text-white">{{ $message['sender'] }}</h2>
                            <p class="dark:text-white">
                                {!! nl2br(e($message['content'])) !!}
                            </p>
                        </div>
                    </div>
                @empty
                     <!-- Displayed when no messages -->
                     <div class="py-20 text-center dark:text-white">
                        <div class="flex items-center justify-center mb-4">
                            <img src="{{ asset('chat.png') }}" alt="Logo" class="w-24 h-24 rounded-full">
                        </div>
                        <p class="text-xl font-semibold">Kumusta! Family law, dito tayo.</p>
                    </div>
                @endforelse
            </div>

            <!-- Check if there are messages displayed -->
            @if (empty($messages))
                     <!-- Grid layout for suggested messages with 4 boxes -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                @for ($i = 1; $i <= 4; $i++)
                    <!-- Suggested Message Box {{ $i }} -->
                    <div class="relative border border-gray-300 dark:border-gray-600 p-4 rounded-md opacity-90 hover:bg-gray-200 dark:hover:bg-gray-700 flex flex-col justify-between" onmouseover="toggleSubmitButton(this, true)" onmouseout="toggleSubmitButton(this, false)">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Suggested Message:</p>
                            <p class="text-base text-gray-800 dark:text-gray-300">{{ ${"suggestedMessage$i"} }}</p>
                        </div>
                            <!-- Submit Button Icon -->
                        <button class="absolute top-1/2 transform -translate-y-1/2 right-2 bg-gray-300 dark:bg-gray-700 rounded-lg p-2 text-gray-800 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-600 submit-button hidden" onclick="sendMessageFromSuggestion('{{ ${"suggestedMessage$i"} }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </button>
                    </div>
                @endfor
            </div>
        @endif


<script>
    function toggleSubmitButton(element, show) {
        const submitButton = element.querySelector('.submit-button');
        submitButton.classList.toggle('hidden', !show);
    }

    function sendMessageFromSuggestion(message) {
        // Set the value of the message input form to the suggested message
        document.getElementById('messageInput').value = message;
        // Trigger an input event on the message input form to activate the submit button
        document.getElementById('messageInput').dispatchEvent(new Event('input'));
        document.getElementById('submitButton').click();
    }

</script>





      <!-- Message input form -->
<form wire:submit.prevent="sendMessage" class="relative">
    <textarea id="messageInput"
              wire:model="newMessage"
              class="w-full rounded-lg pr-10 dark:bg-gray-800 dark:text-white"
              rows="2"
              oninput="autoExpand(this)"
              placeholder="Message LegalAidPH..."
              style="resize: none; overflow: hidden;"></textarea>

    <!-- Loading icon -->
    <div id="loadingIcon" wire:loading wire:target="sendMessage" class="absolute inset-y-0 right-2 top-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647zM20 12c0-3.042-1.135-5.824-3-7.938l-3 2.647A7.962 7.962 0 0120 12h4zm-6 7.291c1.865-2.114 3-4.896 3-7.938h-4c0 1.576-.5 3.041-1.347 4.245l2.347 2.693z"></path>
        </svg>
    </div>

    <!-- Submit button -->
    <button id="submitButton" type="submit" wire:loading.remove class="absolute right-2 top-1.5 cursor-not-allowed bg-gray-400 text-white font-bold rounded-lg p-1" disabled>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

</form>


<script>
    const messageInput = document.getElementById('messageInput');
    const loadingIcon = document.getElementById('loadingIcon');
    const submitButton = document.getElementById('submitButton');

    // Function to enable/disable submit button and loading icon based on textarea content
    function handleFormValidity() {
        if (messageInput.value.trim() === '') {
            submitButton.disabled = true;
            submitButton.classList.add('cursor-not-allowed', 'bg-gray-400');
            submitButton.classList.remove('bg-blue-500', 'hover:bg-blue-700', 'dark:bg-blue-700', 'dark:hover:bg-blue-900');
            loadingIcon.style.display = 'none';
        } else {
            submitButton.disabled = false;
            submitButton.classList.remove('cursor-not-allowed', 'bg-gray-400');
            submitButton.classList.add('bg-blue-500', 'hover:bg-blue-700', 'dark:bg-blue-700', 'dark:hover:bg-blue-900');
            loadingIcon.style.display = 'block';
        }
    }

    // Call the function initially and listen for input events on the textarea
    handleFormValidity();
    messageInput.addEventListener('input', handleFormValidity);

      // Listen for Enter key press event
      messageInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            // Prevent default form submission
            event.preventDefault();
            // Submit the form
            submitButton.click();
        }
    });

</script>




            <!-- JavaScript for auto-expanding textarea -->
            <script>
                function autoExpand(textarea) {
                    // Reset the height to auto to ensure that it will grow with content
                    textarea.style.height = 'auto';
                    // Set the height of the textarea to its scroll height
                    textarea.style.height = textarea.scrollHeight + 'px';
                }
            </script>
        </div>
    </div>
    {{-- <div wire:ignore.self class="fixed bottom-4 right-1/2 transform -translate-x-1/2 z-50" x-data="{ scrollToBottom() { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }); }, isScrolledUp: false }" x-init="window.addEventListener('scroll', () => { isScrolledUp = window.scrollY > 0; })">
        <button x-show="isScrolledUp" x-cloak @click="scrollToBottom" class="bg-transparent hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-md transition-opacity duration-300" :class="{ 'opacity-0': window.innerHeight + window.scrollY >= document.body.offsetHeight }">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </button>
    </div> --}}

</div>


