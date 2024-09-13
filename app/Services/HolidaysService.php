<?php

namespace App\Services;

use Carbon\Carbon;

class HolidaysService
{
    protected $manualHolidays = [
        '2024' => [
            'jour 1 année' => '2024-01-01',
            'Fête du Travail' => '2024-05-01',
            'Soulevement populaire' => '2024-01-01',
            'Fête de la femme' => '2024-03-08',
            'Lundi de pâques' => '2024-04-01',
            'Ramadan' => '2024-04-10',
            'Ascension' => '2024-05-09',
            'Journée des Coutumes et Traditions' => '2024-05-15',
            'Eid-Al-Kabîr (Tabaski)' => '2024-06-16',
            'Burkina Independence Day' => '2024-08-05',
            'Assomption' => '2024-08-15',
            'Maouloud' => '2024-09-16',
            'Toussaint' => '2024-11-01',
            'Commémoration de l’indépendance' => '2024-12-11',
            'Noël' => '2024-12-25',
            'insurection populaire jour 1' => '2024-10-30',
            'insurection populaire jour 2' => '2024-10-31',
        ],
        '2025' => [
            'jour 1 année' => '2025-01-01',
            'Fête du Travail' => '2025-05-01',
            'Soulevement populaire' => '2025-01-01',
            'Fête de la femme' => '2025-03-08',
            'Lundi de pâques' => '2025-04-01',
            'Ramadan' => '2025-04-10',
            'Ascension' => '2025-05-09',
            'Journée des Coutumes et Traditions' => '2025-05-15',
            'Eid-Al-Kabîr (Tabaski)' => '2025-06-16',
            'Burkina Independence Day' => '2025-08-05',
            'Assomption' => '2025-08-15',
            'Maouloud' => '2025-09-16',
            'Toussaint' => '2025-11-01',
            'Commémoration de l’indépendance' => '2025-12-11',
            'Noël' => '2025-12-25',
            'insurection populaire jour 1' => '2025-10-30',
            'insurection populaire jour 2' => '2025-10-31',
        ],

        '2026' => [
            'jour 1 année' => '2026-01-01',
            'Fête du Travail' => '2026-05-01',
            'Soulevement populaire' => '2026-01-01',
            'Fête de la femme' => '2026-03-08',
            'Lundi de pâques' => '2026-04-01',
            'Ramadan' => '2026-04-10',
            'Ascension' => '2026-05-09',
            'Journée des Coutumes et Traditions' => '2026-05-15',
            'Eid-Al-Kabîr (Tabaski)' => '2026-06-16',
            'Burkina Independence Day' => '2026-08-05',
            'Assomption' => '2026-08-15',
            'Maouloud' => '2026-09-16',
            'Toussaint' => '2026-11-01',
            'Commémoration de l’indépendance' => '2026-12-11',
            'Noël' => '2026-12-25',
            'insurection populaire jour 1' => '2026-10-30',
            'insurection populaire jour 2' => '2026-10-31',
        ],
    ];

    /**
     * Récupère les jours fériés manuels pour une année donnée.
     *
     * @param int $year
     * @return array
     */
    public function getHolidays($year)
    {
        if (!isset($this->manualHolidays[$year])) {
            return [];
        }

        return array_map(function ($date) {
            return Carbon::parse($date);
        }, $this->manualHolidays[$year]);
    }

    /**
     * Vérifie si une date est un jour non ouvrable (dimanche ou jour férié).
     *
     * @param Carbon $date
     * @return bool
     */
    public function isNonWorkingDay(Carbon $date)
    {
        // Vérification du dimanche
        if ($date->isSunday()) {
            return true;
        }

        // Vérification des jours fériés
        $holidays = $this->getHolidays($date->year);
        foreach ($holidays as $holiday) {
            if ($holiday->isSameDay($date)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compte les jours ouvrables entre deux dates (lundi à samedi, jours fériés exclus).
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    public function countWorkingDays(Carbon $startDate, Carbon $endDate)
    {
        $totalDays = 0;
        while ($startDate->lte($endDate)) {
            // Si ce n'est ni un dimanche ni un jour férié
            if (!$this->isNonWorkingDay($startDate)) {
                $totalDays++;
            }
            $startDate->addDay();
        }

        return $totalDays;
    }
}
