<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tempest\Http\Method;
use Tempest\Http\Request;
use Tempest\Validation\Exceptions\ValidationFailed;
use Tokei\Command\Command;
use Tokei\Command\Resettable;
use Tokei\Command\Response;
use Tokei\Component\Access\AccessContext;
use Tokei\Component\Access\HttpAccessControl;
use Tokei\Component\Navigation\Navigation;
use Tokei\Component\Validation\ValidationParser;
use Tokei\Extension\Exception\NotFoundException;
use Tokei\Tokei;

use function Tempest\CommandBus\command;
use function Tempest\Container\get;

trait IsAdmin
{
    protected bool $error = false;

    protected ValidationParser $validationParser {
        get {
            return get(ValidationParser::class);
        }
    }

    protected HttpAccessControl $accessControl {
        get {
            return get(Tokei::class)->accessControl;
        }
    }

    protected function beforeInit(): void
    {
        $this->registerNavigation('adm_header');
        $this->registerNavigation($this->getSectionNavigation(), 'adm_section');
        $this->registerViewPath('adm', dirname(__DIR__, 2) . '/views/adm/');
    }

    protected function afterInit(): void
    {
        Navigation::get('adm_header')->setActiveItem('tokei.navigation.header.' . $this->getSectionNavigation());

        if ($this->session->get('success')) {
            $this->setStatus(Status::SUCCESS);
        }
    }

    protected function setActiveItem(string $name): void
    {
        Navigation::get($this->getSectionNavigation())->setActiveItem('tokei.navigation.' . $name);
    }

    protected function executeCommand(Command $command, ?Request $request = null, ?callable $closure = null, bool $onPost = true): ?Response
    {
        if ($onPost === false || $request->method === Method::POST) {
            return $this->sendCommand($command, $closure);
        }

        return null;
    }

    protected function sendCommand(Command $command, ?callable $closure = null): ?Response
    {
        command($command);

        $response = get(Response::class);
        if ($response->value instanceof ValidationFailed) {
            $this->validationParser->parse($response->value);
            $this->register('formErrors', $this->validationParser->parsedErrors);
            $this->setStatus(Status::ERROR);
            return null;
        }

        $this->setStatus(Status::SUCCESS);

        if ($closure !== null) {
            $closure($command, $response);
        }

        if ($command instanceof Resettable) {
            $command->reset();
        }

        return $response;
    }

    /**
     * @template TModel of object
     * @param int $id
     * @param class-string<TModel> $modelClass
     * @param AccessContext|null $context
     * @return TModel
     * @throws NotFoundException
     */
    protected function getModel(int $id, string $modelClass, ?AccessContext $context = null): object
    {
        $model = $modelClass::select()
            ->where('id = ?', $id)
            ->first();

        if ($model === null) {
            throw new NotFoundException($modelClass, $this->getBaseSlug());
        }

        if ($context !== null) {
            $this->checkModel($model, $context);
        }

        return $model;
    }

    /**
     * @template TModel of object
     * @param string $seal
     * @param class-string<TModel> $modelClass
     * @param ?string $timeCode
     * @return TModel
     * @throws NotFoundException
     */
    protected function getBySeal(string $seal, string $modelClass, ?string $timeCode = null, ?AccessContext $context = null): object
    {
        $raw = $modelClass::select();

        if ($timeCode === null) {
            $raw->where('seal = ?', $seal);
        } else {
            $raw->where('seal = ? AND time_code = ?', $seal, $timeCode);
        }

        $model = $raw->first();

        if ($model === null) {
            throw new NotFoundException($modelClass, $this->getBaseSlug());
        }

        if ($context !== null) {
            $this->checkModel($model, $context);
        }

        return $model;
    }

    protected function checkModel(string|object $model, ?AccessContext $context = null): void
    {
        $this->accessControl->checkModel($model, $context);
    }

    public function locatedUser(string $seal = ''): string
    {
        return $this->accessControl->user->seal !== 'all' ? $this->accessControl->user->seal : $seal;
    }

    abstract protected function getSectionNavigation(): string;

    abstract protected function registerNavigation(string $name, ?string $as = null): void;

    abstract protected function registerViewPath(string $namespace, string $path): void;

    abstract protected function getBaseSlug(): string;

    abstract protected function register(string $name, mixed $value): static;

    abstract public function setStatus(Status $status): static;
}
