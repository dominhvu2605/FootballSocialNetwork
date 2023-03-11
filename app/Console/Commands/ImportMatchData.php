<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportMatchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importMatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import match data from csv file';

    protected $folderData;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->folderData = storage_path('dataToImport/match');
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

        // Get latest file
        $allCsvFile = array_map(function($file) {
            return $file->getRealPath();
        }, File::allFiles($this->folderData));
        rsort($allCsvFile);
        $targetFile = $allCsvFile[0];

        // Get all club
        $tempData = DB::table('clubs')->select('id', 'full_name')->get();
        $allClub = [];
        foreach ($tempData as $club) {
            $allClub[$club->full_name] = $club->id;
        }

        // Read csv file and create data to import
        $dataImport = [];
        $handle = fopen($targetFile,"r");

        while (($line = fgetcsv($handle)) !== FALSE) {
            if (array_key_exists($line[4], $allClub) && array_key_exists($line[5], $allClub)) {
                $timeStart = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $line[2])));
                $dataImport[] = [
                    'home_team_id'  => $allClub[$line[4]],
                    'away_team_id'  => $allClub[$line[5]],
                    'league_id'     => '1',
                    'time_start'    => $timeStart,
                    'stadium'       => $line[3],
                    'result'        => ($line[6] != '') ? $line[6] : '',
                    'status_id'     => (date('Ymd', strtotime($timeStart)) > date('Ymd')) ? 1 : 2
                ];
            }
        }
        fclose($handle);

        // Insert to DB
        try {
            DB::table('matches')->insert($dataImport);
            $this->info("===================================Good End!" . date('Y/m/d(D) H:i', time()));
            return true;
        } catch (Exception $e) {
            $this->info('Error: ' . $e->getMessage());
            $this->info("===================================Bad End!" . date('Y/m/d(D) H:i', time()));
            return false;
        }
    }
}
