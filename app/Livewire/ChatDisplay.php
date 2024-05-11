<?php

namespace App\Livewire;

use Parsedown;
use App\Models\Chat;
use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;
use Illuminate\Http\File;
use Filament\Tables\Table;
use App\Models\ChatMessage;
use Livewire\Attributes\Url;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Contracts\View\View;
use App\Models\ChatbotConfiguration;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\Factory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use GrahamCampbell\Markdown\Facades\Markdown;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Foundation\Application;

class ChatDisplay extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $file;
    public $transcribedText;
    public $audioPlayers = [];
    public $errorMessage;
    public $messageContent;
    public $audio;
    public array $messages = [];
    #[Url]
    public $recording;
    public $audioFile;
    public $chatID = null;
    public string $suggestedMessage1 = '';
    public string $suggestedMessage2 = '';
    public string $suggestedMessage3 = '';
    public string $suggestedMessage4 = '';
     public $latestUserMessageId;
    public string $newMessage = '';
    private ?Chat $chat;
    public $showFirstColumn = true;
    public bool $sendingMessage = false; // Add a property to track whether a message is being sent
    public $editingChat = null; // New property to store the ID of the chat being edited
    public $editChatName = ''; // New property to store the edited chat name
    public $search = '';



    private function generateSuggestedMessages(): void
    {

        // Fetch user's topic preference from the database
    $user = auth()->user();
    $topic = $user->topic;

    $prompts = [
        "As the Chatbot User give a tagalog suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words pertaining $topic",
        "As the Chatbot User give a suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words involving $topic",
        "As the Chatbot User give a suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words related to $topic",
        "As the Chatbot User give a tagalog suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words with $topic",
    ];


    foreach ($prompts as $index => $prompt) {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

            $suggestedMessage = $result['choices'][0]['message']['content'];

            $suggestedMessage = str_replace('"', '', $suggestedMessage);


         // Assign generated message to the corresponding property in user table
        $propertyName = "message" . ($index + 1);
        $user->{$propertyName} = $suggestedMessage;
    }

    /** @var \App\Models\User $user **/
    $user->save();

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Chat::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        // Generate the user's name based on your logic
        $name = $user->first_name . ' ' . $user->last_name;

        // Make a request to ui-avatars.com to get the avatar
        $response = Http::get('https://ui-avatars.com/api/', [
            'name' => $name,
            'size' => 200, // Adjust the size as needed
            'background' => 'f0f0f0', // Background color
            'color' => '333', // Text color
            'rounded' => true, // Rounded corners
        ]);

        $avatarUrl = $response->json('url');

        return view('your-view', ['avatarUrl' => $avatarUrl]);
    }

    public function toggleFirstColumn()
    {
        $this->showFirstColumn = !$this->showFirstColumn;
    }



    public function mount(): void
    {
        if ($this->chatID) {
            $this->loadChat();
        }
        $this->generateSuggestedMessages();

     // Fetch suggested messages from the database and assign them to public properties
    $this->suggestedMessage1 = auth()->user()->message1;
    $this->suggestedMessage2 = auth()->user()->message2;
    $this->suggestedMessage3 = auth()->user()->message3;
    $this->suggestedMessage4 = auth()->user()->message4;



    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $chats = Chat::query()
            ->where('user_id', auth()->id())
            ->where('is_archived', false) // Exclude archived chats
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->get();
        // Check if there are no search results
        if ($this->search && $chats->isEmpty()) {
            session()->flash('noResults', true);
        } else {
            session()->forget('noResults');
        }

        return view('livewire.chat-display')
            ->with('chats', $chats);
    }

    public function clearSearch()
    {
        $this->search = '';
    }

   // Modify the search method to be triggered on search input change
public function updatedSearch()
{
    $this->searchChats();
}

