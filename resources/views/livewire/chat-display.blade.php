
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

        {{-- search --}}
    <div class="mb-8 relative">
        <div class="relative">
            <input type="text" wire:model="search" wire:keydown.enter="searchChats" placeholder="Search chats"
                class="w-full pl-10 pr-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white">
            @if(strlen($search) > 0)
                <button wire:click="clearSearch" class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m-.5-8a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"></path>
                </svg>
            </span>
        </div>
        @if(session()->has('noResults'))
            <div class="absolute top-full left-0 mt-1">
                <p class="text-red-500">No results found. Please try a different search term.</p>
            </div>
        @endif
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
                    {{ $chats->firstWhere('id', $chatID)->name ?? 'Chat Not Found' }}
                    @else
                        LegaAidPH Conversation
                    @endif
                </h1>

 <!-- Add space between header and messages -->
 <div class="mb-8"></div>

                 <!-- Display messages -->
    @forelse($messages as $key => $message)
    <div class="last-of-type:border-none py-4 flex items-right">
        <!-- Sender's avatar -->
        @if($message['sender'] === 'LegalAidPH')
            <img src="{{ asset('chat.png') }}" alt="LegalAidPH Logo" class="w-8 h-8 rounded-full mr-4">
        @else
            @php
                $words = explode(' ', $message['sender']);
                $initials = '';
                foreach ($words as $word) {
                    $initials .= strtoupper(substr($word, 0, 1));
                }
                $avatarUrl = "https://ui-avatars.com/api/?name={$message['sender']}&background=random&color=ffffff&size=128&rounded=true&bold=true";
            @endphp
            <img src="{{ $avatarUrl }}" alt="User Avatar" class="w-8 h-8 rounded-full mr-4">
        @endif
          <!-- Message content -->
    <div>
        <div class="flex items-center">
            <h2 class="text-2xl dark:text-white">{{ $message['sender'] }}</h2>

        </div>
        <p class="dark:text-white">
            {!! nl2br(e($message['content'])) !!}
        </p>


<!-- Read Aloud button with megaphone icon -->
@if($message['sender'] === 'LegalAidPH')
<button wire:click="readAloud('{{ $message['content'] }}', {{ $key }})" class="text-sm text-blue-500 hover:underline">
    @if(isset($audioPlayers[$key]))
    <svg width="15" height="15" class="icon-md-heavy" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
    @else
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-md-heavy">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M11 4.9099C11 4.47485 10.4828 4.24734 10.1621 4.54132L6.67572 7.7372C6.49129 7.90626 6.25019 8.00005 6 8.00005H4C3.44772 8.00005 3 8.44776 3 9.00005V15C3 15.5523 3.44772 16 4 16H6C6.25019 16 6.49129 16.0938 6.67572 16.2629L10.1621 19.4588C10.4828 19.7527 11 19.5252 11 19.0902V4.9099ZM8.81069 3.06701C10.4142 1.59714 13 2.73463 13 4.9099V19.0902C13 21.2655 10.4142 22.403 8.81069 20.9331L5.61102 18H4C2.34315 18 1 16.6569 1 15V9.00005C1 7.34319 2.34315 6.00005 4 6.00005H5.61102L8.81069 3.06701ZM20.3166 6.35665C20.8019 6.09313 21.409 6.27296 21.6725 6.75833C22.5191 8.3176 22.9996 10.1042 22.9996 12.0001C22.9996 13.8507 22.5418 15.5974 21.7323 17.1302C21.4744 17.6185 20.8695 17.8054 20.3811 17.5475C19.8927 17.2896 19.7059 16.6846 19.9638 16.1962C20.6249 14.9444 20.9996 13.5175 20.9996 12.0001C20.9996 10.4458 20.6064 8.98627 19.9149 7.71262C19.6514 7.22726 19.8312 6.62017 20.3166 6.35665ZM15.7994 7.90049C16.241 7.5688 16.8679 7.65789 17.1995 8.09947C18.0156 9.18593 18.4996 10.5379 18.4996 12.0001C18.4996 13.3127 18.1094 14.5372 17.4385 15.5604C17.1357 16.0222 16.5158 16.1511 16.0539 15.8483C15.5921 15.5455 15.4632 14.9255 15.766 14.4637C16.2298 13.7564 16.4996 12.9113 16.4996 12.0001C16.4996 10.9859 16.1653 10.0526 15.6004 9.30063C15.2687 8.85905 15.3578 8.23218 15.7994 7.90049Z" fill="currentColor"></path>
    </svg>
    @endif
