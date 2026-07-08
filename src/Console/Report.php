<?php

declare(strict_types=1);

namespace Tokei\Console;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Log\Logger;
use Tempest\Validation\Rules\MatchesRegEx;
use Tokei\Command\Klr\CreateMonths;
use Tokei\Command\Location\CreateReports;
use function Tempest\CommandBus\command;
use function Tempest\Container\get;

final class Report
{
    public function __construct(protected(set) Console $console)
    {}

    #[ConsoleCommand('create-reports')]
    public function createReports(): void
    {
        $year = $this->console->ask(
            'For Year?',
            validation: [new MatchesRegEx('^[0-9]{4}$')],
        );

        try {
            $reportCommand = new CreateReports($year);
            command($reportCommand); // fire and foreget.

            $klrCommand = new CreateMonths($year);
            command($klrCommand); // fire and forget.

            $this->console->info('Reports for ' . $year . ' created.');
        } catch (\Throwable $e) {
            get(Logger::class)->error($e);
            $this->console->error('Could not create reports for ' . $year);
        }
    }
}
