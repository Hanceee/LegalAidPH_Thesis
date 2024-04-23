<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Component;

class HomePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Home';
    protected ?string $heading = '';
    protected static string $view = 'filament.pages.home-page';


}