</button>
@endif




        @if($message['sender'] === auth()->user()->name && $key === count($messages) - 2)
            <!-- Edit button for user message -->
            <button wire:click="editLatestUserMessage({{ $message['id'] }})" class="text-sm text-blue-500 hover:underline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-md-heavy"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.2929 4.29291C15.0641 2.52167 17.9359 2.52167 19.7071 4.2929C21.4783 6.06414 21.4783 8.93588 19.7071 10.7071L18.7073 11.7069L11.1603 19.2539C10.7182 19.696 10.1489 19.989 9.53219 20.0918L4.1644 20.9864C3.84584 21.0395 3.52125 20.9355 3.29289 20.7071C3.06453 20.4788 2.96051 20.1542 3.0136 19.8356L3.90824 14.4678C4.01103 13.8511 4.30396 13.2818 4.7461 12.8397L13.2929 4.29291ZM13 7.41422L6.16031 14.2539C6.01293 14.4013 5.91529 14.591 5.88102 14.7966L5.21655 18.7835L9.20339 18.119C9.40898 18.0847 9.59872 17.9871 9.7461 17.8397L16.5858 11L13 7.41422ZM18 9.5858L14.4142 6.00001L14.7071 5.70712C15.6973 4.71693 17.3027 4.71693 18.2929 5.70712C19.2831 6.69731 19.2831 8.30272 18.2929 9.29291L18 9.5858Z" fill="currentColor"></path></svg>
            </button>
        @elseif($message['sender'] === 'LegalAidPH' && $key === count($messages) - 1)

        <!-- Regenerate button for chatbot message -->
            <button wire:click="regenerateLatestBotMessage({{ $message['id'] }})" wire:loading.attr="disabled" class="text-sm text-blue-500 hover:underline relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" class="icon-md-heavy"><path fill="currentColor" d="M3.07 10.876C3.623 6.436 7.41 3 12 3a9.15 9.15 0 0 1 6.012 2.254V4a1 1 0 1 1 2 0v4a1 1 0 0 1-1 1H15a1 1 0 1 1 0-2h1.957A7.15 7.15 0 0 0 12 5a7 7 0 0 0-6.946 6.124 1 1 0 1 1-1.984-.248m16.992 1.132a1 1 0 0 1 .868 1.116C20.377 17.564 16.59 21 12 21a9.15 9.15 0 0 1-6-2.244V20a1 1 0 1 1-2 0v-4a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2H7.043A7.15 7.15 0 0 0 12 19a7 7 0 0 0 6.946-6.124 1 1 0 0 1 1.116-.868"></path></svg>
                <span wire:loading wire:target="regenerateLatestBotMessage({{ $message['id'] }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 text-blue-500 ml-2"  fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" width="15" height="15" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647zM20 12c0-3.042-1.135-5.824-3-7.938l-3 2.647A7.962 7.962 0 0120 12h4zm-6 7.291c1.865-2.114 3-4.896 3-7.938h-4c0 1.576-.5 3.041-1.347 4.245l2.347 2.693z"></path>
                    </svg>
                </span>
            </button>


        @endif

<!-- Audio player -->
@if(isset($audioPlayers[$key]))
<audio controls autoplay>
    <source src="{{ asset($audioPlayers[$key]) }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
@endif


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


