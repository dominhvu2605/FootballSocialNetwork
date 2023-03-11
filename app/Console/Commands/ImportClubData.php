<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportClubData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importClub';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import match data from csv file';

    protected $fileData;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileData = storage_path('dataToImport/club/EPLStandings.csv');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Info
        $this->info("===================================Start!" . date('Y/m/d(D) H:i', time()));

        // Get club name
        $clubName = [];
        $handle = fopen($this->fileData, "r");
        while (($line = fgetcsv($handle)) !== FALSE) {
            $clubName[] = [
                'full_name' => trim($line[0])
            ];
        }
        unset($clubName[0]);
        fclose($handle);

        // Insert to DB
        try {
            DB::table('clubs')->insert($clubName);
            $this->info("===================================Good End!" . date('Y/m/d(D) H:i', time()));
            return true;
        } catch (Exception $e) {
            $this->info('Error: ' . $e->getMessage());
            $this->info("===================================Bad End!" . date('Y/m/d(D) H:i', time()));
            return false;
        }
    }
}
