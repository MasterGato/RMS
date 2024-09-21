<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicantResource\Pages;
use App\Filament\Resources\ApplicantResource\RelationManagers;
use App\Models\Applicant;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Section;  

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Applicants Information')
                    ->collapsible()


                    ->schema([
                        TextInput::make('Firstname')
                            ->label('First Name')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true)
                            ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('Middlename')
                            ->label('Middle Name')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true)
                            ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('Lastname')
                            ->label('Last Name')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true),

                        Select::make('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female'
                            ]),
                        TextInput::make('Contact')
                            ->label('Contact Number')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true)
                            ->string()->rules('regex:/^[0-9]{11}$/'),

                        TextInput::make('Email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true),

                        datepicker::make('Dateofbirth')
                            ->label('Date of Birth')->required()
                            ->rules(['date', 'before_or_equal:' . now()->subYears(22)->toDateString()]),

                        Select::make('Region')
                            ->label('Region')
                            ->options([
                                'region 12' => 'Region 12',
                                'region 11' => 'Region 11',
                            ])
                            ->reactive() // Make the field reactive to changes
                            ->afterStateUpdated(fn(callable $set) => $set('Province', null)) // Reset province when region changes
                            ->afterStateUpdated(fn(callable $set) => $set('City', null)) // Reset city when region changes
                            ->afterStateUpdated(fn(callable $set) => $set('Brgy', null)), // Reset barangay when region changes

                        Select::make('Province')
                            ->label('Province')
                            ->options(function (callable $get) {
                                $region = $get('Region');
                                if ($region === 'region 12') {
                                    return [
                                        'south Cotabato' => 'South Cotabato',
                                        'sultan Kudarat' => 'Sultan Kudarat',
                                    ];
                                } elseif ($region === 'region 11') {
                                    return [
                                        'davao del sur' => 'Davao Del Sur',
                                        'davao del norte' => 'Davao Del Norte',
                                    ];
                                }
                                return [];
                            })
                            ->reactive() // Make the field reactive to changes
                            ->afterStateUpdated(fn(callable $set) => $set('City', null)) // Reset city when province changes
                            ->afterStateUpdated(fn(callable $set) => $set('Brgy', null)) // Reset barangay when province changes
                            ->required(),

                        Select::make('City')
                            ->label('City')
                            ->options(function (callable $get) {
                                $province = $get('Province');
                                if ($province === 'south Cotabato') {
                                    return [
                                        'koronadal' => 'Koronadal',
                                        'surallah' => 'Surallah',
                                    ];
                                } elseif ($province === 'sultan Kudarat') {
                                    return [
                                        'isulan' => 'Isulan',
                                        'tacurong' => 'Tacurong',
                                    ];
                                }
                                return [];
                            })

                            ->reactive() // Make the field reactive to changes
                            ->afterStateUpdated(fn(callable $set) => $set('Brgy', null)) // Reset barangay when city changes
                            ->required(),

                        Select::make('Brgy')
                            ->label('Barangay')
                            ->options(function (callable $get) {
                                $city = $get('City');
                                if ($city === 'koronadal') {
                                    return [
                                        'barangay 1' => 'Barangay 1',
                                        'barangay 2' => 'Barangay 2',
                                    ];
                                } elseif ($city === 'surallah') {
                                    return [
                                        'barangay a' => 'Barangay A',
                                        'barangay b' => 'Barangay B',
                                    ];
                                } elseif ($city === 'isulan') {
                                    return [
                                        'barangay x' => 'Barangay X',
                                        'barangay y' => 'Barangay Y',
                                    ];
                                } elseif ($city === 'tacurong') {
                                    return [
                                        'barangay p' => 'Barangay P',
                                        'barangay q' => 'Barangay Q',
                                    ];
                                }
                                return [];
                            })
                            ->reactive() // Make the field reactive to changes
                            ->required(),


                        TextInput::make('Citizenship')
                            ->label('Citizenship')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true)
                            ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('Zipcode')
                            ->label('Zip Code')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true)
                            ->string()->rules('regex:/^[0-9]{4}$/'),

                        TextInput::make('Status')
                            ->label('Status')
                            ->required(fn(string $context) => $context === 'create')
                            ->unique(ignoreRecord: true)


                    ])->columns(3),

                Section::make('Educational Attainment')
                    ->collapsible()
                    ->schema([

                        TextInput::make('Level')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('Elementary')
                        ->label('Elementary school')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                        Select::make('year_graduated')
                        ->label('Year Graduated')
                        ->options(
                            collect(range(now()->year - 22, now()->year))->mapWithKeys(function ($year) {
                                return [$year => $year];
                            })
                        )
                        ->required()
                        ->rules('required'),

                        TextInput::make('Level')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('Highschool')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                        Select::make('year_graduated')
                        ->label('Year Graduated')
                        ->options(
                            collect(range(now()->year - 22, now()->year))->mapWithKeys(function ($year) {
                                return [$year => $year];
                            })
                        )
                        ->required()
                        ->rules('required'),
                        TextInput::make('Level')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('College')
                        ->label('College')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                       Select::make('year_graduated')
                        ->label('Year Graduated')
                        ->options(
                            collect(range(now()->year - 22, now()->year))->mapWithKeys(function ($year) {
                                return [$year => $year];
                            })
                        )
                        ->required()
                        ->rules('required'),

                    ])->columns(3),

                Section::make('Work Experience')
                    ->collapsible()
                    
                    ->schema([
                        TextInput::make('Company')
                        ->label('Company')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),

                        TextInput::make('Work')
                        ->label('Work')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),
                        

                        TextInput::make('Year')
                        ->label('Years Experience')
                        ->required(fn(string $context) => $context === 'create')
                        ->unique(ignoreRecord: true)
                        ->string()->rules('regex:/^[^\d]*$/'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Firstname')
                    ->label('First Name')
                    ->searchable(),

                TextColumn::make('Lastname')
                    ->label('Last Namde')
                    ->searchable(),

                TextColumn::make('Middlename')
                    ->label('Middle Name')
                    ->searchable(),
                TextColumn::make('Gender')
                    ->label('Gender')
                    ->searchable(),

                TextColumn::make('Contact')
                    ->label('Contact')
                    ->searchable(),

                TextColumn::make('Email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('Dateofbirth')
                    ->label('Date of Birth')
                    ->searchable(),

                TextColumn::make('Citizenship')
                    ->label('Citizenship')
                    ->searchable(),

                TextColumn::make('Region')
                    ->label('Region')
                    ->searchable(),
                TextColumn::make('Province')
                    ->label('Province')
                    ->searchable(),

                TextColumn::make('City')
                    ->label('City')
                    ->searchable(),

                TextColumn::make('Brgy')
                    ->label('Brgy')
                    ->searchable(),

                TextColumn::make('Zipcode')
                    ->label('Zipcode')
                    ->searchable(),

                TextColumn::make('Status')
                    ->label('Status')
                    ->searchable(),

                

            ])
            ->filters([
                //
            ])
            ->actions([
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicants::route('/'),
            'create' => Pages\CreateApplicant::route(path: '/create'),
            'edit' => Pages\EditApplicant::route('/{record}/edit'),
        ];
    }
}
