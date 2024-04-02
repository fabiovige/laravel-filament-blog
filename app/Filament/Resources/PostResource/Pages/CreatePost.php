<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getPayload(): array
    {
        $payload = parent::getPayload();
        $payload['user_id'] = auth()->id(); // Adiciona o user_id ao payload com o ID do usuário atual
        return $payload;
    }

}
