<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

beforeEach(function () {
    Route::webconsole();
});

it('can access web console', function () {
    get(route('web-console-default.index'))
        ->assertOk();
});

it('can interact with web console', function () {
    postJson(route('web-console-default.interact'), [
        'command' => 'pwd',
        'working_directory' => base_path(),
    ])
    ->assertOk()
    ->assertJson(function (AssertableJson $json) {
        return $json->whereContains('working_directory', getcwd())
            ->whereContains('output', getcwd().PHP_EOL)
            ->etc();
    });
});
