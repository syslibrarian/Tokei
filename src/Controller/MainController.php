<?php

declare(strict_types=1);

namespace Tokei\Controller;

use Tokei\Component\Navigation\Navigation;
use Tokei\Model\Page\Page;
use Tempest\Router\Get;
use Tempest\View\View;

final class MainController extends Controller
{
    use IsPublic;

    #[Get(uri: '/')]
    public function index(): View
    {
        return $this->view('base.tpl', test: 'Hallo Welt');
    }
}
