<?php

declare(strict_types=1);

namespace Tests\ECommerce\Integration;

use Doctrine\DBAL\Connection;
use ECommerce\Domain\Model\AddressStruct;
use ECommerce\Domain\Model\Customer;
use ECommerce\Infrastructure\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\ECommerce\Stub\StubbedImmutableClock;

/**
 * @coversDefaultClass \ECommerce\Infrastructure\Repository\CustomerRepository
 */
final class CustomerRepositoryTest extends KernelTestCase
{
    private CustomerRepository $sut;
    private StubbedImmutableClock $immutableClock;
    private Connection $connection;

    protected function setUp(): void
    {
        self::createKernel();

        $this->sut = self::getContainer()->get(CustomerRepository::class);
        $this->immutableClock = new StubbedImmutableClock();
        $this->connection = self::getContainer()->get(Connection::class);
    }

    /**
     * @covers ::get
     * @test
     */
    public function itReturnsACustomer(): void
    {
        $customerId = $this->arrangeCustomer();

        $actual = $this->sut->get($customerId);

        $expected = new Customer(
            '8a38fe62-a654-4dbb-972b-fd9d987d29dd',
            'Sami',
            'foo@gmail.com',
            new AddressStruct(
                '5ee6b169-a1be-40c8-af6d-9461010f6668',
                'Chez moi',
                "42 rue de l'univers",
                null,
                '75000',
                'fr',
            ),
            new StubbedImmutableClock(),
        );
        self::assertEquals($expected, $actual);
    }

    /**
     * @covers ::add
     * @test
     */
    public function itAddsACustomer(): void
    {
        $customer = new Customer(
            $customerId = 'e7db67af-0348-4da1-906c-3273c2a9c245',
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

        $this->sut->add($customer);

        $customerAddress = $this->connection->fetchAssociative('SELECT * FROM customer_address WHERE customer_id = :customerId', ['customerId' => $customerId]);
        $expected = [
            'id' => 'e025b9d3-8308-415a-8fa7-99a239b38625',
            'customer_id' => 'e7db67af-0348-4da1-906c-3273c2a9c245',
            'name' => 'Chez moi',
            'street1' => "42 rue de l'univers",
            'street2' => null,
            'zipcode' => '75000',
            'country_code' => 'fr',
            'created_at' => '1995-05-01 14:05:00+00',
            'updated_at' => null,
        ];
        self::assertSame($expected, $customerAddress);

        $customer = $this->connection->fetchAssociative('SELECT * FROM customer WHERE id = :customerId', ['customerId' => $customerId]);
        $expected = [
            'id' => 'e7db67af-0348-4da1-906c-3273c2a9c245',
            'name' => 'Sami',
            'email' => 'foo@gmail.com',
            'created_at' => '1995-05-01 14:05:00+00',
            'updated_at' => null,
        ];
        self::assertSame($expected, $customer);
    }

    /**
     * @covers ::remove
     * @test
     */
    public function itRemovesAnExistingCustomerWithItsAssociatedAddress(): void
    {
        $customerId = $this->arrangeCustomer();

        $this->sut->remove($customerId);

        $customerAddressHasNotBeenRemoved = (bool) $this->connection->fetchOne('SELECT count(id) FROM customer_address WHERE customer_id = :customerId', ['customerId' => $customerId]);
        self::assertFalse($customerAddressHasNotBeenRemoved, 'Customer address has not been removed as expected!');

        $customerHasNotBeenRemoved = (bool) $this->connection->fetchOne('SELECT count(id) FROM customer WHERE id = :customerId', ['customerId' => $customerId]);
        self::assertFalse($customerHasNotBeenRemoved, 'Customer has not been removed as expected!');
    }

    private function arrangeCustomer(): string
    {
        $customerId = '8a38fe62-a654-4dbb-972b-fd9d987d29dd';

        $this->connection->insert('customer_address', [
            'id' => '5ee6b169-a1be-40c8-af6d-9461010f6668',
            'customer_id' => $customerId,
            'name' => 'Chez moi',
            'street1' => "42 rue de l'univers",
            'street2' => null,
            'zipcode' => '75000',
            'country_code' => 'fr',
            'created_at' => $this->immutableClock->now()->format(DATE_ATOM),
        ]);
        $this->connection->insert('customer', [
            'id' => $customerId,
            'name' => 'Sami',
            'email' => 'foo@gmail.com',
            'created_at' => $this->immutableClock->now()->format(DATE_ATOM),
        ]);

        return $customerId;
    }
}
