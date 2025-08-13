<?php

namespace App\Services;

use App\Exceptions\InvalidCodeException;
use App\Models\District;
use App\Models\Division;
use App\Models\Province;
use App\Models\Tehsil;
use App\Models\UnionCouncel;

/**
 * World
 */
class Pakistan
{
    public function Provinces()
    {
        return Province::get();
    }

    public static function Divisions()
    {
        return Division::get();
    }

    public static function Districts()
    {
        return District::get();
    }

    public static function Tehsils()
    {
        return Tehsil::get();
    }

    public static function getProvinceByAbbr($abbr)
    {
        return Province::getByAbbr($abbr);
    }

    public static function getDivisionByCode($code)
    {
        return Division::getByCode($code);
    }

    public static function getDistrictByCode($code)
    {
        return District::getByCode($code);
    }

    public static function getTehsilByCode($code)
    {
        return Tehsil::getByCode($code);
    }

    public static function getByCode($code)
    {
        // Ensure the code is a string and convert to lowercase for consistent searching
        $code = strtolower((string) $code);

        $division = null;
        $lookup_code = null;

        // Check if the code contains a hyphen, indicating a combined country and local code
        if (str_contains($code, '-')) {
            [$country_code, $lookup_code] = explode('-', $code, 2);
            $division = self::getDivisionByCode($country_code);
        } else {
            // If no hyphen, the entire code is for a division
            $division = self::getDivisionByCode($code);
        }

        // Check if a division was found; if not, throw an exception
        if (! $division) {
            throw new InvalidCodeException('Invalid division code provided.');
        }

        // Now, determine which model to query based on the division's properties
        if ($division->has_district) {
            // If the division has districts, find the district using the provided code
            return District::where([
                ['division_id', $division->id],
                ['code', $lookup_code],
            ])->first(); // Add ->first() to return a single model instance
        }

        // If the division does not have districts, find a union council
        return UnionCouncel::where([
            ['division_id', $division->id],
            ['code', $lookup_code ?? $code], // Use the lookup_code if it exists, otherwise the full code
        ])->first(); // Add ->first() to return a single model instance
    }
}
