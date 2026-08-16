<?php

declare(strict_types=1);

namespace App\Tests\Unit\Modules\Player\Presentation\Http\Request;

use App\Modules\Player\Presentation\Http\Request\CreatePlayerRequest;
use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\TestCase;

final class CreatePlayerRequestTest extends TestCase
{
    public function testItAllowsMissingBirthDateButRejectsInvalidFormat(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();

        $missingBirthDate = CreatePlayerRequest::fromArray([
            'documentType' => 'CC',
            'firstName' => 'Juan',
            'lastName' => 'Rodas',
            'documentNumber' => '1088329031',
        ]);

        self::assertCount(0, $validator->validate($missingBirthDate));

        $invalidBirthDate = CreatePlayerRequest::fromArray([
            'documentType' => 'CC',
            'firstName' => 'Juan',
            'lastName' => 'Rodas',
            'birthDate' => '31-07-2012',
            'documentNumber' => '1088329031',
        ]);

        self::assertGreaterThan(0, $validator->validate($invalidBirthDate)->count());
    }
}
