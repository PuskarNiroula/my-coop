<?php

namespace Expense\Dto;

class ExpenseRecordCreateDto
{
    public int $expense_head_id;
    public float $amount;
    public ?string $description = null;
    public int $created_by;

    public function fromArray(array $data): self
    {
        $this->expense_head_id = (int) $data['expense_head_id'];
        $this->amount = (float) $data['amount'];
        $this->description = $data['description'] ?? null;

        return $this;
    }
}
