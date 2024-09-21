<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobOfferResource\Pages;
use App\Filament\Resources\JobOfferResource\RelationManagers;
use App\Models\JobOffer;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobOfferResource extends Resource
{
    protected static ?int $navigationSort = 3;
    protected static ?string $model = JobOffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
        
            ->schema([
                Forms\Components\TextInput::make('Job')->required(),
                Forms\Components\TextInput::make('Country')->required(),

                Select::make('Status')
                ->label('Status')
                ->options([
                    'available' => 'Available',
                    'Unavailable' => 'Unavailable',
                ])
            ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'Job')->searchable(),
                TextColumn::make('Country')->searchable(),
                TextColumn::make('Status')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobOffers::route('/'),
            'create' => Pages\CreateJobOffer::route('/create'),
            'list' => Pages\EditJobOffer::route('/{record}/list'),
        ];
    }
}
