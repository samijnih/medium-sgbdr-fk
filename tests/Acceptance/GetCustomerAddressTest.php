<?php

declare(strict_types=1);

namespace Tests\ECommerce\Acceptance;

use ECommerce\Domain\Feature\Customer\GetCustomerAddress;
use ECommerce\Domain\Model\AddressStruct;
use ECommerce\Domain\Model\Customer;
use ECommerce\Infrastructure\Http\Controller\CustomerController;
use PHPUnit\Framework\TestCase;
use Tests\ECommerce\Fake\FakeCustomerRepository;
use Tests\ECommerce\Stub\StubbedImmutableClock;

/**
 * @coversDefaultClass \ECommerce\Infrastructure\Http\Controller\CustomerController
 */
final class GetCustomerAddressTest extends TestCase
{
    private const CUSTOMER_ID = '97aa4ade-ef76-4fc4-b023-0568630b5117';

    private readonly CustomerController $sut;

    protected function setUp(): void
    {
        $this->sut = new CustomerController();
    }

    private function arrangeACustomer(): Customer
    {
        return new Customer(
            self::CUSTOMER_ID,
            'Sami',
            'foo@gmail.com',
            new AddressStruct(
                'e025b9d3-8308-415a-8fa7-99a239b38625',
                'Chez moi',
                "42 rue de l'univers",
                null,
                '75000',
                'fr',
            ),
            new StubbedImmutableClock(),
        );
    }

    /**
     * @test
     * @covers ::getCustomerAddress
     */
    public function itReturnsAskedCustomerAddress(): void
    {
        $customerRepository = new FakeCustomerRepository();
        $customerRepository->add($this->arrangeACustomer());

        $actual = $this->sut->getCustomerAddress(self::CUSTOMER_ID, new GetCustomerAddress($customerRepository));

        self::assertSame(200, $actual->getStatusCode());
        $expected = [
            'id' => 'e025b9d3-8308-415a-8fa7-99a239b38625',
            'name' => 'Chez moi',
            'street1' => "42 rue de l'univers",
            'street2' => null,
            'zipcode' => '75000',
            'countryCode' => 'fr',
        ];
        self::assertSame($expected, json_decode($actual->getContent(), true));
    }

    /**
     * @test
     * @covers ::getCustomerAddress
     */
    public function itReturnsANotFoundWhenAskingANonExistingCustomer(): void
    {
        $actual = $this->sut->getCustomerAddress('unknown id', new GetCustomerAddress(new FakeCustomerRepository()));

        self::assertSame(404, $actual->getStatusCode());
    }
}
