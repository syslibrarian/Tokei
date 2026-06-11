<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\DateTime\DateTime;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Prefix;
use Tempest\View\View;
use Tokei\Command\Klr\BuildFromReports;
use Tokei\Command\Klr\UpdateFromReports;
use Tokei\Command\Location\UpdateReport;
use Tokei\Model\Klr\Month;
use Tokei\Model\Location\Location;
use Tokei\Model\Location\LocationHelper;
use Tokei\Model\Location\Report;

#[Prefix('/adm/reports')]
final class AdmReportController extends Controller
{
    use IsAdmin;

    protected function getSectionNavigation(): string
    {
        return 'adm_reports';
    }

    protected function getBaseSlug(): string
    {
        return '/adm/reports/';
    }

    #[Get('/{?year:[0-9]{4}}/{?seal:[0-9]{3}[a-z]?}')]
    public function index(?int $year = null, ?string $seal = null): View
    {
        $this->setActiveSlug('');
        $year = $year ?? DateTime::now()->getYear();

        // location is needed for check
        if ($seal !== null) {
            $location = $this->getBySeal($seal, Location::class);
        } else {
            $locations = LocationHelper::getLocationsForReports();
        }

        $reportsRaw = Report::select();
        if ($seal !== null) {
            $reportsRaw->where('year = ? AND seal = ?', $year, $seal)->orderBy('month');
        } else {
            $reportsRaw->where('year = ?', $year)->orderBy('month, seal');
        }

        return $this->view(
            '@adm/reports.tpl',
            reports: $reportsRaw->all(),
            locations: $locations ?? null,
            location: $location ?? null,
            year: $year,
            seal: $seal ?? null,
        );
    }

    #[
        Get('/update/{timeCode:[0-9]{4}-[0-9]{2}}/{seal:[0-9]{3}[a-z]?}/'),
        Post('/update/{timeCode:[0-9]{4}-[0-9]{2}}/{seal:[0-9]{3}[a-z]?}/')
    ]
    public function update(string $timeCode, string $seal, Request $request): View
    {
        $this->setActiveSlug('');

        $model = $this->getBySeal($seal, Report::class, $timeCode);
        $location = $this->getBySeal($seal, Location::class);

        $command = new UpdateReport(
            model: $model,
            circulations: (int) $request->get('circulations', $model->circulations),
            visits: (int) $request->get('visits', $model->visits),
            visitsManual: (int) $request->get('visitsManual', $model->visits_manual),
            openHours: (int) $request->get('openHours', $model->open_hours),
            openLibraryHours: (int) $request->get('openLibraryHours', $model->open_library_hours),
            mediaPackages: (int) $request->get('mediaPackages', $model->media_packages),
            shifts: (int) $request->get('shifts', $model->shifts),
            coversReceived: (int) $request->get('coversReceived', $model->covers_received),
            coversGiven: (int) $request->get('coversGiven', $model->covers_given)
        );

        $response = $this->executeCommand($command, $request);

        return $this->view(
            '@adm/updateReport.tpl',
            report: $command,
            location: $location,
            errors: $this->validationParser->parsedErrors,
            success: $response->value === true,
            // later get here covers given and received complet for month
        );
    }

    #[Get('/klr/{?year:[0-9]{4}}/')]
    public function showKlr(?int $year = null): View
    {
        $year = $year ?? DateTime::now()->getYear();

        $months = Month::select()->where('year = ?', $year)->orderBy('month, seal')->all();
        $locations = LocationHelper::getLocationsForReports();

        return $this->view(
            '@adm/showKlr.tpl',
            locations: $locations,
            months: $months,
        );
    }

    #[Get('/print-klr/{?year:[0-9]{4}}/')]
    public function printKlr(?int $year = null): View
    {
        $year = $year ?? DateTime::now()->getYear();

        $months = Month::select()->where('year = ?', $year)->orderBy('month, seal')->all();
        $locations = LocationHelper::getLocationsForReports();

        return $this->view(
            '@adm/printKlr.tpl',
            locations: $locations,
            months: $months,
        );
    }

    #[Get('close-report/{?month:[0-9]{2}}/{?year:[0-9]{4}}/')]
    public function closeReports(?int $month = null, ?int $year = null): Redirect
    {
        [$month, $year] = $this->getTimeCode($month, $year);

        $command = new BuildFromReports($month, $year);
        $response = $this->sendCommand($command);
        $this->session->flash('generated_klr', ($response->value === true));

        return $this->redirect('/adm/reports/');
    }

    #[Get('update-klr/{?month:[0-9]{2}}/{?year:[0-9]{4}}/')]
    public function updateKlr(?int $month = null, ?int $year = null): Redirect
    {
        [$month, $year] = $this->getTimeCode($month, $year);

        $command = new UpdateFromReports($month, $year);
        $response = $this->sendCommand($command);
        $this->session->flash('update_klr', ($response->value === true));

        return $this->redirect('/adm/reports/');
    }

    protected function getTimeCode(?int $month, ?int $year): array
    {
        $month = $month ?? DateTime::now()->getMonth();
        if ($year === null && $month === 1) {
            $month = 12;
            $year = DateTime::now()->getYear() - 1;
        } else {
            $year = $year ?? DateTime::now()->getYear();
        }

        return [$month, $year];
    }
}