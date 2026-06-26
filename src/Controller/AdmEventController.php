<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\Database\Direction;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Prefix;
use Tempest\Router\WithMiddleware;
use Tempest\View\View;
use Tokei\Command\Event\CreateEvent;
use Tokei\Command\Event\UpdateEvent;
use Tokei\Command\Institution\CreateInstitution;
use Tokei\Command\Institution\DeleteInstitution;
use Tokei\Command\Institution\UpdateInstitution;
use Tokei\Component\Access\AccessContext;
use Tokei\Component\Access\IsAuthenticated;
use Tokei\Model\Event\Event;
use Tokei\Model\Event\EventHelper;
use Tokei\Model\Institution\Institution;
use Tokei\Model\Institution\Type;
use Tokei\Model\Location\LocationHelper;
use Tokei\Tool\Event\DBSSection;
use Tokei\Tool\Event\Form;
use Tokei\Tool\Pagination\Pagination;

use function Tokei\Str\trim;

#[Prefix('/adm/events'), WithMiddleware(IsAuthenticated::class)]
final class AdmEventController extends Controller
{
    use IsAdmin;

    protected function getSectionNavigation(): string
    {
        return 'adm_events';
    }

    protected function getBaseSlug(): string
    {
        return '/adm/events/';
    }

    #[
        Get(uri: '/{?seal:[0-9]{3}[a-z]?}/{?no:[0-9]+}/'),
        Get(uri: '/{no:[0-9]+}/'),
    ]
    public function index(?string $seal = null, int $no = 1): View
    {
        $this->setActiveSlug('list/');
        $location = $seal !== null ? $this->getBySeal($seal, Event::class) : null;

        $pagination = new Pagination(
            pageNo: $no,
            maxItems: $location === null ? Event::count()->execute() : Event::count()->where('seal', $location->seal)->execute(),
            uri: $this->getBaseSlug() . ($location !== null ? $location->seal . '/' : '') . '{no}',
        );

        $eventsRaw = Event::select();
        if ($location !== null) {
            $eventsRaw = $eventsRaw->where('seal', $location->seal);
        }
        $eventsRaw
            ->orderBy('time_start', Direction::DESC)
            ->offset($pagination->offset)
            ->limit($pagination->limit);

        return $this->view(
            '@adm/events.tpl',
            pagination: $pagination,
            location: $location,
            events: $eventsRaw->all(),
        );
    }

    #[
        Get(uri: '/list/{?seal:[0-9]{3}[a-z]?}/{?no:[0-9]+}/'),
        Get(uri: '/list/{no:[0-9]+}/'),
    ]
    public function list(?string $seal = null, int $no = 1): View
    {
        $this->setACtiveSlug('list/');
        $location = $seal !== null ? $this->getBySeal($seal, Event::class) : null;

        $pagination = new Pagination(
            pageNo: $no,
            maxItems: $location === null ? Event::count()->execute() : Event::count()->where('seal', $location->seal)->execute(),
            uri: $this->getBaseSlug() . ($location !== null ? $location->seal . '/' : '') . '{no}',
        );

        $eventsRaw = Event::select();
        if ($location !== null) {
            $eventsRaw = $eventsRaw->where('seal', $location->seal);
        }
        $eventsRaw
            ->orderBy('time_start', Direction::DESC)
            ->offset($pagination->offset)
            ->limit($pagination->limit);

        return $this->view(
            '@adm/events.tpl',
            pagination: $pagination,
            location: $location,
            events: $eventsRaw->all(),
        );
    }

    #[Get(uri: '/list-institutions/{?no:[0-9]+}/')]
    public function listInstitutions(int $no = 0): View
    {
        $this->setActiveSlug('list-institutions/');
        $pagination = new Pagination(
            pageNo: $no,
            maxItems: Institution::count()->execute(),
            uri: $this->getBaseSlug() . 'list-institutions/{no}',
        );

        $institutions = Institution::select()
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->view(
            '@adm/listInstitutions.tpl',
            pagination: $pagination,
            institutions: $institutions,
        );
    }

    #[Get(uri: '/create-institution/'), Post(uri: '/create-institution/')]
    public function createInstitution(Request $request): View|Redirect
    {
        $this->checkModel(Institution::class);
        $this->setActiveSlug('create-institution/');

        $institution = new CreateInstitution(
            name: trim($request->get('name', '')),
            educator: trim($request->get('educator', '')),
            email: trim($request->get('email', '')),
            phone: trim($request->get('phone', '')),
            seal: trim($request->get('seal', '')),
            type: trim($request->get('type', '')),
        );

        $response = $this->executeCommand($institution, $request);

        return $this->view(
            '@adm/createInstitution.tpl',
            institution: $institution,
            locations: LocationHelper::getLocationsForForm(),
            types: Type::getForForm(),
            errors: $this->validationParser->parsedErrors,
        );
    }

    #[Get(uri: '/update-institution/{id:[0-9]+}/'), Post(uri: '/update-institution/{id:[0-9]+}/')]
    public function updateInstitution(Request $request, int $id): View
    {
        $this->setActiveSlug('list-institutions/');
        $model = $this->getModel($id, Institution::class, AccessContext::UPDATE);

        $institution = new UpdateInstitution(
            model: $model,
            name: trim($request->get('name', $model->name)),
            educator: trim($request->get('educator', $model->educator)),
            email: trim($request->get('email', $model->email)),
            phone: trim($request->get('phone', $model->phone)),
            seal: trim($request->get('seal', $model->seal)),
            type: trim($request->get('type', $model->type)),
        );

        $response = $this->executeCommand($institution, $request);

        if ($response !== null) {
            $model->refresh();
        }

        return $this->view(
            '@adm/updateInstitution.tpl',
            institution: $institution,
            locations: LocationHelper::getLocationsForForm(),
            types: Type::getForForm(),
            errors: $this->validationParser->parsedErrors,
            success: $response !== null,
        );
    }

    #[Get(uri: '/delete-institution/{id:[0-9]+}/')]
    public function deleteInstitution(int $id): Redirect
    {
        $model = $this->getModel($id, Institution::class, AccessContext::DELETE);
        $institution = new DeleteInstitution($model);

        $response = $this->executeCommand($institution, onPost: false);

        return $this->redirect($this->getBaseSlug() . 'list-institutions/');
    }

    #[
        Get(uri: '/create/{?for:pre-school|school}/'),
        Post(uri: '/create/{?for:pre-school|school}/'),
    ]
    public function createEvent(Request $request, string $for = 'event'): View
    {
        $this->checkModel(Event::class);
        //$location = Location::select()->where('seal = ?', '713')->first(); // implement seal from user
        $location = null;
        $this->setActiveSlug('create/' . ($for !== 'event' ? $for . '/' : ''));
        $form = Form::getFor($for, $location);

        $command = new CreateEvent(
            seal: trim($request->get('seal', '')), // later here seal from user object
            type: trim($request->get('type', '')),
            startDateTime: trim($request->get('startDateTime', '')),
            endTime: trim($request->get('endTime', '')),
            staff: (int) $request->get('staff', 0),
            staffExternal: (int) $request->get('staffExternal', 0),
            attendees: (int) $request->get('attendees', 0),
            online: (int) $request->get('online', 1),
            state: (int) $request->get('state', 1),
            title: trim($request->get('title', '')),
            description: trim($request->get('description', '')),
            audience: trim($request->get('audience', '')),
        );

        $response = $this->executeCommand($command, $request);

        if ($response?->value instanceof Event) {
            $command->reset();
        }

        return $this->view(
            '@adm/createEvent.tpl',
            event: $command,
            locations: LocationHelper::getLocationsForForm(),
            types: $form->getTypes(),
            states: EventHelper::getStateForForm(),
            onlineStates: EventHelper::getOnlineForForm(),
            audiences: EventHelper::getAudienceForForm(),
            errors: $this->validationParser->parsedErrors,
            success: $response !== null,
            timeFactors: $form->getTimeFactors(),
            hiddenFields: $form->getHiddenFields(),
            dataList: $form->getDataList(),
            location: $form->location,
            isBase: $form->isBase(),
            for: $for,
        );
    }

    #[Get(uri: '/update/{id:[0-9]+}/'), Post(uri: '/update/{id:[0-9]+}/')]
    public function updateEvent(Request $request, int $id): View
    {
        $this->setActiveSlug('update/');
        $model = $this->getModel($id, Event::class, AccessContext::UPDATE);

        $command = new UpdateEvent(
            model: $model,
            seal: trim($request->get('seal', $model->seal)),
            type: trim($request->get('type', $model->type)),
            startDateTime: trim($request->get('startDateTime', \DateTime::createFromTimestamp($model->time_start)->format('Y-m-d\TH:i'))),
            endTime: trim($request->get('endTime', \DateTime::createFromTimestamp($model->time_end)->format('H:i'))),
            staff: (int) $request->get('staff', $model->staff),
            staffExternal: (int) $request->get('staffExternal', $model->staff_external),
            attendees: (int) $request->get('attendees', $model->attendees),
            online: (int) $request->get('online', $model->online),
            state: (int) $request->get('state', $model->state),
            title: trim($request->get('title', $model->title)),
            description: trim($request->get('description', $model->description)),
            audience: trim($request->get('audience', $model->audience)),
        );

        $response = $this->executeCommand($command, $request);

        if ($response !== null) {
            $model->refresh();
        }

        return $this->view(
            '@adm/updateEvent.tpl',
            event: $command,
            locations: LocationHelper::getLocationsForForm(),
            types: DBSSection::getForForm(),
            states: EventHelper::getStateForForm(),
            onlineStates: EventHelper::getOnlineForForm(),
            audiences: EventHelper::getAudienceForForm(),
            errors: $this->validationParser->parsedErrors,
            success: $response !== null,
            isBase: true,
        );
    }
}
