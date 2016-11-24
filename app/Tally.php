<?php

namespace Codesta\Beer0Meter;

use Illuminate\Support\Collection;

final class Tally
{
    public function __construct()
    {
        $this->contents = $this->getFileContents();
    }

    public function add(string $username, int $count)
    {
        if ($count === 0) {
            throw new \InvalidArgumentException("Count should be a negative or positive integer");
        }

        $this->contents->push(['username' => $username, 'created_at' => date(DATE_RFC3339), 'count' => $count]);
        $this->save();
    }

    public function countByUsername(string $username): int
    {
        return $this->contents
            ->groupBy('username')
            ->get($username)
            ->sum(function (array $item) {
                return $item['count'];
            });
    }

    public function stats(): Collection
    {
        return $this->contents
            ->groupBy('username')
            ->map(function (Collection $items, string $username) {
                return $this->countByUsername($username);
            });
    }

    private function save()
    {
        file_put_contents($this->getFile(), json_encode($this->contents->toArray(), JSON_NUMERIC_CHECK));
    }

    private function getFileContents(): Collection
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
