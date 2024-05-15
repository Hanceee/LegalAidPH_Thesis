<?php

namespace App\Filament\Pages;

use Exception;
use App\Models\Lawyers;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Services\GPTEngine;
use Livewire\Attributes\On;
use PhpParser\Node\Stmt\Label;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class LawyerRecommender extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static string $view = 'filament.pages.lawyer-recommender';
    public ?array $data = [];
    public bool $waitingForResponse = false;
    public string $reply = '';
    public string $lastQuestion = '';
    protected static ?string $navigationLabel = 'Find a Lawyer';
    protected ?string $heading = 'Lawyer Finder';



    protected function getHeaderActions(): array
    {
        return [
            Action::make("Lawyer Request")
            ->label("Are you a Lawyer?")
            ->url(fn (): string => 'https://docs.google.com/forms/d/e/1FAIpQLSeEsUCLfVaFye1QYfQI5xUSY2yOao36lNgoh0V7NGGw-hounQ/viewform?usp=sf_link')
            ->openUrlInNewTab()    ->link()

            ,



        ];
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Lawyers::query())

            ->groups([
                Group::make('location'),
            ])

            ->columns([
                Stack::make([

                TextColumn::make('name')
                ->icon('heroicon-o-scale'),
                TextColumn::make('contact')->wrap()->alignment(Alignment::End),


                TextColumn::make('specializations')->wrap()
                ->searchable()->alignment(Alignment::End),
                TextColumn::make('location')
                ->icon('heroicon-s-map-pin')
                ->searchable()->alignment(Alignment::End),
                TextColumn::make('name')->formatStateUsing(function (string $state): string {
                    $parts = explode(' ', $state);
                    return end($parts) . ' Law Offices';
                })->alignment(Alignment::End)->icon('heroicon-o-building-library'),
                TextColumn::make('experience')
                ->formatStateUsing(fn (string $state): string => __("{$state} years"))->sortable()->alignment(Alignment::End)
                ,

                IconColumn::make('verified')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->sortable(),
            ]),

            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([

    Filter::make('not verified')
    ->query(fn (Builder $query): Builder => $query->where('verified', false))->toggle(),
    TextFilter::make('location')

            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('message')
                ->label("Find a Lawyer Bot")
                    ->required()     ->hint('Search Family Lawyers')
    ->hintIcon('heroicon-c-magnifying-glass')   ->helperText('Find a lawyer near you, based on experience and verified credentials.')




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
}

