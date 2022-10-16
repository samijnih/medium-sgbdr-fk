<?php

declare(strict_types=1);

namespace Tests\ECommerce\Acceptance;

use ECommerce\Domain\Feature\Customer\RemoveCustomer;
use ECommerce\Domain\Model\AddressStruct;
use ECommerce\Domain\Model\Customer;
use ECommerce\Infrastructure\Http\Controller\CustomerController;
use PHPUnit\Framework\TestCase;
use Tests\ECommerce\Fake\FakeCustomerRepository;
use Tests\ECommerce\Stub\StubbedImmutableClock;

/**
 * @coversDefaultClass \ECommerce\Infrastructure\Http\Controller\CustomerController
 */
final class RemoveCustomerTest extends TestCase
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
     * @covers ::removeCustomer
     */
    public function itRemovesTheAskedCustomer(): void
    {
        $customerRepository = new FakeCustomerRepository();
        $customerRepository->add($this->arrangeACustomer());

        $actual = $this->sut->removeCustomer(self::CUSTOMER_ID, new RemoveCustomer($customerRepository));

        self::assertSame(204, $actual->getStatusCode());
    }

    /**
     * @test
     * @covers ::removeCustomer
     */
    public function itReturnsANotFoundWhenCustomerDoesNotExistForRemoval(): void
    {
        $actual = $this->sut->removeCustomer('unknown id', new RemoveCustomer(new FakeCustomerRepository()));

        self::assertSame(404, $actual->getStatusCode());
    }
}
