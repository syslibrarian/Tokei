<?php

declare(strict_types=1);

namespace Tokei\Tool\Statistic;

use Tokei\Model\Klr\KlrHelper;
use Tokei\Model\Klr\KlrReport;
use Tokei\Model\Location\Location;

final class KlrPrinter
{
    private(set) array $fieldToProduct = [];
    private(set) array $products = [];
    private(set) array $months = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'März',
        4 => 'Apr',
        5 => 'Mai',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Aug',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Dec',
        'year' => 'Gesamt',
    ];

    /**
     * @param KlrReport[] $klrReports
     * @param Location[] $locations
     */
    public function __construct(
        protected(set) array $klrReports,
        protected(set) ?array $locations = null,
    ) {
        $this->createStructure();
        $this->build();
    }

    private function createStructure(): void
    {
        // buid structure
        foreach (KlrHelper::KLR_PRODUCTS as $number => $information) {
            $this->fieldToProduct[$information['field']] = $number;
            $this->products[$number] = [
                'title' => $information['title'],
                'locations' => [],
                'sum' => $this->getContainers(true),
            ];
        }
    }

    private function getContainers(bool $forSum = false): array
    {
        $containers = [];
        foreach ($this->months as $key => $month) {
            $containers[$key] = $key === 'year' || $forSum ? new Cell(0, 0) : null;
        }

        return $containers;
    }

    private function build(): void
    {
        foreach ($this->klrReports as $report) {
            foreach ($this->fieldToProduct as $field => $number) {
                if (! isset($this->products[$number]['locations'][$report->seal])) {
                    $this->products[$number]['locations'][$report->seal] = [
                        'klrCode' => $this->locations[$report->seal]->klr_code ?? $report->seal,
                        'data' => $this->getContainers(),
                    ];
                }

                $this->products[$number]['sum'][$report->month]->value += $report->$field;
                $this->products[$number]['sum']['year']->value += $report->$field;
                $this->products[$number]['locations'][$report->seal]['data'][$report->month] = new Cell($report->$field, $report->report_status);
                $this->products[$number]['locations'][$report->seal]['data']['year']->value += $report->$field;
            }
        }
    }
}
