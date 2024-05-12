<?php

namespace App\Filament\Pages;

use Exception;
use App\Models\Chat;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Services\GPTEngine;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use function PHPSTORM_META\map;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;

use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\ArchiveResource;
use Filament\Forms\Concerns\InteractsWithForms;

class ChatDisplay extends Page
{
    use InteractsWithForms;


    public ?array $data = [];
    public bool $waitingForResponse = false;
    public string $reply = '';
    public string $lastQuestion = '';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'LegalAidPH Chat';
    protected ?string $heading = 'Chat Display';
    protected static string $view = 'filament.pages.chat-display';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('message')
                    ->required()
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $this->reply = '';
        $this->waitingForResponse = true;
        $message = $this->form->getState()['message'];
        $this->data['message'] = '';

        $this->dispatch('queryAI', $message);
    }

    #[On('queryAI')]
    public function queryAI($message)
    {
        try {
            $this->reply = (new GPTEngine())->ask($message);
        } catch (Exception $e) {
            info($e->getMessage());
            $this->reply = 'Sorry, the AI assistant was unable to answer your question. Please try to rephrase your question.';
            $this->data['message'] = $this->lastQuestion;
        }
        $this->lastQuestion = $message;
        $this->waitingForResponse = false;
    }

    protected function getHeaderActions(): array
{

    return [



        ActionGroup::make([

            Action::make('Manage Archived Chats')
            ->url(fn (): string => ArchiveResource::getUrl())
            ->openUrlInNewTab()
            ->icon('heroicon-o-archive-box')

                ,
                Action::make('Archive all chats')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->action(function () {
                        $user = auth()->user();
                        Chat::where('user_id', $user->id)->update(['is_archived' => 1]);
                    }),
                Action::make('Delete all chats')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(function () {
                        $user = auth()->user();
                        Chat::where('user_id', $user->id)->delete();
                    }),
            ])
            ->label('Settings')
            ->icon('heroicon-o-cog-6-tooth')
            ->size(ActionSize::Small)
            ->color('primary')
            ->button(),

            ActionGroup::make([

                Action::make('Help and Tips')->form([
                    Textarea::make('')
                    ->default(
"1. How to Use LegalAidPH Effectively

    English:

        1. Start by typing your query or concern in either English or Tagalog.

        2. LegalAidPH will guide you through the process step by step, providing relevant information and assistance.

        3. If you need a family lawyer, provide location, specialization, and years of service for personalized
        recommendations.

        4. You can edit, archive, or delete chat conversations for future reference.

        5. Feel free to ask LegalAidPH for clarification if you don't understand something.

    Tagalog:

        1. Magsimula sa pag-type ng iyong tanong o alalahanin sa Ingles o Tagalog.

        2. Ituturo ka ng LegalAidPH sa bawat hakbang, nagbibigay ng kaugnay na impormasyon at tulong.

        3. Kung naghahanap ka ng abogado sa pamilya, magbigay ng mga detalye tulad ng lokasyon, espesyalisasyon,
        at taon ng paglilingkod para sa mga personalisadong rekomendasyon.

        4. Maaari mong baguhin, i-archive, o burahin ang mga usapan para sa hinaharap na paggamit.

        5. Huwag mag-atubiling magtanong sa LegalAidPH kung hindi mo maintindihan ang isang bagay.

2. Understanding LegalAidPH's Responses:

    English:

        1. LegalAidPH provides information based on the Family Code of the Philippines and relevant legal principles.

        2. If you need clarification on any response, feel free to ask for further explanation.

        3. The chatbot is designed to assist with general legal queries and provide guidance; for complex cases, it's
        recommended to consult with a licensed attorney.

        4. Responses may be provided in both English and Tagalog for better understanding.

        5. LegalAidPH remembers your conversation history to provide continuity and personalized assistance.

    Tagalog:

        1. Binibigyan ka ng LegalAidPH ng impormasyon batay sa Philippine Family Code at mga kaugnay na batas.

        2. Kung kailangan mo ng paliwanag sa anumang sagot, huwag mag-atubiling humingi ng karagdagang
        paliwanag.

        3. Ang chatbot ay idinisenyo upang magbigay ng tulong sa pangkalahatang mga tanong sa batas at magbigay
        ng gabay; para sa mga komplikadong kaso, inirerekomenda na kumonsulta sa isang lisensyadong abogado.

        3. Maaaring magbigay ng mga sagot ang LegalAidPH sa Ingles at Tagalog para sa mas mabuting pang-unawa.

        4. Naalala ng LegalAidPH ang kasaysayan ng iyong usapan upang magbigay ng patuloy na tulong at
        personalisadong asistensya.")

                    ->autosize()
                ])->disabledForm()->modalSubmitAction(false)->Icon('heroicon-c-megaphone')->modalIcon('heroicon-c-megaphone')->modalIconColor('primary'),


                Action::make('Disclaimer')->form([
                    Textarea::make('')
                    ->default(
                        "English : LegalAidPH is an AI-powered chatbot designed to provide general information and assistance regarding family law in the Philippines. While every effort is made to ensure the accuracy and reliability of the information provided, it should not be considered a substitute for professional legal advice. LegalAidPH does not provide legal representation and cannot guarantee the outcome of any legal matter. Users are encouraged to consult with a qualified attorney for personalized legal advice tailored to their specific situation. By using LegalAidPH, you agree to these terms and understand that the chatbot's responses are for informational purposes only.

Tagalog : Ang LegalAidPH ay isang AI-powered chatbot na dinisenyo upang magbigay ng pangkalahatang impormasyon at tulong tungkol sa batas sa pamilya sa Pilipinas. Bagaman ginagawa ang lahat ng pagsisikap upang tiyakin ang katumpakan at katiyakan ng impormasyong ibinigay, hindi ito dapat ituring na kapalit ng propesyonal na payo sa batas. Hindi nagbibigay ng representasyon sa batas ang LegalAidPH at hindi rin nito maaring garantiyahin ang resulta ng anumang usapin sa batas. Inirerekomenda sa mga gumagamit na kumunsulta sa isang lisensyadong abogado para sa personalisadong payo sa batas na naaangkop sa kanilang partikular na sitwasyon. Sa pamamagitan ng paggamit sa LegalAidPH, sumasang-ayon ka sa mga tuntuning ito at nauunawaan na ang mga sagot ng chatbot ay para lamang sa impormasyon.")
                    ->autosize()
                    ->readOnly(),
                ])->disabledForm()->modalSubmitAction(false)->Icon('heroicon-c-hand-raised') ->modalIcon('heroicon-c-hand-raised')->modalIconColor('warning'),

                ])
                ->iconButton()
                ->icon('heroicon-s-question-mark-circle')
                ->size(ActionSize::Large)
                ->color('warning')


    ];
}
}
