<?php

declare(strict_types=1);

namespace Tokei\Model\Institution;

use Tempest\Database\IsDatabaseModel;
use Tempest\Database\Table;
use Tempest\Validation\Rules\IsEmail;
use Tempest\Validation\Rules\IsNotEmptyString;
use Tempest\Validation\Rules\IsPhoneNumber;
use Tokei\Extension\Validation\Rules\IsInstitutionType;
use Tokei\Model\IsLocated;
use Tokei\Model\Located;

#[Table(name: 'institution')]
final class Institution implements Located
{
    use IsDatabaseModel;
    use IsLocated;

    #[IsNotEmptyString]
    public string $name;

    #[IsNotEmptyString]
    public string $educator;

    #[IsEmail]
    public ?string $email;

    #[IsPhoneNumber]
    public ?string $phone;

    #[IsInstitutionType]
    public string $type;

    public int $created;

    public ?int $modified;

    public ?int $last_event;
}
