<?php

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;

beforeEach(function () {
    $this->moneyCast = new MoneyCast;
    $this->model = Mockery::mock(Model::class);
});

afterEach(function () {
    Mockery::close();
});

it('casts cents to dollars', function () {
    $key = 'price';
    $value = 12345; // cents
    $attributes = [];

    $result = $this->moneyCast->get($this->model, $key, $value, $attributes);

    expect($result)->toBe(123.45);
});

it('casts dollars to cents', function () {
    $key = 'price';
    $value = 123.45; // dollars
    $attributes = [];

    $result = $this->moneyCast->set($this->model, $key, $value, $attributes);

    expect($result)->toBe(12345.0);
});

it('rounds set result to nearest cent', function () {
    $key = 'price';
    $value = 123.999; // dollars
    $attributes = [];

    $result = $this->moneyCast->set($this->model, $key, $value, $attributes);

    expect($result)->toBe(12400.0);
});
