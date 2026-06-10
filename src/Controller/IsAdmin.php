<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tokei\Command\Command;
use Tokei\Command\Response;
use Tokei\Component\Navigation\Navigation;
use Tokei\Component\Validation\ValidationParser;
use Tempest\Http\Method;
use Tempest\Http\Request;
use Tempest\Validation\Exceptions\ValidationFailed;

use Tokei\Controller\Exception\NotFoundException;
use function Tempest\CommandBus\command;
use function Tempest\Container\get;

trait IsAdmin
{
    protected ValidationParser $validationParser {
        get {
            return get(ValidationParser::class);
        }
    }

    protected function extend(): void
    {
        $this->registerNavigation('adm_header');
        $this->registerNavigation($this->getSectionNavigation());
        $this->registerViewPath('adm', dirname(__DIR__, 2) . '/views/adm/');
        parent::extend();

        $baseSlug = (str_starts_with('/', $this->getBaseSlug())) ? $this->getBaseSlug() : '/' . $this->getBaseSlug();

        $this->tokei->add(
            'route_base',
            (str_ends_with('/', $baseSlug)) ? $baseSlug : $baseSlug . '/',
        );
        Navigation::get('adm_header')->setActiveTarget($this->getBaseSlug());
    }

    protected function setActiveSlug(string $slug): void
    {
        $this->tokei->add('route_current', $slug);
        $this->tokei->add(
            'route_current',
            (str_ends_with('/', $slug)) ? $slug : $slug . '/',
        );
        Navigation::get($this->getSectionNavigation())->setActiveTarget($this->getBaseSlug() . $slug);
    }

    protected function executeCommand(Command $command, ?Request $request = null, ?callable $closure = null, bool $onPost = true): ?Response
    {
        if ($onPost === false || $request->method === Method::POST) {
            return $this->sendCommand($command);
        }

        return null;
    }

    protected function sendCommand(Command $command, ?callable $closure = null): ?Response
    {
        command($command);

        $response = get(Response::class);
        if ($response->value instanceof ValidationFailed) {
            $this->validationParser->parse($response->value);
            return null;
        }

        if ($closure !== null) {
            $closure($command, $response);
        }

        return $response;
    }

    abstract protected function getSectionNavigation(): string;

    abstract protected function registerNavigation(string $name): void;

    abstract protected function registerViewPath(string $namespace, string $path): void;

    abstract protected function getBaseSlug(): string;

    /**
     * @template TModel
     * @param int $id
     * @param class-string<TModel> $modelClass
     * @return TModel
     * @throws NotFoundException
     */
    protected function getModel(int $id, string $modelClass): object
    {
        $model = $modelClass::select()
            ->where('id = ?', $id)
            ->first();

        if ($model === null) {
            throw new NotFoundException($modelClass, $this->getBaseSlug());
        }

        return $model;
    }

    /**
     * @template TModel
     * @param string $seal
     * @param class-string<TModel> $modelClass
     * @param ?string $timeCode
     * @return TModel
     * @throws NotFoundException
     */
    protected function getBySeal(string $seal, string $modelClass, ?string $timeCode = null): object
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

        return $model;
    }
}
