<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResumeResource\Pages;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Talent;
use App\Models\Skill;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;

class ResumeResource extends Resource
{
    protected static ?string $model = Talent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Personal Details')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('linkedin_url')
                                    ->url()
                                    ->maxLength(255),
                                Forms\Components\Select::make('job_role_id')
                                    ->relationship('jobRole', 'name')
                                    ->required(),
                            ])
                        ]),
                    Step::make('Skills and Experience')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\Select::make('skills')
                                    ->multiple()
                                    ->options(function () {
                                        return Skill::all()->pluck('name', 'id');
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $currentSkillDetails = $get('skill_details') ?? [];

                                        // Create a map of existing skill details
                                        $existingSkillMap = collect($currentSkillDetails)->keyBy('skill')->toArray();

                                        // Create or update skill details
                                        $updatedSkillDetails = collect($state)->map(function ($skillId) use ($existingSkillMap) {
                                            $skillName = Skill::find($skillId)->name;
                                            return [
                                                'skill' => $skillId,
                                                'skill_name' => $skillName,
                                                'years_experience' => $existingSkillMap[$skillId]['years_experience'] ?? null,
                                            ];
                                        })->toArray();

                                        $set('skill_details', $updatedSkillDetails);
                                    })->columnStart(1)
                            ]),
                            Repeater::make('skill_details')
                                ->schema([
                                    Forms\Components\Select::make('skill')
                                        ->options(function () {
                                            return Skill::all()->pluck('name', 'id');
                                        })
                                        ->disabled(),
                                    Forms\Components\TextInput::make('years_experience')
                                        ->numeric()
                                        ->label('Years of Experience')
                                        ->required(),
                                ])
                                ->columns(2)
                                ->disableItemCreation()
                                ->disableItemDeletion()
                                ->disableItemMovement()
                                ->columnSpanFull(),
                        ]),
                ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jobRole.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResumes::route('/'),
            'create' => Pages\CreateResume::route('/create'),
            'edit' => Pages\EditResume::route('/{record}/edit'),
        ];
    }
}
