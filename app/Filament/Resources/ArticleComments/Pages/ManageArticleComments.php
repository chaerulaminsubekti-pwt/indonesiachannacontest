<?php

namespace App\Filament\Resources\ArticleComments\Pages;

use App\Filament\Resources\ArticleComments\ArticleCommentResource;
use Filament\Resources\Pages\ManageRecords;

class ManageArticleComments extends ManageRecords
{
    protected static string $resource = ArticleCommentResource::class;
}