// Rename the searchChats method to handle the search functionality
public function searchChats()
{
    // No need to do anything here, just re-render the component
    $this->render();
}

    public function sendMessage(): void
{
    // Set sendingMessage to true when sending a message
    $this->sendingMessage = true;

    if (!$this->chatID) {
        $this->createNewChat();
    } else {
        $this->loadChat();
    }

    // Store the user's message in the database
    $this->chat->chatMessages()->create([
        'sender' => auth()->user()->name,
        'content' => $this->newMessage,
    ]);

    // Update the 'updated_at' timestamp of the chat
    $this->chat->touch();

    // Send the user's message and chat history to the API
    $response = Http::timeout(60)->asJson()->post('http://127.0.0.1:5000/api/chat_with_history', [
        'query' => $this->newMessage,
        'chat_history' => $this->messages,
    ])->json();

    $answer = $response['data']['answer'] ?? '';

    // If the answer starts with 'Answer:', remove it
    if (str_starts_with($answer, 'Answer:')) {
        $answer = substr($answer, 7);
    }

    // Remove Markdown symbols
    $answer = str_replace(['*', '_', '~', '`'], '', $answer);

    // Store the AI's response in the database
    $this->chat->chatMessages()->create([
        'sender' => 'LegalAidPH',
        'content' => $answer,
    ]);

    // Refresh messages
    $this->messages = $this->chat->chatMessages()->get()->toArray();

    // Reset new message input
    $this->newMessage = '';

    // Set sendingMessage to false when the response is received
    $this->sendingMessage = false;
}


    public function editLatestUserMessage($id)
    {

        $message = ChatMessage::find($id);
        $this->newMessage = $message->content;
        $this->latestUserMessageId = $id;
    }



   public function regenerateLatestBotMessage($id)
{

    $message = ChatMessage::find($id);
    $this->newMessage = $this->messages[count($this->messages) - 2]['content']; // Set the latest user message as the new message

     // Delete the second to last message
     $secondToLastMessageId = $this->messages[count($this->messages) - 2]['id'];
     ChatMessage::destroy($secondToLastMessageId);

    $message->delete();

    $this->sendMessage();


}

public function startNewChat(): void
{
    $this->chatID = null;
    $this->messages = [];
    $this->audioPlayers = [];

}

public function changeChat($chatID): void
{
    $this->chatID = $chatID;

    // Reset the message input form
    $this->newMessage = '';
    $this->audioPlayers = [];

    $this->loadChat();

}

private function loadChat(): void
{
    $chat = Chat::where('user_id', auth()->id())->with(['chatMessages'])->find($this->chatID);

    if (!$chat) {
        $this->chatID = null;
        return;
    }

    $this->chat = $chat;
    $this->messages = $chat->chatMessages->toArray();


}

private function createNewChat(): void
{

    $prompt = "Make title in 4 words for this conversation using this prompt: {$this->newMessage}. It must be related to Family Law, and mirror the language of the prompt";

    $result = OpenAI::chat()->create([
        'model' => 'gpt-4-turbo',
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);

    $suggestedTitle = $result['choices'][0]['message']['content'];

    $suggestedTitle = str_replace('"', '', $suggestedTitle);


    $this->chat = Chat::create([
        'user_id' => auth()->id(),
        'name' => $suggestedTitle,
    ]);

    $this->chatID = $this->chat->id;
}


    public function archiveChat($chatID)
    {
        $chat = Chat::find($chatID);
        $chat->is_archived = true;
        $chat->save();

        $this->messages = array_filter($this->messages, function ($message) use ($chatID) {
            return $message['id'] !== $chatID;
        });

         // Redirect to the specified URL after archiving the chat
         return redirect()->to('/admin/chat-display')->with('reload', true);
        }

    public function hardDeleteChat($chatID)
    {
        $chat = Chat::find($chatID);
        $chat->delete();

         // Remove the deleted chat from the messages array
    $this->messages = array_filter($this->messages, function ($message) use ($chatID) {
        return $message['id'] !== $chatID;
    });

    return redirect()->to('/admin/chat-display')->with('reload', true);


    }

    public function editChat($chatID)
    {
        // Set the chat ID being edited
        $this->editingChat = $chatID;

        // Load the chat
        $this->chat = Chat::find($chatID);

        // Set the initial value of the edited chat name
        $this->editChatName = $this->chat->name;
    }

    public function updateChat()
    {
        // Find the chat by its ID
        $chat = Chat::find($this->editingChat);

        // Update the chat name
        $chat->name = $this->editChatName;
        $chat->save();

        // Reset the editing state
        $this->editingChat = null;
        $this->editChatName = '';
    }

    public function clickAway()
{
    // Save the edited chat name when clicking away from the input field
    $this->updateChat();
}


// Livewire component


public function readAloud($encodedContent, $index)
    {
        $content = urldecode($encodedContent);

        if (!isset($this->audioPlayers[$index])) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/audio/speech', [
                'model' => 'tts-1-hd',
                'input' => $content,
                'voice' => 'onyx',
            ]);

            if ($response->successful()) {
                file_put_contents("speech_$index.mp3", $response->body());
                $this->audioPlayers[$index] = "speech_$index.mp3";
            } else {
                // Handle error
                $this->addError('speech_generation', 'Failed to generate speech.');
            }
        } else {
            // Remove audio file and player if clicked again
            unlink($this->audioPlayers[$index]);
            unset($this->audioPlayers[$index]);
        }
    }


    public function updatedFile($file)
    {
        $this->validate([
            'file' => 'required|file|mimes:mp3',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => 'whisper-1',
                'response_format' => 'text',
            ]);

            $this->newMessage = $response->body();
            $this->errorMessage = null;
        } catch (\Exception $e) {
            $this->newMessage = null;
            $this->errorMessage = $e->getMessage();
        }
    }



}
