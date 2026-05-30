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

        Navigation::get('adm_header')->setActiveTarget($this->getBaseSlug());
    }

    protected function setActiveSlug(string $slug): void
    {
        Navigation::get($this->getSectionNavigation())->setActiveTarget($this->getBaseSlug() . $slug);
    }

    protected function sendCommand(Command $command, Request $request, ?callable $closure = null): ?Response
    {
        if ($request->method === Method::POST) {
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

        return null;
    }

    abstract protected function getSectionNavigation(): string;

    abstract protected function registerNavigation(string $name): void;

    abstract protected function registerViewPath(string $namespace, string $path): void;

    abstract protected function getBaseSlug(): string;
}
