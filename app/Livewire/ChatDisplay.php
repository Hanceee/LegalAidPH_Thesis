<?php

namespace App\Livewire;

use Parsedown;
use App\Models\Chat;
use App\Models\User;
use Livewire\Component;
use Livewire\Redirector;
use Filament\Tables\Table;
use App\Models\ChatMessage;
use Livewire\Attributes\Url;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Contracts\View\View;
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

    public array $messages = [];
    #[Url]
    public $chatID = null;
    // public string $suggestedMessage1 = 'Suggested Message 1';
    // public string $suggestedMessage2 = 'Suggested Message 2';
    // public string $suggestedMessage3 = 'Suggested Message 3';
    // public string $suggestedMessage4 = 'Suggested Message 4';
    // public string $suggestedMessage5 = 'Suggested Message 5';
    // public string $suggestedMessage6 = 'Suggested Message 6';

    public string $suggestedMessage1 = '';
    public string $suggestedMessage2 = '';
    public string $suggestedMessage3 = '';
    public string $suggestedMessage4 = '';
    // public string $suggestedMessage5 = '';
    // public string $suggestedMessage6 = '';

    public string $newMessage = '';
    private ?Chat $chat;
    public $showFirstColumn = true;
    public bool $sendingMessage = false; // Add a property to track whether a message is being sent
    public $editingChat = null; // New property to store the ID of the chat being edited
    public $editChatName = ''; // New property to store the edited chat name


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
        // $this->generateSuggestedMessages();



    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $chats = Chat::query()
            ->where('user_id', auth()->id())
            ->where('is_archived', false) // Exclude archived chats
            ->latest()
            ->get();


        return view('livewire.chat-display')
            ->with('chats', $chats);
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
        $request = Http::timeout(60)->asJson()
        // Hardcoded because it's easy :)
            ->post('http://127.0.0.1:5000/api/chat_with_history', [
                'query' => $this->newMessage,
                'chat_history' => $this->messages,
            ])
            ->json();

            if (str_starts_with($request['data']['answer'], 'Answer:')) {
                $request['data']['answer'] = substr($request['data']['answer'], 7);
            }

            // Remove Markdown symbols
            $request['data']['answer'] = str_replace(['*', '_', '~', '`'], '', $request['data']['answer']);


            $this->chat->chatMessages()->create([
                'sender' => 'LegalAidPH',
                'content' => $request['data']['answer'],
            ]);

            $this->messages = $this->chat->chatMessages()->get()->toArray();

            $this->newMessage = '';

              // Set sendingMessage to false when the response is received
        $this->sendingMessage = false;
    }

public function startNewChat(): void
{
    $this->chatID = null;
    $this->messages = [];

}

public function changeChat($chatID): void
{
    $this->chatID = $chatID;

    // Reset the message input form
    $this->newMessage = '';

    $this->loadChat();

}

private function generateSuggestedMessages(): void
{



    // Define the prompts
    $prompts = [
        "As the Chatbot User give a suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words tagalog",
        "As the Chatbot User give a suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words",
        "As the Chatbot User give a suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words",
        "As the Chatbot User give a suggested message prompt to a Filipino family law lawyer chatbot named LegalAidPH in 20 words in tagalog",
    ];


    foreach ($prompts as $index => $prompt) {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $suggestedMessage = $result['choices'][0]['message']['content'];

        $suggestedMessage = str_replace('"', '', $suggestedMessage);


        // Assign generated message to the corresponding property
        $propertyName = "suggestedMessage" . ($index + 1);
        $this->{$propertyName} = $suggestedMessage;
    }

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

}
