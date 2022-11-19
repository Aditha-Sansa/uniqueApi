<?php

namespace App\Actions;

use App\Models\Code;
use Illuminate\Support\Facades\DB;

class RandomAlphaNumeric
{
    public function create(int $count, int $length)
    {
        $begin = microtime(true);
        $insertData = $this->generatedData($count, $length);

        DB::connection()->disableQueryLog();
        DB::beginTransaction();

        $chunks = array_chunk($insertData, 2000);
        foreach ($chunks as $chunk){
            try {
                Code::query()->insert($chunk);
            } catch (\Illuminate\Database\QueryException $ex){
                if ($ex->getCode() === "23000") {
                    $fetchedNewChunk = $this->generatedData(2000, $length);
                    Code::query()->insert($fetchedNewChunk);
                }
            }
        }

        DB::commit();
        $end = microtime(true) - $begin;

        return 'data generated and table populated in '. $end .'s';
    }

    private function generatedData(int $count, int $length): array
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $insertData = [];
        for ($i = 0; $i < $count; $i++){
            $insertData[] = [
                'unique_code' => self::stringFromAlphabet($pool, $length)
            ];
        }

        return $insertData;
    }


    private static function stringFromAlphabet(string $alphabet, int $length): string
    {
        $str = '';

        // determine the base of the alphabet
        $base = \strlen($alphabet);
        // for every requested character
        for ($i = 0; $i < $length; $i++) {
            // generate a random index within the alphabet
            $index = self::intBelow($base);
            // add the corresponding digit to the output
            $str .= $alphabet[$index];
        }

        return $str;
    }

    private static function intBelow(int $bound): int
    {
        return self::intBetween(0, $bound - 1);
    }

    private static function intBetween(int $min, int $max): int
    {
        return \random_int($min, $max);
    }
}
