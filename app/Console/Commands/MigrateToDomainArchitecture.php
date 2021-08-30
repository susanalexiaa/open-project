<?php

namespace App\Console\Commands;

use App\Domains\Lead\Models\Lead;
use Illuminate\Console\Command;
use Te7aHoudini\LaravelTrix\Models\TrixRichText;

class MigrateToDomainArchitecture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:architecture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TrixRichText::query()->where('model_type', '=', 'App\Models\Lead')->update(['model_type' => Lead::class]);
        TrixRichText::query()->where('model_type', '=', 'App\Models\Comment')->update(['model_type' => Lead::class]);
        return 0;
    }
}
