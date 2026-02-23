<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\RefreshToken;
use App\Models\UserSession;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Prune expired or revoked refresh tokens and their sessions
Artisan::command('tokens:prune', function () {

    $pruned = 0;

    RefreshToken::query()
        ->where(function ($q) {
            $q->where('expired_at', '<', now())
                ->orWhereNotNull('revoked_at');
        })
        ->chunk(100, function ($tokens) use (&$pruned) {
            $ids = $tokens->pluck('id');

            if ($ids->isEmpty()) {
                return;
            }

            UserSession::whereIn('refresh_token_id', $ids)->delete();
            RefreshToken::whereIn('id', $ids)->delete();

            $pruned += $ids->count();
        });

    $this->info("Pruned {$pruned} expired/revoked refresh tokens.");
})->purpose('Delete expired or revoked refresh tokens and their sessions');
