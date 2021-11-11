<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Models\Messages\Ledger;

use App\Exceptions\Breaker;
use App\Models\Messages\Ledger\Domain;
use App\Models\Messages\Message;
use Tests\TestCase;


class DomainTest extends TestCase
{
    protected array $base = [
        'code' => 'GL',
        'names' => [
            ['name' => 'In English', 'language' => 'en'],
            ['name' => 'en francais', 'language' => 'fr'],
        ],
        'currency' => 'CAD',
    ];

    public function testFromRequestAdd()
    {
        $domain = Domain::fromRequest(
            $this->base, Message::OP_ADD | Message::FN_VALIDATE
        );
        $this->assertEquals('GL', $domain->code);
        $this->assertCount(2, $domain->names);
        $this->assertEquals('CAD', $domain->currencyDefault);
    }

    public function testFromRequestAdd_bad()
    {
        $base = $this->base;
        unset($base['names']);
        $this->expectException(Breaker::class);
        Domain::fromRequest(
            $base, Message::OP_ADD | Message::FN_VALIDATE
        );
    }

    public function testFromRequestDelete()
    {
        $base = $this->base;
        unset($base['names']);
        $base['revision'] = 'revision-code';
        $domain = Domain::fromRequest(
            $base, Message::OP_DELETE | Message::FN_VALIDATE
        );
        $this->assertEquals('GL', $domain->code);
        $this->assertEquals('CAD', $domain->currencyDefault);
    }

    public function testFromRequestDelete_bad()
    {
        $base = $this->base;
        unset($base['names']);
        $domain = Domain::fromRequest(
            $base, Message::OP_DELETE | Message::FN_VALIDATE
        );
        $this->assertEquals('GL', $domain->code);
        $this->assertEquals('CAD', $domain->currencyDefault);
    }

    public function testFromRequestGet()
    {
        $domain = Domain::fromRequest(
            $this->base, Message::OP_ADD | Message::FN_VALIDATE
        );
        $this->assertEquals('GL', $domain->code);
        $this->assertCount(2, $domain->names);
        $this->assertEquals('CAD', $domain->currencyDefault);
    }

    public function testFromRequestUpdate()
    {
        $domain = Domain::fromRequest(
            $this->base, Message::OP_ADD | Message::FN_VALIDATE
        );
        $this->assertEquals('GL', $domain->code);
        $this->assertCount(2, $domain->names);
        $this->assertEquals('CAD', $domain->currencyDefault);
    }

}
