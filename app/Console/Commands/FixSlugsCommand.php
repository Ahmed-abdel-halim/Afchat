<?php

namespace App\Console\Commands;

use App\Models\Setup;
use App\Models\Tag;
use Illuminate\Console\Command;

class FixSlugsCommand extends Command
{
    protected $signature = 'app:fix-slugs';
    protected $description = 'Generate slugs for existing Tags and Setups';

    public function handle()
    {
        $this->info('Fixing Slugs for Tags...');
        Tag::all()->each(function ($tag) {
            $tag->slug = null; // Power sluggable to regenerate
            $tag->save();
        });

        $this->info('Fixing Slugs for Setups...');
        Setup::all()->each(function ($setup) {
            $setup->slug = null;
            $setup->save();
        });

        $this->info('All slugs updated successfully!');
    }
}
