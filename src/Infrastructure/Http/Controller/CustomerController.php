<?php

declare(strict_types=1);

namespace ECommerce\Infrastructure\Http\Controller;

use ECommerce\Domain\Feature\Customer\DoSignUpCustomer;
use ECommerce\Domain\Feature\Customer\GetCustomerAddress;
use ECommerce\Domain\Feature\Customer\RemoveCustomer;
use ECommerce\Domain\Feature\Customer\SignUpCustomer;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class CustomerController
{
    #[Route('/customers/{customerId}/address', methods: ['GET', 'HEAD'])]
    public function getCustomerAddress(string $customerId, GetCustomerAddress $service): JsonResponse
    {
        try {
            $address = $service($customerId);
        } catch (Throwable) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return JsonResponse::fromJsonString(\json_encode($address));
    }

    #[Route('/customers/sign-up', methods: ['POST', 'HEAD'])]
    public function signUpCustomer(Request $request, DoSignUpCustomer $service): JsonResponse
    {
        try {
            $service(
                new SignUpCustomer(
                    $request->request->get('name', ''),
                    $request->request->get('email', ''),
                    $request->request->get('addressName', ''),
                    $request->request->get('street1', ''),
                    $request->request->get('street2', null),
                    $request->request->get('zipcode', ''),
                    $request->request->get('countryCode', ''),
                )
            );
        } catch (InvalidArgumentException) {
            return new JsonResponse(null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/customers/{customerId}', methods: ['DELETE', 'HEAD'])]
    public function removeCustomer(string $customerId, RemoveCustomer $service): JsonResponse
    {
        try {
            $service($customerId);
        } catch (Throwable) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
