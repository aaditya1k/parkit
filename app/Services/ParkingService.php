<?php

namespace App\Services;

class ParkingService
{
    const CHARGE_METHOD_PER_HOUR = 1;
    const CHARGE_METHOD_IN_CATEGORY = 2;

    private $chargeMethods = [
        self::CHARGE_METHOD_PER_HOUR => 'Per Hour',
        self::CHARGE_METHOD_IN_CATEGORY => 'In Category'
    ];

    public function getChargeMethods()
    {
        return $this->chargeMethods;
    }

    public function getChargeMethod($index)
    {
        return $this->chargeMethods[$index];
    }

    public function generateMap($rows, $cols, $mapIndexes)
    {
        $tools = [
            'fa-long-arrow-up',
            'fa-long-arrow-down',
            'fa-long-arrow-left',
            'fa-long-arrow-right',
            'fa-car',
            'fa-motorcycle',
            'fa-sign-in',
            'fa-sign-out',
            'fa-stop',
            'fa-id-card-o',
            '',
        ];

        $html = "";
        $totalI = 0;
        for ($i = 0; $i < $rows; $i++) {
            $html .= '<div class="gen-m-rows">';

            for ($j = 0; $j < $cols; $j++) {

                if ($mapIndexes[$totalI] != null) {
                    $html .= '<div class="gen-m-cols">';
                    $html .= '<i class="fa '.$tools[ $mapIndexes[$totalI] ].'" aria-hidden="true"></i>';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="gen-m-cols gen-invi"></div>';
                }

                ++$totalI;
            }
            $html .= '</div>';
        }
        return $html;
    }
}