<?php

use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\PartnerResource\Pages\ListPartners;
use App\Models\Neighbourhood;
use App\Models\Partner;

use function Pest\Livewire\livewire;

/** Render */
it('can render Partner list', function () {
    $this->get(PartnerResource::getUrl())->assertSuccessful();
});

it('can List Partners', function () {
    $partners = Partner::factory(10)->create();

    livewire(ListPartners::class)
        ->assertCanSeeTableRecords($partners)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('address')
        ->assertTableColumnExists('address')
        ->assertCanRenderTableColumn('neighbourhood.name')
        ->assertTableColumnExists('neighbourhood.name');
});

/** Search */
it('can search Partners by name', function () {
    $partners = Partner::factory(10)->create();
    $name = $partners->first()->name;

    livewire(ListPartners::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($partners->where('name', $name))
        ->assertCanNotSeeTableRecords($partners->where('name', '!==', $name));
});

it('can search Partners by address', function () {
    $partners = Partner::factory(10)->create();
    $address = $partners->first()->address;

    $filteredPartners = $partners->filter(function (Partner $partner) use ($address) {
        return $partner->adress === $address;
    });
    livewire(ListPartners::class)
        ->searchTable($address)
        ->assertCanSeeTableRecords($filteredPartners)
        ->assertCanNotSeeTableRecords($partners->diff($filteredPartners));
});

it('can search Partners by neighbourhood', function () {
    $neighbourhoods = Neighbourhood::factory(3)->create();
    $partners = Partner::factory(10)->create()->each(function (Partner $partner) use ($neighbourhoods) {
        $partner->neighbourhood()->associate($neighbourhoods->random());
        $partner->save();
    });

    $neighbourhood = $neighbourhoods->first();
    $filteredPartners = $partners->filter(function (Partner $partner) use ($neighbourhood) {
        return $partner->neighbourhood->getKey() === $neighbourhood->getKey();
    });

    livewire(ListPartners::class)
        ->searchTable($neighbourhood->name)
        ->assertCanSeeTableRecords($filteredPartners)
        ->assertCanNotSeeTableRecords($partners->diff($filteredPartners));
});

/** Filter */
it('can filter Partners by neighbourhood', function () {
    $neighbourhoods = Neighbourhood::factory(3)->create();
    $partners = Partner::factory(10)->create()->each(function (Partner $partner) use ($neighbourhoods) {
        $partner->neighbourhood()->associate($neighbourhoods->random());
        $partner->save();
    });

    $neighbourhood = $neighbourhoods->first();
    $filteredPartners = $partners->filter(function (Partner $partner) use ($neighbourhood) {
        return $partner->neighbourhood->getKey() === $neighbourhood->getKey();
    });

    livewire(ListPartners::class)
        ->assertCanSeeTableRecords($partners)
        ->filterTable('neighbourhood_id', $neighbourhood->getKey())
        ->assertCanSeeTableRecords($filteredPartners)
        ->assertCanNotSeeTableRecords($partners->diff($filteredPartners));
});
