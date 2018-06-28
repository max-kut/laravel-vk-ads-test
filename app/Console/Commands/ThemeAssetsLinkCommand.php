<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ThemeAssetsLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать символическую ссылку на ресурсы темы';

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
     * @return mixed
     */
    public function handle()
    {
        $themes = env('THEMES');
        if (is_string($themes)) {

            $themes = explode(',', $themes);
            
            // очистим ссылки перед созданием новых
            array_map(
                function($link){
                    if(is_link($link)) {
                        unlink($link);
                    }
                },
                glob(public_path('assets' . DIRECTORY_SEPARATOR . '*'),GLOB_ONLYDIR)
            );

            foreach ($themes as $theme) {
                $symlinkPath = public_path('assets' . DIRECTORY_SEPARATOR . $theme);
                $symlinkTarget = resource_path(
                    'views' . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'assets');

                if (file_exists($symlinkTarget)) {
                    $symlinkExists = file_exists($symlinkPath);
                    if ($symlinkExists) {
                        $this->error('ERROR: Symlink already exists!');
                    } else {
                        $this->laravel->make('files')
                            ->link($symlinkTarget, $symlinkPath);

                        $this->info('The ['.$symlinkTarget.'] directory has been linked.');
                    }
                } else {
                    $this->error('ERROR: theme path not exists!');
                }
            }
        } else {
            $this->error('ERROR: key "THEMES" not defined in .env file');
        }
        return null;
    }
}
