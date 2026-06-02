<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Prefix;
use Tempest\View\View;
use Tokei\Command\Institution\CreateInstitution;
use Tokei\Command\Institution\DeleteInstitution;
use Tokei\Command\Institution\UpdateInstitution;
use Tokei\Model\Institution\Institution;
use Tokei\Model\Institution\Type;
use Tokei\Model\Location\LocationHelper;

use Tokei\Tool\Pagination\Pagination;
use function Tokei\Str\trim;

#[Prefix('/adm/events')]
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

    #[Get(uri: '/')]
    public function index(): View
    {
        return $this->view('@adm/events.tpl');
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
            pagination: $pagination ,
            institutions: $institutions
        );
    }

    #[Get(uri: '/create-institution/'), Post(uri: '/create-institution/')]
    public function createInstitution(Request $request): View|Redirect
    {
        $this->setActiveSlug('create-institution/');

        $institution = new CreateInstitution(
            name: trim($request->get('name', '')),
            educator: trim($request->get('educator', '')),
            email: trim($request->get('email', '')),
            phone: trim($request->get('phone', '')),
            seal: trim($request->get('seal', '')),
            type: (int) $request->get('type', 0),
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

        $model = $this->getModel($id, Institution::class);
        $institution = new UpdateInstitution(
            model: $model,
            name: trim($request->get('name', $model->name)),
            educator: trim($request->get('educator', $model->educator)),
            email: trim($request->get('email', $model->email)),
            phone: trim($request->get('phone', $model->phone)),
            seal: trim($request->get('seal', $model->seal)),
            type: (int) $request->get('type', $model->type)
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
            success: $response !== null
        );
    }

    #[Get(uri: '/delete-institution/{id:[0-9]+}/')]
    public function deleteInstitution(int $id): Redirect
    {
        $model = $this->getModel($id, Institution::class);
        $institution = new DeleteInstitution($model);

        $response = $this->executeCommand($institution, onPost: false);

        return $this->redirect($this->getBaseSlug() . 'list-institutions/');
    }
}
