<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Agency;
use App\Models\Customer;
use App\Models\Sender;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isEmitter = false;
        $isAgency = true;

        $formSchema = [
            Forms\Components\TextInput::make('details')
                ->required()
                ->maxLength(255),
        ];

        if ($isEmitter) {
            $formSchema[] = Forms\Components\TextInput::make('ticketable_type')
                ->label('Ticket Tipo Emitter')
                ->default('App\Models\Sender') // Substitua pelo namespace correto
                //->hidden()
                ->required();

            $formSchema[] = Forms\Components\TextInput::make('ticketable_id')
                ->default(1) // Assumindo que você tenha uma relação direta para obter o ID do emissor
                //->hidden()
                ->required();
        }

        if ($isAgency) {
            $formSchema[] = Forms\Components\TextInput::make('ticketable_type')
                ->label('Ticket Tipo Emitter 2')
                ->default('App\Models\Sender') // Substitua pelo namespace correto
                //->hidden()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('ticketable_id', null))
                ->required();

            $formSchema[] = Forms\Components\Select::make('ticketable_id')
                ->label('Ticket Emitter')
                ->options(function (callable $get) {
                    $type = $get('ticketable_type');
                    return $type ? $type::pluck('name', 'id')->toArray() : [];
                })
                ->reactive()
                ->required();
        }

        return $form->schema($formSchema);

        /*
        return $form
            ->schema([

                Forms\Components\Select::make('ticketable_type')
                    ->label('Ticket Emitter Type')
                    ->options([
                        Agency::class => 'Agency',
                        Sender::class => 'Emitter',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('ticketable_id', null))
                    ->required(),

                Forms\Components\Select::make('ticketable_id')
                    ->label('Ticket Emitter')
                    ->options(function (callable $get) {
                        $type = $get('ticketable_type');
                        return $type ? $type::pluck('name', 'id')->toArray() : [];
                    })
                    ->reactive()
                    ->required(),
            ]);
        */
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('details')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ticketable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticketable_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
