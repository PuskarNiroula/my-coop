<?php

namespace Deposit\Dto;

class CreateDepositDto
{
    public int $memberId;
    public int $monthId;
    public int $amount;
    public ?string $description;

}
