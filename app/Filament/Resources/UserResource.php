<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Agency;
use App\Models\Customer;
use App\Models\Sender;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()

                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nome')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->label('E-mail')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('password')
                                ->label('Senha')
                                ->password()
                                ->required()
                                ->hiddenOn('edit')
                                ->maxLength(255),
                            Forms\Components\Select::make('role')
                                ->label('Perfis')
                                ->multiple()
                                ->required()
                                ->relationship('roles', 'name', function (Builder $query) {
                                    return $query->where('name', '!=', 'admin');
                                })
                                ->preload()
                        ]),

                    Forms\Components\Select::make('userable_type')
                        ->label('Tipo de Usuário')
                        ->options([
                            Agency::class => 'Agência',
                            Sender::class => 'Emissor',
                            Customer::class => 'Cliente',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Forms\Set $set, $state) {
                            $set('userable_id', null);
                        }),

                    Forms\Components\Select::make('userable_id')
                        ->label('Detalhe do Usuário')
                        ->options(function (callable $get) {
                            $type = $get('userable_type');
                            return $type ? $type::pluck('name', 'id') : [];
                        })
                        ->reactive()
                        ->hidden(fn (callable $get) => is_null($get('userable_type'))),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Perfis')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de criação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Data da alteração')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('id', '!=', 1);
    }
}
