<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\DateTime\DateTime;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Prefix;
use Tempest\Router\WithMiddleware;
use Tempest\View\View;
use Tokei\Command\Klr\BuildFromReports;
use Tokei\Command\Location\UpdateReport;
use Tokei\Component\Access\AccessContext;
use Tokei\Component\Access\IsAuthenticated;
use Tokei\Model\Klr\KlrReport;
use Tokei\Model\Location\Location;
use Tokei\Model\Location\LocationHelper;
use Tokei\Model\Location\MonthlyReport;
use Tokei\Model\Location\ReportHelper;
use Tokei\Tool\Statistic\KlrPrinter;

#[Prefix('/adm/reports'), WithMiddleware(IsAuthenticated::class)]
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
        $year ??= DateTime::now()->getYear();

        // location is needed for check
        if ($seal !== null) {
            $location = $this->getBySeal($seal, Location::class);
            $locations = null;
        } else {
            $location = null;
            $locations = Location::select()->all();
        }

        $reports = $seal !== null ? ReportHelper::getFor($seal) : ReportHelper::getSealSorted();

        return $this->view(
            '@adm/reports.tpl',
            reports: $reports,
            locations: $locations,
            location: $location,
            year: $year,
            seal: $seal,
            klrStatus: $this->session->consume('klr'),
        );
    }

    #[Get('/show-report/{timeCode:[0-9]{4}-[0-9]{2}}/{seal:[0-9]{3}[a-z]?}')]
    public function showReport(string $timeCode, string $seal): View
    {
        $report = $this->getBySeal($seal, MonthlyReport::class, $timeCode);
        $location = $this->getBySeal($seal, Location::class);

        return $this->view(
            '@adm/showReport.tpl',
            location: $location,
            report: $report,
        );
    }

    #[
        Get('/update/{timeCode:[0-9]{4}-[0-9]{2}}/{seal:[0-9]{3}[a-z]?}/'),
        Post('/update/{timeCode:[0-9]{4}-[0-9]{2}}/{seal:[0-9]{3}[a-z]?}/'),
    ]
    public function update(string $timeCode, string $seal, Request $request): View
    {
        $this->setActiveSlug('');

        $model = $this->getBySeal($seal, MonthlyReport::class, $timeCode, AccessContext::UPDATE);
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
            coversGiven: (int) $request->get('coversGiven', $model->covers_given),
            staffExternal: (int) $request->get('staffExternal', $model->staff_external),
            staffExternalHours: (float) $request->get('staffExternalHours', $model->staff_external_hours),
            staffGrant: (int) $request->get('staffGrant', $model->staff_grant),
            staffGrantHours: (float) $request->get('staffGrantHours', $model->staff_grant_hours),
            staffVolunteer: (int) $request->get('staffVolunteer', $model->staff_volunteer),
            staffVolunteerHours: (float) $request->get('staffVolunteerHours', $model->staff_volunteer_hours),
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

    #[
        Get('/klr/{?year:[0-9]{4}}/'),
        Get('/klr/{print:print}/{?year:[0-9]{4}}/'),
    ]
    public function showKlr(?int $year = null, string $print = ''): View
    {
        $year ??= DateTime::now()->getYear();

        $months = KlrReport::select()->where('year = ?', $year)->orderBy('month, seal')->all();
        $locations = LocationHelper::getLocationsForReports();
        $printer = new KlrPrinter($months, $locations);

        return $this->view(
            $print !== 'print' ? '@adm/showKlr.tpl' : '@adm/printKlr.tpl',
            printer: $printer,
            year: $year,
            isPrint: $print === 'print',
        );
    }

    #[Get('close-report/{?month:[0-9]{1,2}}/{?year:[0-9]{4}}/')]
    public function closeReports(?int $month = null, ?int $year = null): Redirect
    {
        $this->checkModel(KlrReport::class, AccessContext::UPDATE);
        [$month, $year] = $this->getTimeCode($month, $year);

        if ($year > DateTime::now()->getYear() || $year === DateTime::now()->getYear() && $month >= DateTime::now()->getMonth()) {
            $this->session->set('klr', 'error');
        } else {
            $command = new BuildFromReports($month, $year);
            $response = $this->sendCommand($command);
            $this->session->set('klr', $response->value === true ? 'success' : 'error');
        }

        return $this->redirect('/adm/reports/');
    }

    protected function getTimeCode(?int $month, ?int $year): array
    {
        if ($month === null && $year === null) {
            $month = DateTime::now()->getMonth();
            $year = DateTime::now()->getYear();

            if ($month === 1) {
                $month = 12;
                $year--;
            }
        } else {
            $month ??= DateTime::now()->getMonth();
            $year ??= DateTime::now()->getYear();
        }

        return [$month, $year];
    }
}
