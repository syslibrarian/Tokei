<?php

declare(strict_types=1);

namespace Tokei\Tool\Statistic;

use Tokei\Model\Klr\KlrHelper;
use Tokei\Model\Klr\KlrReport;
use Tokei\Model\Location\Location;

final class KlrPrinter
{
    protected(set) array $fieldToProduct= [];
    protected(set) array $products = [];
    protected(set) array $tableMonths = [1 => 'Jan', 2 => 'Feb', 3 => 'März', 4 => 'Apr', 5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'Aug', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Dec', 'year' => 'Gesamt'];

    /**
     * @param KlrReport[] $klrReports
     * @param Location[] $locations
     */
    public function __construct(protected(set) array $klrReports, protected(set) ?array $locations = null)
    {
        $this->createStructure();
        $this->build();
    }

    protected function createStructure(): void
    {
        foreach (KlrHelper::KLR_PRODUCTS as $number => $information) {
            $this->fieldToProduct[$information['field']] = $number;
            $this->products[$number] = [
                'title' => $information['title'],
                'location' => [],
                'sum' => $this->getContainers(true)
            ];
        }
    }

    protected function getContainers(bool $forSum = false): array
    {
        $containers = [];
        foreach ($this->tableMonths as $key => $month) {
            $containers[$key] = ($key === 'year' || $forSum) ? new Cell(0, 0) : null;
        }

        return $containers;
    }

    protected function build(): void
    {
        foreach ($this->klrReports as $report) {
            foreach ($this->fieldToProduct as $field => $number) {
                if (!isset($this->products[$number]['location'][$report->seal])) {
                    $this->products[$number]['location'][$report->seal] = [
                        'klrCode' => $report->seal,
                        'data' => $this->getContainers()
                    ];
                }

                $this->products[$number]['sum'][$report->month]->value += $report->$field;
                $this->products[$number]['sum']['year']->value += $report->$field;
                $this->products[$number]['location'][$report->seal]['data'][$report->month] = new Cell($report->$field, $report->report_status);
                $this->products[$number]['location'][$report->seal]['data']['year']->value += $report->$field;
            }
        }
    }
}
