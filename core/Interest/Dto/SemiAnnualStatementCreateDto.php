<?php

namespace Interest\Dto;

class SemiAnnualStatementCreateDto
{
    public int $startYearId;
    public int $startMonthId;
    public int $endYearId;
    public int $endMonthId;

    public function getPeriods(): array
    {
        $periods = [];

        $yearId = $this->startYearId;
        $monthId = $this->startMonthId;

        while (true) {
            $periods[] = [
                'year_id' => $yearId,
                'month_id' => $monthId,
            ];

            if (
                $yearId === $this->endYearId &&
                $monthId === $this->endMonthId
            ) {
                break;
            }

            if ($monthId === 12) {
                $monthId = 1;
                $yearId = $this->endYearId;
            } else {
                $monthId++;
            }
        }

        return $periods;
    }

    public function getPreviousPeriod(): array
    {
        $yearId = $this->startYearId;
        $monthId = $this->startMonthId;

        if ($monthId === 1) {
            $monthId = 12;
            $yearId--;
        } else {
            $monthId--;
        }

        return [
            'year_id' => $yearId,
            'month_id' => $monthId,
        ];
    }
}
