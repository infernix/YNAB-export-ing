<?php

namespace App\Commands;

use App\Contracts;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Dusk\Browser;
use LaravelZero\Framework\Commands\Command;

class ING extends YNAB implements Contracts\ExportsCsv
{
    private $file;

    private $url = 'https://mijn.ing.nl/login';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ing';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get a CSV export from ING for YNAB';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->file = $this->getFile();
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    public function exportsCsv(): void
    {
        // TODO: Implement exportsCsv() method.
    }

    private function getFile()
    {
        $this->browse(function ($browser) {
            $this->prepareDomain($browser);

            $originalBrowser = $browser->getOriginalBrowser();
            $url = $originalBrowser->driver->getCommandExecutor()->getAddressOfRemoteServer();
            $uri = '/session/' . $originalBrowser->driver->getSessionID() . '/chromium/send_command';
            $body = [
                'cmd'    => 'Page.setDownloadBehavior',
                'params' => ['behavior' => 'allow', 'downloadPath' => storage_path() . '/app'],
            ];

            (new \GuzzleHttp\Client())->post($url . $uri, ['body' => json_encode($body)]);
            $this->info("Success!");
        });
    }

    /**
     * @param $browser
     *
     * @return mixed
     */
    private function prepareDomain($browser)
    {
        return $browser->visit($this->url)
                       ->assertSee('Log in bij Mijn ING')
                       ->type('#username', getenv('ING_USERNAME'))
                       ->type('#password', getenv('ING_PASSWORD'))
                       ->click('#submitButton')
                       ->visit('https://mijn.ing.nl/particulier/overzichten/download/index')
                       ->click('#accounts')//accounts select
                       ->click('#accounts > ul > li:nth-child(3) > label > div > div')// select account
                       ->type('#startDate-input', '01-01-2000')// start date export
                       ->select('#downloadFormat', 'string:CSV')//set csv as type
                       ->click('#gTransactionsDownload > form > div:nth-child(6) > fieldset > div.col-md-6 > div > div > button');
    }
}
