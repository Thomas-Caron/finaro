<?php

declare(strict_types=1);

namespace App\DataEntity\App\Account\Movement;

use App\DataEntity\App\Account\Movement\AccountMovementData;

class AccountMovementCollectionData
{
    private array $collection = [];

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function setCollection(array $collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    public function addCollection(AccountMovementData $data): static
    {
        $this->collection[] = $data;
        return $this;
    }

    public function removeFixedExpense(AccountMovementData $data): static
    {
        $this->collection = array_filter($this->collection, fn($item) => $item !== $data);
        
        return $this;
    }
}
