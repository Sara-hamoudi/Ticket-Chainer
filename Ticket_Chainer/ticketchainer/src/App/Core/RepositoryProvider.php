<?php

use InvalidArgumentException;

class RepositoryProvider
{

    protected $repositories;


    public function __construct()
    {
        $this->repositories = [];
    }

    public function getRepository(string $modelClass): RepositoryInterface
    {
        if (!isset($this->repositories[$modelClass])) {
            throw new InvalidArgumentException("Could find any repository for $modelClass");
        }
        return $this->repositories[$modelClass];
    }

    public function addRepository(RepositoryInterface $repository): void
    {
        $this->repositories[$repository->getModelClass()] = $repository;
    }


}