<?php

namespace App\Controller\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

class ProgrammeFilter implements FilterInterface
{
    /**
     * @Assert\Collection
     */
    public array $availableFilters = [
        'orderBy' => [
            'id' => [
                'type' => 'integer',
                'label' => 'Id'
            ],
            'name' => [
                'type' => 'string',
                'label' => 'Name',
            ],
            'description' => [
                'type' => 'string',
                'label' => 'Description',
            ],
            'startTime' => [
                'type' => 'datetime',
                'format' => 'rfc0808',
                'label' => 'Start Date',
            ],
            'endTime' => [
                'type' => 'datetime',
                'format' => 'rfc0808',
                'label' => 'Start Date',
            ],
            'isOnline' => [
                'type' => 'boolean',
                'acceptedValues' => [
                    'da',
                    'nu',
                    true,
                    false
                ],
                'label' => 'Is Online'
            ]
        ],
        'sortOrder' => [
            'ASC' => [
                'type' => 'string'
             ],
            'DESC' => [
                'type' => 'string'
            ],
        ],
        'filterBy' => [
            'id' => [
                'type' => 'integer',
                'label' => 'Id'
            ],
            'name' => [
                'type' => 'string',
                'label' => 'Name',
            ],
            'description' => [
                'type' => 'string',
                'label' => 'Description',
            ],
            'startTime' => [
                'type' => 'datetime',
                'format' => 'rfc0808',
                'label' => 'Start Date',
            ],
            'endTime' => [
                'type' => 'datetime',
                'format' => 'rfc0808',
                'label' => 'Start Date',
            ],
            'isOnline' => [
                'type' => 'boolean',
                'acceptedValues' => [
                    'da',
                    'nu',
                    true,
                    false
                ],
                'label' => 'Is Online'
            ]
        ],
        'perPage' => [
            'limit' => [
                'type' => 'integer',
                'label' => 'Per page'
            ],
            'offset'  => [
                'type' => 'integer',
                'label' => 'Per page'
            ],
        ],
    ];

    /**
     * @Assert\Collection
     * @CustomAssert\ProgrammeFilter()
     */
    public array $requestFilters = [];

    public function handle(Request $request): array
    {
        $programmeFilter = new ProgrammeFilter();
        $programmeFilter->setRequestFilters($request->query->all());

        return $programmeFilter->requestFilters;
    }


    public function getAvailableFilters(): array
    {
        return $this->availableFilters;
    }


    public function setAvailableFilters(array $availableFilters): ProgrammeFilter
    {
        $this->availableFilters = $availableFilters;
        return $this;
    }

    public function getRequestFilters(): array
    {
        return $this->requestFilters;
    }


    public function setRequestFilters(array $requestFilters): array
    {
        $this->requestFilters = $requestFilters;

        return $this->requestFilters;
    }
}
