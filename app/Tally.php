<?php

namespace Codesta\Beer0Meter;

use Illuminate\Support\Collection;

final class Tally
{
    private $tally;

    public function __construct()
    {
        $this->tally = $this->getTally();
    }

    public function add(string $username, int $count)
    {
        if ($count === 0) {
            throw new \InvalidArgumentException("Count should be a negative or positive integer");
        }

        // If less then the total amount, set the count to zero
        if ($count < 0) {
            $countByUsername = $this->countByUsername($username);

            if (abs($count) > $countByUsername) {
                $count = $countByUsername * -1;
            }
        }

        $this->tally->push(['username' => $username, 'created_at' => date(DATE_RFC3339), 'count' => $count]);
        $this->save();
    }

    public function countByUsername(string $username): int
    {
        return $this->tally
            ->groupBy('username')
            ->get($username)
            ->sum(function (array $item) {
                return $item['count'];
            });
    }

    public function totals(): Collection
    {
        return $this->tally
            ->groupBy('username')
            ->map(function (Collection $items, string $username) {
                return $this->countByUsername($username);
            });
    }

    public function usernames(): Collection
    {
        return $this->tally
            ->groupBy('username')
            ->keys();
    }

    public function tally(): Collection
    {
        return $this->tally;
    }

    private function save()
    {
        file_put_contents($this->getFile(), json_encode($this->tally->toArray(), JSON_NUMERIC_CHECK));
    }

    private function getTally(): Collection
    {
        return collect(json_decode(file_get_contents($this->getFile()), true));
    }

    private function getFile(): string
    {
        $file = storage_path('tally.json');

        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }

        return $file;
    }
}
