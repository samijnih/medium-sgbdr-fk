<?php

declare(strict_types=1);

namespace Tests\ECommerce\Acceptance;

use ECommerce\Domain\Feature\Customer\DoSignUpCustomer;
use ECommerce\Infrastructure\Http\Controller\CustomerController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\ECommerce\Fake\FakeCustomerRepository;
use Tests\ECommerce\Stub\StubbedImmutableClock;

/**
 * @coversDefaultClass \ECommerce\Infrastructure\Http\Controller\CustomerController
 */
final class CustomerCanSignUpTest extends TestCase
{
    private readonly CustomerController $sut;

    protected function setUp(): void
    {
        $this->sut = new CustomerController();
    }

    private function buildValidSignUpRequest(): Request
    {
        return new Request([], [
            'name' => 'Sami',
            'email' => 'foo@gmail.com',
            'addressName' => 'Chez moi',
            'street1' => "42 rue de l'univers",
            'zipcode' => '75000',
            'countryCode' => 'fr',
        ]);
    }

    /**
     * @test
     * @covers ::signUpCustomer
     */
    public function itSignsUpACustomerWithAValidPayload(): void
    {
        $actual = $this->sut->signUpCustomer(
            $this->buildValidSignUpRequest(),
            new DoSignUpCustomer(
                new FakeCustomerRepository(),
                new StubbedImmutableClock(),
            )
        );

        self::assertSame(201, $actual->getStatusCode());
    }

    public function provideInvalidPayloads(): iterable
    {
        yield 'empty name' => [
            new Request([], [
                'name' => '',
                'email' => 'foo@gmail.com',
                'addressName' => 'Chez moi',
                'street1' => "42 rue de l'univers",
                'zipcode' => '75000',
                'countryCode' => 'fr',
            ]),
        ];

        yield 'bad email' => [
            new Request([], [
                'name' => 'Sami',
                'email' => 'foo',
                'addressName' => 'Chez moi',
                'street1' => "42 rue de l'univers",
                'zipcode' => '75000',
                'countryCode' => 'fr',
            ]),
        ];

        yield 'empty address name' => [
            new Request([], [
                'name' => 'Sami',
                'email' => 'foo@gmail.com',
                'addressName' => '',
                'street1' => "42 rue de l'univers",
                'zipcode' => '75000',
                'countryCode' => 'fr',
            ]),
        ];

        yield 'empty street1' => [
            new Request([], [
                'name' => 'Sami',
                'email' => 'foo@gmail.com',
                'addressName' => 'Chez moi',
                'street1' => '',
                'zipcode' => '75000',
                'countryCode' => 'fr',
            ]),
        ];

        yield 'bad street2' => [
            new Request([], [
                'name' => 'Sami',
                'email' => 'foo@gmail.com',
                'addressName' => 'Chez moi',
                'street1' => "42 rue de l'univers",
                'street2' => '',
                'zipcode' => '75000',
                'countryCode' => 'fr',
            ]),
        ];

        yield 'empty zipcode' => [
            new Request([], [
                'name' => 'Sami',
                'email' => 'foo@gmail.com',
                'addressName' => 'Chez moi',
                'street1' => "42 rue de l'univers",
                'zipcode' => '123',
                'countryCode' => 'fr',
            ]),
        ];

        yield 'empty country code' => [
            new Request([], [
                'name' => 'Sami',
                'email' => 'foo@gmail.com',
                'addressName' => 'Chez moi',
                'street1' => "42 rue de l'univers",
                'zipcode' => '75000',
                'countryCode' => 'fra',
            ]),
        ];
    }

    /**
     * @test
     * @covers ::signUpCustomer
     * @dataProvider provideInvalidPayloads
     */
    public function itDoesNotSignUpACustomerWithAnInvalidPayload(Request $request): void
    {
        $actual = $this->sut->signUpCustomer($request, new DoSignUpCustomer(
            new FakeCustomerRepository(),
            new StubbedImmutableClock(),
        ));

        self::assertSame(422, $actual->getStatusCode());
    }
}
